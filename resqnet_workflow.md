# ResQNet — Complete Feature & Workflow Guide

gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ touch Procfile
gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ git add Procfile
gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ git commit -m "Add Procfile for Heroku"
[main eb94cf9] Add Procfile for Heroku
 1 file changed, 1 insertion(+)
 create mode 100644 Procfile
gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ heroku create
Creating app... done, ⬢ cryptic-meadow-34370
https://cryptic-meadow-34370-866a112f207f.herokuapp.com/ | https://git.heroku.com/cryptic-meadow-34370.git
gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ heroku addons:create jawsdb:kitefin
Creating jawsdb:kitefin on ⬢ cryptic-meadow-34370... free
Resource is being provisioned.
jawsdb-flat-97131 is being created in the background. The app will restart when complete...
Run heroku addons:info jawsdb-flat-97131 to check creation progress.
Run heroku addons:docs jawsdb to view documentation.
gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ heroku config:get JAWSDB_URL

gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ heroku config:get JAWSDB_URL
mysql://zsff09zc7urqdde3:jhacy11n42dec4bo@muowdopceqgxjn2b3.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/pb65ef5mkoa0rw2t
gurung-samriddhi@gurung-samriddhi-Inspiron-5402:~/Projects/ResQNet/ResQNet-backend$ 
> **Three User Personas** · **Two Interfaces** · **One Unified API Backend**

This document maps every feature from **Authentication → Dashboard → Core Modules → Logout** for each user type, grounded in the actual codebase.

---

## System Architecture Overview

```mermaid
graph TB
    subgraph Clients
        MA["📱 Mobile App<br/>(React Native + Expo)"]
        WP["🖥️ Web Admin Panel<br/>(Laravel Blade)"]
    end

    subgraph Backend["Laravel 11 Backend"]
        API["RESTful API<br/>(Sanctum Tokens)"]
        WEB["Web Routes<br/>(Session Auth)"]
        MW["Middleware Layer<br/>auth · is_active · role"]
    end

    DB[(MySQL 8.0)]

    MA -->|"api/v1/*"| API
    WP -->|"admin/* · organization/*"| WEB
    API --> MW --> DB
    WEB --> MW --> DB
```

### Roles & Permissions (Spatie)

| Role | Guard | Interface | Auth Method |
|------|-------|-----------|-------------|
| `public_user` | `sanctum` | Mobile App | Token-based (Sanctum) |
| `organization` | `web` | Admin Panel | Session-based |
| `super_admin` | `web` | Admin Panel | Session-based |

---

## 🟢 PERSONA 1: Public User (Mobile App)

### 1.1 Authentication Flow

```mermaid
sequenceDiagram
    participant U as 📱 User
    participant API as 🔌 API
    participant DB as 🗄️ Database
    participant Mail as 📧 Email

    Note over U,Mail: REGISTRATION FLOW
    U->>API: POST /api/auth/register<br/>{name, email, password, password_confirmation, phone}
    API->>API: Validate (min 8 chars, mixed case, numbers, symbols, uncompromised)
    API->>DB: Create User (is_active=true, role=public_user)
    API->>Mail: Send 6-digit OTP (cached 10 min, 60s cooldown)
    API-->>U: 201 {status, message, user}

    Note over U,Mail: OTP VERIFICATION
    U->>API: POST /api/auth/verify-otp<br/>{email, otp}
    API->>API: Check OTP from Cache
    API->>DB: Mark email_verified_at = now()
    API-->>U: 200 {status: success}

    Note over U,Mail: LOGIN FLOW
    U->>API: POST /api/auth/login<br/>{email, password}
    API->>API: Check credentials, is_active, email_verified_at
    API->>DB: Update last_login_at
    API-->>U: 200 {user, token}
```

**Key Endpoints:**

