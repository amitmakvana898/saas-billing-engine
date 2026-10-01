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
}
