# SaaSify — Enterprise Multi-Tenant Subscription & GST Billing Engine

A production-grade, full-stack B2B SaaS billing engine built with **PHP 8.2+ Clean MVC Architecture**, featuring row-level scoped data isolation, state-machine driven subscription lifecycles, Razorpay UPI QR Code & Stripe checkout, an immutable 18% GST tax invoicing studio, Developer REST API v1 with API keys, and a cyber-kinetic telemetry dashboard.

---

## 🚀 Key Architectural & Enterprise Features

* **Zero-Bloat Custom MVC Engine:** PSR-4 compliant architecture with a front-controller pattern, dynamic regex router, and multi-layered middleware pipeline (CSRF, Rate Limiting, RBAC, API Key Auth).
* **Row-Level Multi-Tenancy:** 100% strict data isolation across organizations using automatic tenant scoping and repository-level parameterized query guards.
* **18% GST Tax Invoicing Studio:** Real Indian GST compliance with automatic CGST (9%) + SGST (9%) or IGST (18%) interstate calculations, HSN/SAC codes, and printable tax receipts.
* **1-Click PDF Download & Client Emailing:** Instant client-side and server-ready PDF generation (`html2pdf.js`), WhatsApp payment links, and email dispatch with payment portal tokens.
* **Razorpay UPI & Stripe Payment Gateway:** Interactive public payment portal (`/pay/{token}`) with real-time UPI QR code generation (`upi://pay?pa=...`), Card checkout, and instant status settlement.
* **Idempotent Webhook Engine:** Resilient webhook receiver with HMAC-SHA256 cryptographic signature verification and event-level deduplication to prevent double-billing.
* **Developer REST API v1:** Bearer token (`ak_live_...`) authenticated endpoints for external microservices to query invoices, forge bills, and fetch catalog tiers.
* **Cyber-Kinetic Cockpit Dashboard:** Dark obsidian glassmorphic UI with dynamic animated telemetry dials, money pipeline visualizer, and Dynamic Island navigation dock.
* **Platform Super Admin Master Control:** Executive console (`/admin/dashboard`) with platform MRR calculation, tenant lifecycle suspension/reactivation, and audit logging.

---

## 📂 Architecture & Directory Breakdown

```text
saas-billing-engine/
├── config/              # App, Database PDO, and Gateway Secret Configurations
├── database/            # Relational Schema, Seed Data, and CLI Migrator
├── public/              # Web Server Document Root (Front Controller & Assets)
│   ├── index.php        # Central HTTP Request Dispatcher
│   └── .htaccess        # Apache mod_rewrite rule redirection
├── routes/
│   ├── web.php          # Web Portal Routes (Dashboard, Invoices, Plans, Admin)
│   └── api.php          # Developer REST API v1 & Stripe/Razorpay Webhook Endpoints
├── src/
│   ├── Core/            # Framework Core (Router, Request, Response, Database, Session, View)
│   ├── Middlewares/     # AuthGuard, TenantScope, AdminGuard, ApiKeyMiddleware, CsrfGuard
│   ├── Models/          # Database Entities (Tenant, User, Plan, Subscription, Invoice)
│   ├── Repositories/    # Scoped Data Access Layer (Tenant, Invoice, Customer, ApiKey)
│   ├── Services/        # Domain Business Logic (Auth, Subscription, Invoicing, Webhooks)
│   └── Controllers/     # HTTP Handlers (Dashboard, Invoices, Settings, ApiController, SuperAdmin)
└── views/               # Responsive Obsidian & Tailwind CSS Templates
    ├── layouts/         # Master layouts (main.php, admin.php)
    ├── admin/           # Super Admin Console (dashboard, tenants, webhooks, login)
    ├── dashboard/       # Cyber-Kinetic Cockpit & Webhook Simulator
    ├── invoices/        # Tax Invoicing (list, create, view, print, public_pay)
    └── settings/        # Organization Profile, GST Details & Developer API Keys
```

---

## 🛠 Local Setup & Running Instructions

1. Ensure **Apache & MySQL** are running in **XAMPP**.
2. Run database migration and seed:
   ```bash
   cd C:\xampp\htdocs\saas-billing-engine
   php database/migrate.php
   ```
3. Open your browser:
   ```text
   http://localhost/saas-billing-engine/public/
   ```

---

## 🔐 Default Access Credentials

### 🏢 Tenant Workspace Portal:
* **Login URL:** `http://localhost/saas-billing-engine/public/login`
* **Email:** `owner@acme.com`
* **Password:** `Password123!`
* **Role:** Organization Owner (Acme Cloud Technologies)
* *(1-Click Autofill button available on login page)*

### 🛡️ Platform Super Admin Console:
* **Login URL:** `http://localhost/saas-billing-engine/public/admin/login`
* **Email:** `superadmin@saasify.app`
* **Password:** `AdminPass123!`
* **Role:** Platform Super Administrator
* *(1-Click Autofill button available on admin login page)*

---

## 🔌 Developer REST API (v1)

Authenticate all API requests with an API key generated in **Settings → Developer REST API**:

```bash
# Set Header
Authorization: Bearer ak_live_your_secret_token_here
```

### Endpoints:

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/docs` | Public API documentation (JSON) |
| `GET` | `/api/v1/invoices` | List all tenant invoices (supports `?status=open\|paid`) |
| `POST` | `/api/v1/invoices` | Forge a new GST tax invoice with line items |
| `GET` | `/api/v1/invoices/{id}` | Retrieve complete invoice details & payment URL |
| `GET` | `/api/v1/customers` | List all registered B2B customers |
| `GET` | `/api/v1/plans` | Retrieve active subscription tiers |

#### Example: Forge Invoice via cURL:
```bash
curl -X POST "http://localhost/saas-billing-engine/public/api/v1/invoices" \
  -H "Authorization: Bearer ak_live_your_token_here" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_name": "Infosys BPM Ltd",
    "customer_email": "billing@infosys.com",
    "customer_gstin": "29AAACI1234A1Z5",
    "items": [
      {
        "description": "Cloud Infrastructure Management Node",
        "hsn": "998313",
        "qty": 1,
        "rate": 50000.00,
        "tax_rate": 18
      }
    ]
  }'
```

---

## 🧪 Comprehensive Automated E2E Testing Suite

Run the full 5-step automated end-to-end verification suite in terminal:

```bash
php "C:\Users\UV\.gemini\antigravity\brain\050e945b-4568-432a-b0ff-1e7cbc4d26c9\scratch\execute_all_steps_test.php"
```

**Automated Test Scope:**
1. Tenant Workspace Authentication (`owner@acme.com`).
2. Cyber-Kinetic Cockpit Dashboard Engine (HTTP 200, 116 KB payload, Reactor Dial).
3. Client CRM Registration & 18% GST Invoice Forging (`INV-2026-XXXX`, Gross ₹29,500.00).
4. Subscription Plans & Matrix Verification (Starter, Professional, Enterprise).
5. Platform Super Admin Master Control (`superadmin@saasify.app`).