| Action | Method | Route | Controller |
|--------|--------|-------|------------|
| Register | `POST` | `/api/auth/register` | `Api\AuthController@register` |
| Login | `POST` | `/api/auth/login` | `Api\AuthController@login` |
| Verify OTP | `POST` | `/api/auth/verify-otp` | `Api\AuthController@verifyOtp` |
| Resend OTP | `POST` | `/api/auth/resend-otp` | `Api\AuthController@resendOtp` |
| Forgot Password | `POST` | `/api/auth/forgot-password` | `Api\AuthController@forgotPassword` |
| Reset Password | `POST` | `/api/auth/reset-password` | `Api\AuthController@resetPassword` |

**Auth Guards:**
- All protected mobile routes use `auth:sanctum` middleware
- The `is_active` middleware returns a `403` JSON response for suspended users

---

### 1.2 Mobile App Screens (Tab Navigation)

After login, users land on the **Home tab**. The mobile app uses Expo Router with a tab layout:

| Tab | Screen File | Purpose |
|-----|------------|---------|
| 🏠 Home | `app/(tabs)/home.tsx` | Browse adoptable pets, search & filter |
| 🆘 Rescue | `app/(tabs)/rescue.tsx` | Report animals in distress |
| 💰 Donate | `app/(tabs)/donate.tsx` | Donate to verified organizations |
| ❤️ Favorites | `app/(tabs)/favorites.tsx` | Saved/liked pet listings |
| 👤 Profile | `app/(tabs)/profile.tsx` | User profile, history, settings |

---

### 1.3 Feature: Browse & Adopt Pets

```mermaid
flowchart LR
    A["🏠 Home Tab"] --> B["Browse Pet Listings"]
    B --> C["Filter: species, size, gender, location"]
    B --> D["View Pet Detail"]
    D --> E["See Health Summary"]
    D --> F["Submit Adoption Request"]
    F --> G["Track Request Status"]
```

| Action | Method | Route | Auth Required |
|--------|--------|-------|---------------|
| List all pets | `GET` | `/api/pets` | ❌ No |
| View pet detail | `GET` | `/api/pets/{pet}` | ❌ No |
| Submit adoption | `POST` | `/api/adoptions` | ✅ Yes |
| My adoptions | `GET` | `/api/adoptions` | ✅ Yes |
| Adoption detail | `GET` | `/api/adoptions/{request}` | ✅ Yes |

**Adoption Request Fields:** `pet_id`, `message`, `living_situation`, `has_other_pets`
**Status Lifecycle:** `pending` → `approved` / `rejected`

---

### 1.4 Feature: Rescue Reporting

```mermaid
flowchart LR
    A["🆘 Rescue Tab"] --> B["Tap 'Report'"]
    B --> C["Capture Photo"]
    B --> D["Auto-detect GPS"]
    B --> E["Select Urgency Level"]
    B --> F["Add Description"]
    C & D & E & F --> G["Submit Report"]
    G --> H["Track Status in Real-time"]
```

| Action | Method | Route | Auth Required |
|--------|--------|-------|---------------|
| Submit report | `POST` | `/api/rescues` | ✅ Yes |
| My reports | `GET` | `/api/rescues` | ✅ Yes |
| Report detail | `GET` | `/api/rescues/{report}` | ✅ Yes |

**Report Fields:** `title`, `description`, `image_path`, `latitude`, `longitude`, `address`, `urgency`
**Status Lifecycle:** `reported` → `assigned` → `in_progress` → `resolved`

---

### 1.5 Feature: Donations

```mermaid
flowchart LR
    A["💰 Donate Tab"] --> B["Browse Verified Orgs"]
    B --> C["Select Organization"]
    C --> D["Enter Amount"]
    D --> E{"Payment Gateway"}
    E --> F["Khalti"]
    E --> G["eSewa"]
    F & G --> H["Payment Verification Callback"]
    H --> I["View Donation History & Receipt"]
```

| Action | Method | Route | Auth Required |
|--------|--------|-------|---------------|
| View donation history | `GET` | `/api/donations` | ✅ Yes |
| Make donation | `POST` | `/api/donations` | ✅ Yes |

**Donation Fields:** `organization_id`, `amount`, `payment_method` (khalti/esewa), `transaction_id`, `remarks`
**Status Lifecycle:** `pending` → `completed` / `failed`

