-- ============================================================================
-- ResQNet Database Schema
-- Animal Rescue, Adoption, Donation & Health Management Platform
-- ============================================================================
--
-- ENTITY RELATIONSHIP SUMMARY:
--
--   users (1) ──── (0..1) organizations       A user with 'org_admin' role manages ONE organization
--   organizations (1) ──── (many) pets         An organization lists MANY pets
--   organizations (1) ──── (many) vets         An organization has MANY registered vets
--   pets (1) ──── (many) pet_images            A pet has MANY images
--   pets (1) ──── (many) adoption_requests     A pet receives MANY adoption requests
--   users (1) ──── (many) adoption_requests    A user submits MANY adoption requests
--   users (1) ──── (many) rescue_reports       A user reports MANY rescues
--   organizations (1) ──── (many) rescue_reports  An org handles MANY rescue reports
--   users (1) ──── (many) donations            A user makes MANY donations
--   organizations (1) ──── (many) donations    An org receives MANY donations
--   pets (1) ──── (many) medical_records       A pet has MANY medical records
--   pets (1) ──── (many) vaccinations          A pet receives MANY vaccinations
--   pets (1) ──── (many) medications           A pet is prescribed MANY medications
--   vets (1) ──── (many) medical_records       A vet records MANY medical entries
--   vets (1) ──── (many) vaccinations          A vet administers MANY vaccinations
--   vets (1) ──── (many) medications           A vet prescribes MANY medications
--
-- ROLE MANAGEMENT:
--   Roles are managed via Spatie Permission package (roles & permissions tables).
--   Three roles: 'public', 'org_admin', 'system_admin'
--   No role enum on the users table — Spatie handles this via pivot tables.
--
-- ============================================================================


-- ============================================================================
-- 1. USERS TABLE (modify existing Laravel default)
-- ============================================================================
-- The core authentication table. Extended with phone and avatar fields.
-- Relationships:
--   - Has ONE organization (if role is org_admin)
--   - Has MANY adoption_requests
--   - Has MANY rescue_reports
--   - Has MANY donations
-- Role is NOT stored here — managed by Spatie's model_has_roles pivot table.

ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(20) NULL AFTER `email`;
ALTER TABLE `users` ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `phone`;


