-- ============================================================================
-- ResQNet Database Schema (Synced with Laravel Migrations)
-- Animal Rescue, Adoption, Donation & Health Management Platform
-- Last updated: 2026-05-19
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
--   rescue_reports (0..1) ──── (0..1) pets     A rescued animal can be linked to a pet record
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
--   Three roles: 'mobile_user', 'organization', 'super_admin'
--   No role enum on the users table — Spatie handles this via pivot tables.
--
-- SOFT DELETES:
--   Most tables use soft deletes (deleted_at column). Records are never
--   permanently removed — they are marked as deleted for audit purposes.
--
-- FK STRATEGY:
--   Financial/historical records use SET NULL on FK delete to preserve data.
--   Child records (images, medical) use CASCADE — deleted with parent.
--
-- ============================================================================




-- ============================================================================
-- 1. USERS TABLE (modify existing Laravel default)
-- ============================================================================
-- Extended with phone, avatar, address, activity tracking, and soft deletes.


ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(20) NULL UNIQUE AFTER `email`;
ALTER TABLE `users` ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `phone`;
ALTER TABLE `users` ADD COLUMN `address` TEXT NULL AFTER `avatar`;
ALTER TABLE `users` ADD COLUMN `is_active` BOOLEAN NOT NULL DEFAULT TRUE AFTER `address`;
ALTER TABLE `users` ADD COLUMN `phone_verified_at` TIMESTAMP NULL AFTER `email_verified_at`;
ALTER TABLE `users` ADD COLUMN `last_login_at` TIMESTAMP NULL AFTER `phone_verified_at`;
ALTER TABLE `users` ADD COLUMN `deleted_at` TIMESTAMP NULL;
ALTER TABLE `users` ADD INDEX `users_is_active_index` (`is_active`);




-- ============================================================================
-- 2. ORGANIZATIONS TABLE
-- ============================================================================
-- Each organization is managed by exactly ONE user (the org_admin).
-- status enum replaces simple boolean — supports pending/active/suspended/rejected workflow.


CREATE TABLE `organizations` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `user_id` BIGINT UNSIGNED NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `slug` VARCHAR(255) NOT NULL,
   `description` TEXT NULL,
   `logo` VARCHAR(255) NULL,
   `phone` VARCHAR(20) NULL,
   `email` VARCHAR(255) NULL,
   `address` VARCHAR(255) NULL,
   `registration_number` VARCHAR(255) NULL,         -- Nepal NGO registration
   `latitude` DECIMAL(10, 8) NULL,
   `longitude` DECIMAL(11, 8) NULL,
   `is_verified` BOOLEAN NOT NULL DEFAULT FALSE,
   `verified_at` TIMESTAMP NULL,
   `status` ENUM('pending', 'active', 'suspended', 'rejected') NOT NULL DEFAULT 'pending',
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   UNIQUE KEY `organizations_slug_unique` (`slug`),
   UNIQUE KEY `organizations_user_id_unique` (`user_id`),
   INDEX `organizations_status_index` (`status`),
   INDEX `organizations_is_verified_index` (`is_verified`),


   CONSTRAINT `organizations_user_id_foreign`
       FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 3. PETS TABLE
-- ============================================================================
-- organization_id is nullable with SET NULL — preserves pet records if org is force-deleted.
-- Gender now includes 'unknown'. Size includes 'xl'. Status includes 'inactive'.


CREATE TABLE `pets` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `organization_id` BIGINT UNSIGNED NULL,
   `qr_code_id` VARCHAR(20) NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `species` ENUM('dog', 'cat', 'bird', 'rabbit', 'other') NOT NULL DEFAULT 'dog',
   `breed` VARCHAR(100) NULL,
   `age_months` INT UNSIGNED NULL,
   `gender` ENUM('male', 'female', 'unknown') NOT NULL DEFAULT 'unknown',
   `size` ENUM('small', 'medium', 'large', 'xl') NOT NULL DEFAULT 'medium',
   `color` VARCHAR(100) NULL,
   `description` TEXT NULL,
   `health_status` TEXT NULL,
   `is_vaccinated` BOOLEAN NOT NULL DEFAULT FALSE,
   `is_neutered` BOOLEAN NOT NULL DEFAULT FALSE,
   `status` ENUM('available', 'adopted', 'unavailable', 'inactive') NOT NULL DEFAULT 'available',
   `adopted_at` TIMESTAMP NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   UNIQUE KEY `pets_qr_code_id_unique` (`qr_code_id`),
   INDEX `pets_species_index` (`species`),
   INDEX `pets_status_index` (`status`),


   CONSTRAINT `pets_organization_id_foreign`
       FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 4. PET_IMAGES TABLE