---

### 1.6 Feature: Profile & QR Scan

| Action | Method | Route | Auth Required |
|--------|--------|-------|---------------|
| View profile | `GET` | `/api/user` | ✅ Yes |
| Update profile | `PATCH` | `/api/user` | ✅ Yes |

**Profile Features:**
- View & edit name, phone, address, avatar
- View all submitted rescue reports
- View all adoption request history
- View all donation history
- **QR Code Scanner** — scan an animal's QR tag to view its full profile + medical history

---

### 1.7 Logout (Mobile)

| Action | Method | Route | Auth Required |
|--------|--------|-------|---------------|
| Logout | `POST` | `/api/auth/logout` | ✅ Yes |

**Mechanism:** Deletes the current Sanctum access token → user is unauthenticated on next request.

---

## 🔵 PERSONA 2: Organization Admin (Web Admin Panel)

### 2.1 Registration Flow (Multi-Step, Requires Approval)

```mermaid
sequenceDiagram
    participant OA as 🏥 Org Admin
    participant WEB as 🌐 Web Panel
    participant DB as 🗄️ Database
    participant SA as ⚙️ System Admin

    OA->>WEB: GET /register-organization
    WEB-->>OA: Registration Form
    OA->>WEB: POST /register-organization<br/>{user_name, user_email, password,<br/>org_name, org_email, registration_number, document}
    WEB->>DB: Create User (is_active=FALSE, role=organization)
    WEB->>DB: Create Organization (status=pending, is_verified=false)
    WEB->>DB: Store registration document (Spatie MediaLibrary)
    WEB-->>OA: Redirect → /registration-pending

    Note over OA,SA: ⏳ WAITING FOR ADMIN APPROVAL
    SA->>DB: Approve org → status=active, user.is_active=true
    OA->>WEB: GET /organization/login
    OA->>WEB: POST /organization/login {email, password}
    WEB->>WEB: Check: hasRole('organization') ✅<br/>Check: is_active ✅
    WEB-->>OA: Redirect → /organization/dashboard
```

**Key Endpoints:**

| Action | Method | Route | Controller |
|--------|--------|-------|------------|
| Show form | `GET` | `/register-organization` | `Auth\RegisterOrganizationController@create` |
| Submit | `POST` | `/register-organization` | `Auth\RegisterOrganizationController@store` |
| Pending page | `GET` | `/registration-pending` | Static Blade view |
| Login form | `GET` | `/organization/login` | `Auth\OrganizationLoginController@create` |
| Login submit | `POST` | `/organization/login` | `Auth\OrganizationLoginController@store` |

**Login Validation Checks (in order):**
1. Credentials match → else "credentials do not match"
2. `is_active` = true → else "account is not active" (auto-logout)
3. `hasRole('organization')` → else "no permission" (auto-logout)

---

### 2.2 Dashboard

**Route:** `GET /organization/dashboard` → `Web\Organization\DashboardController@index`
**Middleware:** `auth` · `is_active` · `role:organization`

**Dashboard Metrics:**

| Stat Card | Data Source |
|-----------|------------|
| Active Pets | `organization->pets()->count()` |
| Pending Adoptions | Adoption requests with status=pending |
| Active Rescues | Rescue reports in progress |
| Pets In Care | Pets with status=available |
| Total Donations | Sum of completed donations |

---

### 2.3 Feature: Pet Management (CRUD + QR)

```mermaid
flowchart TB
    A["🐾 Pet Management"] --> B["View All Pets<br/>GET /organization/pets"]
    A --> C["Add New Pet<br/>POST /organization/pets"]
    A --> D["Edit Pet<br/>PUT /organization/pets/{id}"]
    A --> E["Delete Pet<br/>DELETE /organization/pets/{id}"]
    A --> F["Change Status<br/>POST /organization/pets/{id}/status"]
    A --> G["Generate QR<br/>GET /organization/pets/{id}/qr"]

    C --> C1["Upload Primary Image"]
    C --> C2["Upload Gallery Images"]
    C --> C3["Auto-generate QR Code ID<br/>(RQN-00001 format)"]
```

