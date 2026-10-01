<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\CustomerRepository;
use App\Repositories\AuditLogRepository;

class CustomerController
{
    private CustomerRepository $customerRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->customerRepo = new CustomerRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function index(Request $request): Response
    {
        $tenant = current_tenant();
        $customers = $this->customerRepo->listByTenant($tenant['id']);
        $stats = $this->customerRepo->getStats($tenant['id']);

        return view('customers.index', [
            'title' => 'Client & Customer Management - SaaSify',
            'customers' => $customers,
            'stats' => $stats,
            'tenant' => $tenant,
            'user' => auth_user(),
        ]);
    }

    public function store(Request $request): Response
    {
        $tenant = current_tenant();
        $user = auth_user();

        $name = trim($request->input('name') ?? '');
        $email = trim($request->input('email') ?? '');
        $phone = trim($request->input('phone') ?? '');
        $company = trim($request->input('company_name') ?? '');
        $gstin = strtoupper(trim($request->input('gstin') ?? ''));
        $address = trim($request->input('address') ?? '');
        $city = trim($request->input('city') ?? '');
        $state = trim($request->input('state') ?? '');
        $pincode = trim($request->input('pincode') ?? '');

        if (empty($name) || empty($email)) {
            flash('error', 'Client Name and Contact Email are required.');
            return redirect('/customers');
        }

        $id = $this->customerRepo->create([
            'tenant_id' => $tenant['id'],
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company_name' => $company,
            'gstin' => $gstin,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
        ]);

        $this->auditRepo->log(
            $tenant['id'],
            $user['id'] ?? null,
            $user['name'] ?? 'User',
            'customer.created',
            "Added client '{$name}' (" . ($company ?: 'Individual') . ")"
        );

        flash('success', "Client '{$name}' has been successfully registered!");
        return redirect('/customers');
    }

    public function delete(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $user = auth_user();

        $customer = $this->customerRepo->findById($id, $tenant['id']);
        if ($customer) {
            // Check if customer has any invoices
            $pdo = \App\Core\Database::getConnection();
            $invStmt = $pdo->prepare('SELECT COUNT(*) FROM `invoices` WHERE `customer_id` = :cid AND `tenant_id` = :tid');
            $invStmt->execute(['cid' => $id, 'tid' => $tenant['id']]);
            $invCount = (int)$invStmt->fetchColumn();

            if ($invCount > 0) {
                flash('error', "Cannot delete client '{$customer['name']}' because they have {$invCount} invoice(s) on record. Please keep this client for GST accounting and audit compliance.");
                return redirect('/customers');
            }

            $this->customerRepo->delete($id, $tenant['id']);
            $this->auditRepo->log(
                $tenant['id'],
                $user['id'] ?? null,
                $user['name'] ?? 'User',
                'customer.deleted',
                "Removed client '{$customer['name']}'"
            );
            flash('success', "Client '{$customer['name']}' was removed.");
        } else {
            flash('error', 'Client not found.');
        }

        return redirect('/customers');
    }

    public function update(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $user = auth_user();

        $customer = $this->customerRepo->findById($id, $tenant['id']);
        if (!$customer) {
            flash('error', 'Client record not found.');
            return redirect('/customers');
        }

        $name = trim($request->input('name') ?? '');
        $email = trim($request->input('email') ?? '');
        $phone = trim($request->input('phone') ?? '');
        $company = trim($request->input('company_name') ?? '');
        $gstin = strtoupper(trim($request->input('gstin') ?? ''));
        $address = trim($request->input('address') ?? '');
        $city = trim($request->input('city') ?? '');
        $state = trim($request->input('state') ?? '');
        $pincode = trim($request->input('pincode') ?? '');

        if (empty($name) || empty($email)) {
            flash('error', 'Client Name and Contact Email are required.');
            return redirect('/customers');
        }

        $this->customerRepo->update($id, $tenant['id'], [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company_name' => $company,
            'gstin' => $gstin,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
        ]);

        $this->auditRepo->log(
            $tenant['id'],
            $user['id'] ?? null,
            $user['name'] ?? 'User',
            'customer.updated',
            "Updated client details for '{$name}'"
        );

        flash('success', "Client '{$name}' details updated successfully!");
        return redirect('/customers');
    }

    public function apiSearch(Request $request): Response
    {
        $tenant = current_tenant();
        $customers = $this->customerRepo->listByTenant($tenant['id']);
        return Response::json($customers);
    }

    public function downloadSampleCsv(?Request $request = null): Response
    {
        $headers = ['Name', 'Company Name', 'Email', 'Phone', 'GSTIN', 'Address', 'City', 'State', 'Pincode'];
        $sampleRows = [
            ['Tata Consultancy Services', 'TCS Limited', 'procurement@tcs.com', '+91 9820011223', '27AAACT2727Q1ZW', 'TCS House Raveline Street', 'Mumbai', 'Maharashtra', '400001'],
            ['Infosys BPM', 'Infosys Limited', 'vendor-desk@infosys.com', '+91 8028520261', '29AAACI1234A1Z5', 'Electronics City Hosur Road', 'Bengaluru', 'Karnataka', '560100'],
            ['Reliance Jio Platforms', 'Jio Platforms Ltd', 'billing@jio.com', '+91 2244778899', '24AABCR2020A1Z9', 'Reliance Corporate Park Ghansoli', 'Navi Mumbai', 'Maharashtra', '400701']
        ];

        $stream = fopen('php://temp', 'r+');
        fputs($stream, "\xEF\xBB\xBF"); // UTF-8 BOM
        fputcsv($stream, $headers);
        foreach ($sampleRows as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        return new Response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sample_clients_template.csv"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function importCsv(Request $request): Response
    {
        $tenant = current_tenant();
        $user = auth_user();

        if (empty($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please choose a valid CSV file to upload.');
            return redirect('/customers');
        }

        $fileTmp = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];

        if (!str_ends_with(strtolower($fileName), '.csv')) {
            flash('error', 'Only .csv files are supported for bulk client import.');
            return redirect('/customers');
        }

        $handle = fopen($fileTmp, 'r');
        if (!$handle) {
            flash('error', 'Failed to read uploaded CSV file.');
            return redirect('/customers');
        }

        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            flash('error', 'Uploaded CSV file appears to be empty.');
            return redirect('/customers');
        }

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                continue;
            }

            $name = trim($row[0] ?? '');
            $company = trim($row[1] ?? '');
            $email = trim($row[2] ?? '');
            $phone = trim($row[3] ?? '');
            $gstin = strtoupper(trim($row[4] ?? ''));
            $address = trim($row[5] ?? '');
            $city = trim($row[6] ?? '');
            $state = trim($row[7] ?? '');
            $pincode = trim($row[8] ?? '');

            if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            // Check if customer email already exists in tenant
            $existing = $this->customerRepo->findByEmail($email, $tenant['id']);
            if ($existing) {
                $skipped++;
                continue;
            }

            $this->customerRepo->create([
                'tenant_id' => $tenant['id'],
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company_name' => $company,
                'gstin' => $gstin,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'pincode' => $pincode,
            ]);
            $imported++;
        }

        fclose($handle);

        $this->auditRepo->log(
            $tenant['id'],
            $user['id'] ?? null,
            $user['name'] ?? 'User',
            'customer.bulk_imported',
            "Bulk imported {$imported} clients via CSV ({$skipped} skipped/duplicates)"
        );

        flash('success', "Import completed: {$imported} clients successfully registered! ({$skipped} duplicates/invalid rows skipped).");
        return redirect('/customers');
    }
}