-- ============================================================================
-- No soft deletes — images are hard-deleted with file cleanup.


CREATE TABLE `pet_images` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `pet_id` BIGINT UNSIGNED NOT NULL,
   `image_path` VARCHAR(255) NOT NULL,
   `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,
   `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),


   CONSTRAINT `pet_images_pet_id_foreign`
       FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 5. ADOPTION_REQUESTS TABLE
-- ============================================================================
-- user_id is nullable with SET NULL — preserves adoption history if user leaves.
-- Composite index on (pet_id, status) for "pending requests for this pet" queries.


CREATE TABLE `adoption_requests` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `pet_id` BIGINT UNSIGNED NOT NULL,
   `user_id` BIGINT UNSIGNED NULL,
   `message` TEXT NULL,
   `living_situation` VARCHAR(255) NULL,
   `has_other_pets` BOOLEAN NULL,
   `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
   `admin_notes` TEXT NULL,
   `reviewed_at` TIMESTAMP NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `adoption_requests_pet_status_index` (`pet_id`, `status`),


   CONSTRAINT `adoption_requests_pet_id_foreign`
       FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
       ON DELETE CASCADE,


   CONSTRAINT `adoption_requests_user_id_foreign`
       FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 6. RESCUE_REPORTS TABLE
-- ============================================================================
-- pet_id added — links rescued animal to a pet record after intake.
-- title added for quick identification. lat/long now nullable.
-- All FKs use SET NULL to preserve report history.


CREATE TABLE `rescue_reports` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `user_id` BIGINT UNSIGNED NULL,
   `organization_id` BIGINT UNSIGNED NULL,
   `pet_id` BIGINT UNSIGNED NULL,
   `title` VARCHAR(255) NULL,
   `description` TEXT NOT NULL,
   `image_path` VARCHAR(255) NULL,
   `latitude` DECIMAL(10, 8) NULL,
   `longitude` DECIMAL(11, 8) NULL,
   `address` VARCHAR(255) NULL,
   `urgency` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
   `status` ENUM('reported', 'assigned', 'in_progress', 'resolved') NOT NULL DEFAULT 'reported',
   `resolved_at` TIMESTAMP NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `rescue_reports_status_index` (`status`),
   INDEX `rescue_reports_urgency_index` (`urgency`),


   CONSTRAINT `rescue_reports_user_id_foreign`
       FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
       ON DELETE SET NULL,


   CONSTRAINT `rescue_reports_organization_id_foreign`
       FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
       ON DELETE SET NULL,


   CONSTRAINT `rescue_reports_pet_id_foreign`
       FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 7. DONATIONS TABLE
-- ============================================================================
-- Both FKs nullable with SET NULL — financial records must NEVER be deleted.
-- payment_payload stores raw Khalti/eSewa JSON for audit trails.


CREATE TABLE `donations` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `user_id` BIGINT UNSIGNED NULL,
   `organization_id` BIGINT UNSIGNED NULL,
   `amount` DECIMAL(10, 2) NOT NULL,
   `payment_method` ENUM('khalti', 'esewa') NOT NULL,
   `transaction_id` VARCHAR(255) NULL,
   `status` ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
   `payment_payload` JSON NULL,
   `remarks` TEXT NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `donations_status_index` (`status`),


   CONSTRAINT `donations_user_id_foreign`
       FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
       ON DELETE SET NULL,


   CONSTRAINT `donations_organization_id_foreign`
       FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 8. VETS TABLE
-- ============================================================================
-- Added is_active flag and soft deletes.


CREATE TABLE `vets` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `organization_id` BIGINT UNSIGNED NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `phone` VARCHAR(20) NULL,
   `email` VARCHAR(255) NULL,
   `specialization` VARCHAR(255) NULL,
   `license_number` VARCHAR(100) NULL,
   `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `vets_is_active_index` (`is_active`),


   CONSTRAINT `vets_organization_id_foreign`
       FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 9. MEDICAL_RECORDS TABLE