| Action | Method | Route | Controller |
|--------|--------|-------|------------|
| List pets | `GET` | `/organization/pets` | `Org\PetController@index` |
| Create pet | `POST` | `/organization/pets` | `Org\PetController@store` |
| View pet | `GET` | `/organization/pets/{pet}` | `Org\PetController@show` |
| Update pet | `PUT` | `/organization/pets/{pet}` | `Org\PetController@update` |
| Delete pet | `DELETE` | `/organization/pets/{pet}` | `Org\PetController@destroy` |
| Change status | `POST` | `/organization/pets/{pet}/status` | `Org\PetController@changeStatus` |
| Get QR | `GET` | `/organization/pets/{pet}/qr` | `Org\PetController@generateQr` |

**Pet Fields:** `name`, `species`, `breed`, `age_months`, `gender`, `size`, `color`, `description`, `health_status`, `is_vaccinated`, `is_neutered`, `status`
**Status Values:** `available` · `adopted` · `unavailable` · `inactive`
**QR Code:** Auto-generated on creation as `RQN-XXXXX` (zero-padded)
**Images:** Primary image + gallery, stored via `pets/{id}/` on public disk

---

### 2.4 Feature: Adoption Management

```mermaid
flowchart LR
    A["📋 Adoption Requests"] --> B["View All Requests<br/>GET /organization/adoptions"]
    B --> C["Review Applicant Details"]
    C --> D{"Decision"}
    D -->|Approve| E["POST /organization/adoptions/{id}/approve<br/>→ Pet status = adopted"]
    D -->|Reject| F["POST /organization/adoptions/{id}/reject<br/>→ Add admin_notes"]
```

| Action | Method | Route |
|--------|--------|-------|
| List requests | `GET` | `/organization/adoptions` |
| Approve | `POST` | `/organization/adoptions/{request}/approve` |
| Reject | `POST` | `/organization/adoptions/{request}/reject` |

---

### 2.5 Feature: Rescue Report Management

```mermaid
flowchart LR
    A["🚨 Rescue Reports"] --> B["View Incoming Reports<br/>GET /organization/rescues"]
    B --> C["Update Status<br/>POST /organization/rescues/{id}/update"]
    C --> D["reported → assigned → in_progress → resolved"]
    B --> E["Link to Pet Record<br/>POST /organization/rescues/{id}/link"]
```

| Action | Method | Route |
|--------|--------|-------|
| View reports | `GET` | `/organization/rescues` |
| Update status | `POST` | `/organization/rescues/{report}/update` |
| Link to pet | `POST` | `/organization/rescues/{report}/link` |

---

### 2.6 Feature: Health & Veterinary Records

```mermaid
flowchart TB
    A["🏥 Health Management"] --> B["Manage Veterinarians"]
    A --> C["Medical Records"]
    A --> D["Vaccinations"]
    A --> E["Medications"]

    B --> B1["Register Vet<br/>(name, phone, specialization, license)"]
    C --> C1["Log Checkup / Surgery / Treatment<br/>Linked to Pet + Vet"]
    D --> D1["Record Vaccine<br/>(name, batch, date, next_due)"]
    E --> E1["Prescribe Medication<br/>(medicine, dosage, frequency, duration)"]
```

**Route:** `resource /organization/health` → `Org\HealthController`

**Data Models:**

| Model | Key Fields | Relationships |
|-------|-----------|---------------|
| `Vet` | name, phone, specialization, license_number, is_active | → Organization, → MedicalRecords |
| `MedicalRecord` | type, title, description, record_date, next_visit_date | → Pet, → Vet |
| `Vaccination` | vaccine_name, batch_number, date_administered, next_due_date | → Pet, → Vet |
| `Medication` | medicine_name, dosage, frequency, start_date, end_date | → Pet, → Vet |

---

### 2.7 Feature: Staff Management

| Action | Method | Route |
|--------|--------|-------|
| View staff | `GET` | `/organization/staff` |
| Invite member | `POST` | `/organization/staff/invite` |
| Remove member | `DELETE` | `/organization/staff/{user}` |