-- ============================================================================
-- 2. ORGANIZATIONS TABLE
-- ============================================================================
-- Represents animal shelters and rescue groups (e.g., KAT Center, Sneha's Care).
-- Each organization is managed by exactly ONE user (the org_admin).
--
-- Relationships:
--   - BELONGS TO one user (user_id FK) — the org admin who manages it
--   - HAS MANY pets — animals listed by this organization
--   - HAS MANY vets — veterinarians registered under this organization
--   - HAS MANY rescue_reports — reports assigned to this organization
--   - HAS MANY donations — donations received by this organization
--
-- Why latitude/longitude?
--   Used for proximity-based rescue report routing — when a user reports
--   an animal in distress, the system can suggest the nearest organization.

CREATE TABLE `organizations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,              -- FK → users.id (the org admin)
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,                     -- URL-friendly name, unique
    `description` TEXT NULL,
    `logo` VARCHAR(255) NULL,                         -- Path to logo image
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(255) NULL,
    `address` VARCHAR(255) NULL,
    `latitude` DECIMAL(10, 8) NULL,                   -- GPS lat for proximity search
    `longitude` DECIMAL(11, 8) NULL,                  -- GPS lng for proximity search
    `is_verified` BOOLEAN NOT NULL DEFAULT FALSE,     -- System admin verifies orgs
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `organizations_slug_unique` (`slug`),
    UNIQUE KEY `organizations_user_id_unique` (`user_id`),  -- One user = one org

    CONSTRAINT `organizations_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE
        -- CASCADE: if the org admin's account is deleted, the org is also removed
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 3. PETS TABLE
-- ============================================================================
-- Animals listed by organizations for adoption.
-- Each pet belongs to exactly ONE organization.
--
-- Relationships:
--   - BELONGS TO one organization (organization_id FK)
--   - HAS MANY pet_images
--   - HAS MANY adoption_requests
--   - HAS MANY medical_records
--   - HAS MANY vaccinations
--   - HAS MANY medications
--
-- qr_code_id:
--   Auto-generated unique identifier (e.g., "RQN-00042") used for QR code
--   generation. Shelter staff print QR codes and attach them to animal
--   collars/tags. Scanning the QR opens the animal's full profile.
--
-- status flow: 'available' → 'adopted' (when adoption approved)
--              'available' → 'unavailable' (if pet is transferred/deceased)

CREATE TABLE `pets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,       -- FK → organizations.id
    `qr_code_id` VARCHAR(20) NOT NULL,                -- Unique ID for QR code (e.g., RQN-00042)
    `name` VARCHAR(255) NOT NULL,
    `species` ENUM('dog', 'cat', 'bird', 'rabbit', 'other') NOT NULL DEFAULT 'dog',
    `breed` VARCHAR(100) NULL,
    `age_months` INT UNSIGNED NULL,                    -- Age in months for flexibility
    `gender` ENUM('male', 'female') NOT NULL,
    `size` ENUM('small', 'medium', 'large') NOT NULL DEFAULT 'medium',
    `description` TEXT NULL,
    `health_status` TEXT NULL,                         -- Brief health overview
    `is_vaccinated` BOOLEAN NOT NULL DEFAULT FALSE,
    `is_neutered` BOOLEAN NOT NULL DEFAULT FALSE,
    `status` ENUM('available', 'adopted', 'unavailable') NOT NULL DEFAULT 'available',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `pets_qr_code_id_unique` (`qr_code_id`),
    INDEX `pets_organization_id_index` (`organization_id`),
    INDEX `pets_species_index` (`species`),            -- Speeds up filter queries
    INDEX `pets_status_index` (`status`),              -- Speeds up "available" filter

    CONSTRAINT `pets_organization_id_foreign`
        FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
        ON DELETE CASCADE
        -- CASCADE: if org is deleted, all their pet listings are removed
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 4. PET_IMAGES TABLE
-- ============================================================================
-- Multiple images per pet. One image is marked as primary (shown in listings).
--
-- Relationships:
--   - BELONGS TO one pet (pet_id FK)
--
-- Why a separate table instead of a JSON column on pets?
--   Normalized design is more scalable — you can query, count, and manage
--   images independently. Also makes it easy to set a primary image.

CREATE TABLE `pet_images` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pet_id` BIGINT UNSIGNED NOT NULL,                -- FK → pets.id
    `image_path` VARCHAR(255) NOT NULL,               -- Storage path to the image
    `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,      -- Only one should be TRUE per pet
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `pet_images_pet_id_index` (`pet_id`),

    CONSTRAINT `pet_images_pet_id_foreign`
        FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
        ON DELETE CASCADE
        -- CASCADE: if pet is deleted, all their images are removed
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 5. ADOPTION_REQUESTS TABLE
-- ============================================================================
-- When a public user wants to adopt a pet, they submit a request.
-- The organization admin reviews and approves/rejects it.
--
-- Relationships:
--   - BELONGS TO one pet (pet_id FK) — the pet being requested
--   - BELONGS TO one user (user_id FK) — the person requesting adoption
--
-- Status flow: 'pending' → 'approved' (org admin approves)
--              'pending' → 'rejected' (org admin rejects)
--
-- When approved: the pet's status should also change to 'adopted'
-- (handled in application logic, not DB trigger)

CREATE TABLE `adoption_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pet_id` BIGINT UNSIGNED NOT NULL,                -- FK → pets.id
    `user_id` BIGINT UNSIGNED NOT NULL,               -- FK → users.id (the adopter)
    `message` TEXT NULL,                               -- Why they want to adopt
    `living_situation` VARCHAR(255) NULL,              -- e.g., "House with yard"
    `has_other_pets` BOOLEAN NULL,                     -- Do they already have pets?
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT NULL,                           -- Org admin's internal notes
    `reviewed_at` TIMESTAMP NULL,                      -- When the decision was made
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `adoption_requests_pet_id_index` (`pet_id`),
    INDEX `adoption_requests_user_id_index` (`user_id`),
    INDEX `adoption_requests_status_index` (`status`),

    CONSTRAINT `adoption_requests_pet_id_foreign`
        FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `adoption_requests_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 6. RESCUE_REPORTS TABLE
-- ============================================================================
-- When a public user finds an injured/stray animal, they submit a report
-- with photo evidence and GPS location.
--
-- Relationships:
--   - BELONGS TO one user (user_id FK) — the person who reported
--   - BELONGS TO one organization (organization_id FK, NULLABLE) — assigned later
--
-- Why organization_id is NULLABLE:
--   When first submitted, no organization is assigned yet. A system admin
--   or the system itself (based on proximity) assigns an org later.
--
-- Status flow: 'reported' → 'assigned' (org assigned)
--              'assigned' → 'in_progress' (org is working on it)
--              'in_progress' → 'resolved' (animal rescued/treated)

CREATE TABLE `rescue_reports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,               -- FK → users.id (reporter)
    `organization_id` BIGINT UNSIGNED NULL,            -- FK → organizations.id (assigned org, nullable)
    `description` TEXT NOT NULL,                       -- What the user saw
    `image_path` VARCHAR(255) NULL,                    -- Photo evidence
    `latitude` DECIMAL(10, 8) NOT NULL,                -- GPS lat from phone
    `longitude` DECIMAL(11, 8) NOT NULL,               -- GPS lng from phone
    `address` VARCHAR(255) NULL,                       -- Human-readable address
    `urgency` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
    `status` ENUM('reported', 'assigned', 'in_progress', 'resolved') NOT NULL DEFAULT 'reported',
    `resolved_at` TIMESTAMP NULL,                      -- When the rescue was completed
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `rescue_reports_user_id_index` (`user_id`),
    INDEX `rescue_reports_organization_id_index` (`organization_id`),
    INDEX `rescue_reports_status_index` (`status`),
    INDEX `rescue_reports_urgency_index` (`urgency`),

    CONSTRAINT `rescue_reports_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `rescue_reports_organization_id_foreign`
        FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
        ON DELETE SET NULL
        -- SET NULL: if the org is deleted, the report stays but becomes unassigned
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 7. DONATIONS TABLE
-- ============================================================================
-- Financial donations from public users to organizations.
-- Integrated with Khalti / eSewa payment gateways.
--
-- Relationships:
--   - BELONGS TO one user (user_id FK) — the donor
--   - BELONGS TO one organization (organization_id FK) — the recipient
--
-- Status flow: 'pending' → 'completed' (payment verified via gateway callback)
--              'pending' → 'failed' (payment failed or timed out)
--
-- transaction_id: The unique ID returned by Khalti/eSewa after payment.
-- Used to verify the payment on the server side.

CREATE TABLE `donations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,               -- FK → users.id (donor)
    `organization_id` BIGINT UNSIGNED NOT NULL,        -- FK → organizations.id (recipient)
    `amount` DECIMAL(10, 2) NOT NULL,                  -- Amount in NPR
    `payment_method` ENUM('khalti', 'esewa') NOT NULL,
    `transaction_id` VARCHAR(255) NULL,                -- From payment gateway
    `status` ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `donations_user_id_index` (`user_id`),
    INDEX `donations_organization_id_index` (`organization_id`),
    INDEX `donations_status_index` (`status`),

    CONSTRAINT `donations_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `donations_organization_id_foreign`
        FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 8. VETS TABLE
-- ============================================================================
-- Veterinarians registered under an organization.
-- They are referenced in medical records, vaccinations, and medications
-- to track which vet performed which action.
--
-- Relationships:
--   - BELONGS TO one organization (organization_id FK)
--   - HAS MANY medical_records
--   - HAS MANY vaccinations
--   - HAS MANY medications

CREATE TABLE `vets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,        -- FK → organizations.id
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(255) NULL,
    `specialization` VARCHAR(255) NULL,                -- e.g., "Small Animal Surgery"
    `license_number` VARCHAR(100) NULL,                -- NVC registration number
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `vets_organization_id_index` (`organization_id`),

    CONSTRAINT `vets_organization_id_foreign`
        FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 9. MEDICAL_RECORDS TABLE
-- ============================================================================
-- Individual health entries for each pet (checkups, treatments, surgeries, etc).
-- Each record is logged by a specific vet.
--
-- Relationships:
--   - BELONGS TO one pet (pet_id FK)
--   - BELONGS TO one vet (vet_id FK)
--
-- This is the core of the health tracking system. Combined with vaccinations
-- and medications, it gives a complete medical history for each animal.
-- Accessible by scanning the animal's QR code.

CREATE TABLE `medical_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pet_id` BIGINT UNSIGNED NOT NULL,                 -- FK → pets.id
    `vet_id` BIGINT UNSIGNED NOT NULL,                 -- FK → vets.id
    `type` ENUM('checkup', 'treatment', 'surgery', 'injury', 'observation') NOT NULL,
    `title` VARCHAR(255) NOT NULL,                     -- e.g., "Leg fracture treatment"
    `description` TEXT NULL,                           -- Detailed notes
    `record_date` DATE NOT NULL,                       -- When this happened
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `medical_records_pet_id_index` (`pet_id`),
    INDEX `medical_records_vet_id_index` (`vet_id`),

    CONSTRAINT `medical_records_pet_id_foreign`
        FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `medical_records_vet_id_foreign`
        FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 10. VACCINATIONS TABLE
-- ============================================================================
-- Vaccination records for each pet. Tracks what was given, when, and when
-- the next dose is due.
--
-- Relationships:
--   - BELONGS TO one pet (pet_id FK)
--   - BELONGS TO one vet (vet_id FK) — who administered it
--
-- Status logic (computed in application, not DB):
--   - 'completed': date_administered is in the past, next_due_date is in the future
--   - 'upcoming': next_due_date is within 30 days
--   - 'overdue': next_due_date is in the past
--
-- Common vaccines for Nepal:
--   Dogs: Rabies, DHPP (Distemper, Hepatitis, Parainfluenza, Parvovirus)
--   Cats: FVRCP (Feline Viral Rhinotracheitis, Calicivirus, Panleukopenia), Rabies

CREATE TABLE `vaccinations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pet_id` BIGINT UNSIGNED NOT NULL,                 -- FK → pets.id
    `vet_id` BIGINT UNSIGNED NOT NULL,                 -- FK → vets.id
    `vaccine_name` VARCHAR(255) NOT NULL,              -- e.g., "Rabies", "DHPP"
    `batch_number` VARCHAR(100) NULL,                  -- Vaccine batch for traceability
    `date_administered` DATE NOT NULL,
    `next_due_date` DATE NULL,                         -- When the next dose is due
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `vaccinations_pet_id_index` (`pet_id`),
    INDEX `vaccinations_vet_id_index` (`vet_id`),
    INDEX `vaccinations_next_due_date_index` (`next_due_date`), -- For "overdue" queries

    CONSTRAINT `vaccinations_pet_id_foreign`
        FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `vaccinations_vet_id_foreign`
        FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 11. MEDICATIONS TABLE
-- ============================================================================
-- Medications prescribed to a pet by a vet.
-- Tracks dosage, duration, and whether the course is still active.
--
-- Relationships:
--   - BELONGS TO one pet (pet_id FK)
--   - BELONGS TO one vet (vet_id FK) — who prescribed it
--
-- Active check (application logic):
--   A medication is "active" if today's date is between start_date and end_date.

CREATE TABLE `medications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pet_id` BIGINT UNSIGNED NOT NULL,                 -- FK → pets.id
    `vet_id` BIGINT UNSIGNED NOT NULL,                 -- FK → vets.id
    `medicine_name` VARCHAR(255) NOT NULL,             -- e.g., "Amoxicillin"
    `dosage` VARCHAR(100) NOT NULL,                    -- e.g., "250mg twice daily"
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,                              -- NULL = ongoing medication
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    INDEX `medications_pet_id_index` (`pet_id`),
    INDEX `medications_vet_id_index` (`vet_id`),

    CONSTRAINT `medications_pet_id_foreign`
        FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
        ON DELETE CASCADE,

    CONSTRAINT `medications_vet_id_foreign`
        FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