-- ============================================================================
-- vet_id nullable with SET NULL — medical records preserved even if vet removed.
-- Added 'other' to type enum, notes column, and next_visit_date.


CREATE TABLE `medical_records` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `pet_id` BIGINT UNSIGNED NOT NULL,
   `vet_id` BIGINT UNSIGNED NULL,
   `type` ENUM('checkup', 'treatment', 'surgery', 'injury', 'observation', 'other') NOT NULL DEFAULT 'checkup',
   `title` VARCHAR(255) NOT NULL,
   `description` TEXT NULL,
   `notes` TEXT NULL,
   `record_date` DATE NOT NULL,
   `next_visit_date` DATE NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `medical_records_record_date_index` (`record_date`),
   INDEX `medical_records_type_index` (`type`),


   CONSTRAINT `medical_records_pet_id_foreign`
       FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
       ON DELETE CASCADE,


   CONSTRAINT `medical_records_vet_id_foreign`
       FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 10. VACCINATIONS TABLE
-- ============================================================================
-- vet_id nullable with SET NULL. Added date_administered index.


CREATE TABLE `vaccinations` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `pet_id` BIGINT UNSIGNED NOT NULL,
   `vet_id` BIGINT UNSIGNED NULL,
   `vaccine_name` VARCHAR(255) NOT NULL,
   `batch_number` VARCHAR(100) NULL,
   `date_administered` DATE NOT NULL,
   `next_due_date` DATE NULL,
   `notes` TEXT NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `vaccinations_date_administered_index` (`date_administered`),
   INDEX `vaccinations_next_due_date_index` (`next_due_date`),


   CONSTRAINT `vaccinations_pet_id_foreign`
       FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
       ON DELETE CASCADE,


   CONSTRAINT `vaccinations_vet_id_foreign`
       FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 11. MEDICATIONS TABLE
-- ============================================================================
-- vet_id nullable with SET NULL. Added frequency column (separate from dosage).


CREATE TABLE `medications` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `pet_id` BIGINT UNSIGNED NOT NULL,
   `vet_id` BIGINT UNSIGNED NULL,
   `medicine_name` VARCHAR(255) NOT NULL,
   `dosage` VARCHAR(255) NOT NULL,
   `frequency` VARCHAR(255) NULL,
   `start_date` DATE NOT NULL,
   `end_date` DATE NULL,
   `notes` TEXT NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,
   `deleted_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   INDEX `medications_start_date_index` (`start_date`),
   INDEX `medications_end_date_index` (`end_date`),


   CONSTRAINT `medications_pet_id_foreign`
       FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`)
       ON DELETE CASCADE,


   CONSTRAINT `medications_vet_id_foreign`
       FOREIGN KEY (`vet_id`) REFERENCES `vets` (`id`)
       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- ============================================================================
-- 12. MEDIA TABLE (Spatie MediaLibrary)
-- ============================================================================
-- Used by Spatie MediaLibrary for polymorphic file uploads.
-- Currently used for organization registration document uploads.


CREATE TABLE `media` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `model_type` VARCHAR(255) NOT NULL,
   `model_id` BIGINT UNSIGNED NOT NULL,
   `uuid` CHAR(36) NULL,
   `collection_name` VARCHAR(255) NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `file_name` VARCHAR(255) NOT NULL,
   `mime_type` VARCHAR(255) NULL,
   `disk` VARCHAR(255) NOT NULL,
   `conversions_disk` VARCHAR(255) NULL,
   `size` BIGINT UNSIGNED NOT NULL,
   `manipulations` JSON NOT NULL,
   `custom_properties` JSON NOT NULL,
   `generated_conversions` JSON NOT NULL,
   `responsive_images` JSON NOT NULL,
   `order_column` INT UNSIGNED NULL,
   `created_at` TIMESTAMP NULL,
   `updated_at` TIMESTAMP NULL,


   PRIMARY KEY (`id`),
   UNIQUE KEY `media_uuid_unique` (`uuid`),
   INDEX `media_model_type_model_id_index` (`model_type`, `model_id`),
   INDEX `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