**Invite Fields:** `name`, `email`, `role` (organization/staff/vet)
**Mechanism:** Creates user with random password, assigns role via Spatie

---

### 2.8 Feature: Finance & Donations

| Action | Method | Route |
|--------|--------|-------|
| View donations | `GET` | `/organization/finance` |

Displays: incoming donation list, transaction IDs, donor info, amount breakdowns

---

### 2.9 Feature: Organization Profile

| Action | Method | Route |
|--------|--------|-------|
| Edit profile | `GET` | `/organization/profile` |
| Update profile | `PATCH` | `/organization/profile` |

**Editable Fields:** name, description, logo, phone, email, address, lat/lng

---

### 2.10 Logout (Organization)

| Action | Method | Route | Controller |
|--------|--------|-------|------------|
| Logout | `POST` | `/organization/logout` | `Auth\OrganizationLoginController@destroy` |

**Mechanism:** `Auth::guard('web')->logout()` → session invalidate → regenerate CSRF → redirect to `/organization/login`

---

## 🔴 PERSONA 3: System Admin (Web Admin Panel)

### 3.1 Login Flow

```mermaid
sequenceDiagram
    participant SA as ⚙️ System Admin
    participant WEB as 🌐 Web Panel
    participant DB as 🗄️ Database

    SA->>WEB: GET /admin/login
    WEB-->>SA: Admin Login Form
    SA->>WEB: POST /admin/login {email, password, remember?}
    WEB->>DB: Auth::attempt()
    WEB->>WEB: Check hasRole('super_admin') ✅
    WEB->>WEB: Check is_active ✅
    WEB-->>SA: Redirect → /admin/dashboard
```

| Action | Method | Route | Controller |
|--------|--------|-------|------------|
| Login form | `GET` | `/admin/login` | `Auth\AdminLoginController@create` |
| Login submit | `POST` | `/admin/login` | `Auth\AdminLoginController@store` |

**Login Validation (in order):**
1. Credentials match
2. `hasRole('super_admin')` → else "You do not have admin access" (auto-logout)
3. `is_active` = true → else "account deactivated" (auto-logout)

---

### 3.2 Dashboard

**Route:** `GET /admin/dashboard` → `Web\Admin\DashboardController@index`
**Middleware:** `auth` · `is_active` · `role:super_admin`

**Dashboard Metrics:**

| Stat Card | Data Source |
|-----------|------------|
| Total Organizations | `Organization::count()` |
| Pending Organizations | `Organization::where('status','pending')->count()` |
| Total Users | `User::count()` |

---

### 3.3 Feature: Organization Management

```mermaid
flowchart TB
    A["🏢 Organization Management"] --> B["View All Orgs<br/>GET /admin/organizations"]
    B --> C["Pending Registrations"]
    C --> D{"Action"}
    D --> E["✅ Approve<br/>POST /admin/organizations/{id}/approve"]
    D --> F["❌ Reject<br/>POST /admin/organizations/{id}/reject"]
    B --> G["Active Organizations"]
    G --> H["⏸️ Suspend<br/>POST /admin/organizations/{id}/suspend"]
    G --> I["✔️ Verify Badge<br/>POST /admin/organizations/{id}/verify"]
```

| Action | Method | Route |
|--------|--------|-------|
| List all orgs | `GET` | `/admin/organizations` |
| Approve | `POST` | `/admin/organizations/{org}/approve` |
| Reject | `POST` | `/admin/organizations/{org}/reject` |
| Suspend | `POST` | `/admin/organizations/{org}/suspend` |
| Verify (badge) | `POST` | `/admin/organizations/{org}/verify` |

**Organization Status Lifecycle:** `pending` → `active` / `rejected` → `suspended`

---

### 3.4 Feature: User Management

| Action | Method | Route |
|--------|--------|-------|
| List all users | `GET` | `/admin/users` |
| Deactivate user | `POST` | `/admin/users/{user}/deactivate` |

---

### 3.5 Feature: Pet Oversight (Admin-Level CRUD)

| Action | Method | Route |
|--------|--------|-------|
| View all pets | `GET` | `/admin/pets` |
| Create pet | `POST` | `/admin/pets` |
| Update pet | `PUT` | `/admin/pets/{pet}` |
| Delete pet | `DELETE` | `/admin/pets/{pet}` |

System admin can manage pets across **all** organizations (platform-wide oversight).

---

### 3.6 Logout (System Admin)

| Action | Method | Route | Controller |
|--------|--------|-------|------------|
| Logout | `POST` | `/admin/logout` | `Auth\AdminLoginController@destroy` |

**Mechanism:** `Auth::guard('web')->logout()` → session invalidate → regenerate CSRF → redirect to `/admin/login` with success flash message.

---

## Cross-Cutting Concerns

### Middleware Pipeline

```mermaid
flowchart LR
    REQ["Incoming Request"] --> A{"auth / auth:sanctum"}
    A -->|Unauthenticated| R1["401 / Redirect to login"]
    A -->|Authenticated| B{"is_active"}
    B -->|Inactive| R2["403 / Force logout"]
    B -->|Active| C{"role:xxx"}
    C -->|Wrong role| R3["403 / Redirect"]
    C -->|Correct role| D["✅ Controller Action"]
```

### Password Reset Flow (Web — Shared)

| Action | Route |
|--------|-------|
| Forgot password form | `GET /admin/forgot-password` or `/organization/forgot-password` |
| Send reset link | `POST` (same) |
| Reset form | `GET /admin/reset-password/{token}` |
| Submit new password | `POST /admin/reset-password` |
| Confirm current password | `GET /confirm-password` (protected) |
| Update password | `PUT /password` (protected) |

### Password Reset Flow (Mobile API)

| Step | Route |
|------|-------|
| Request OTP | `POST /api/auth/forgot-password` |
| Reset with OTP | `POST /api/auth/reset-password` (validates OTP + new password) |
| Security | All existing tokens are deleted after password reset |

### QR Code System

```mermaid
flowchart LR
    A["Pet Created"] --> B["Auto-generate QR ID<br/>(RQN-00001)"]
    B --> C["Org: Print QR Tag"]
    C --> D["Physical Tag on Animal"]
    D --> E["Public User: Scan QR"]
    E --> F["View Full Profile + Medical History"]
```

### Data Integrity

- **Soft Deletes** on: User, Organization, Pet, AdoptionRequest, RescueReport, Donation, Vet, MedicalRecord, Vaccination, Medication
- **Transactions** used in Organization registration (User + Org + Document upload)
- **Foreign key constraints** enforced across all tables

---

## Implementation Status Summary

| Module | Mobile API | Web (Org) | Web (Admin) |
|--------|:----------:|:---------:|:-----------:|
| Auth (Register/Login/Logout) | ✅ Done | ✅ Done | ✅ Done |
| OTP Verification | ✅ Done | N/A | N/A |
| Password Reset | ✅ Done | ✅ Done | ✅ Done |
| Dashboard | 🔲 TODO | ✅ Done | ✅ Done |
| Pet Listing/Browse | 🔲 TODO | ✅ Done | ✅ Done |
| Pet CRUD | N/A | ✅ Done | ✅ Done |
| Adoption Request | 🔲 TODO | 🔲 TODO | N/A |
| Rescue Reporting | 🔲 TODO | 🔲 TODO | N/A |
| Donations | 🔲 TODO | 🔲 TODO | N/A |
| Health Records | N/A | 🔲 TODO | N/A |
| Staff Management | N/A | ✅ Done | N/A |
| Org Management | N/A | N/A | 🔲 TODO |
| User Management | N/A | N/A | 🔲 TODO |
| QR Code System | 🔲 TODO | ✅ Partial | N/A |
| Payment Gateway | 🔲 TODO | N/A | N/A |
| Profile Management | 🔲 TODO | 🔲 TODO | N/A |

> **Legend:** ✅ Done · 🔲 TODO · N/A = Not applicable for this persona
