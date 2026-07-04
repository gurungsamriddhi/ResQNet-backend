# Adoption & Auth System — Error Fix Log

## 1. API Route references non-existent controller class

**File:** `routes/api.php:7`

**Error:** `Class "App\Http\Controllers\Api\AdoptionRequestController" not found`

**Cause:** The import and route bindings used `AdoptionRequestController::class`, but the file is named `AdoptionController.php` with class `AdoptionController` in the same namespace.

**Fix:** Changed import to `AdoptionController` and all three route references:
```
- use App\Http\Controllers\Api\AdoptionRequestController;
+ use App\Http\Controllers\Api\AdoptionController;

- [AdoptionRequestController::class, 'index']
+ [AdoptionController::class, 'index']
```

---

## 2. Duplicate vet routes in web.php

**File:** `routes/web.php:127-130`

**Error:** Duplicate route registrations — `Route::apiResource('vets', ...)` followed by `Route::resource('vets', ...)` on the same URI with the same controller. Both registered the same routes, causing conflicts.

**Cause:** Likely leftover from a refactor where one was meant to replace the other but both were kept.

**Fix:** Replaced both with a single `Route::resource('vets', OrgVetController::class)` which includes all resource routes (index, create, store, show, edit, update, destroy).

---

## 3. AdoptionRequest model fillable uses old column name

**File:** `app/Models/AdoptionRequest.php:25`

**Error:** `$fillable` contained `'admin_notes'` but migration `2026_06_15_135341_update_adoption_requests_table.php` renames the column to `organization_response`.

**Cause:** The model was not updated after the migration renamed the column.

**Fix:** Changed `'admin_notes'` → `'organization_response'` in `$fillable`. Also added the missing `reviewed_by`, `response_sent_at`, and `adopted_at` columns to fillable.

---

## 4. Org AdoptionController uses old column name in approve/reject

**File:** `app/Http/Controllers/Web/Organization/AdoptionController.php:55,91-94`

**Error:** `approve()` and `reject()` methods read from and write to `admin_notes`, but the column was renamed to `organization_response`.

**Cause:** Controllers not updated after column rename migration.

**Fix:** Changed all references:
- `$request->input('admin_notes')` → `$request->input('organization_response')`
- Validation rule `'admin_notes'` → `'organization_response'`

---

## 5. Missing `fcm_token` column

**File:** `app/Models/User.php:66-68`

**Error:** `routeNotificationForFcm()` returns `$this->fcm_token`, but:
- No migration adds `fcm_token` column to the users table
- `fcm_token` is not in `$fillable`

**Impact:** FCM push notifications silently fail — the channel has no device token to send to.

**Fix:** Added `'fcm_token'` to `$fillable`. A migration still needs to be created to add the actual database column.

---

## 6. Unused `AdoptionApprovedNotification` class

**File:** `app/Notifications/AdoptionApprovedNotification.php`

**Error:** This notification class exists but is never referenced by any controller. The `AdoptionStatusUpdatedNotification` is used instead for all status updates including approval.

**Impact:** Dead code — adds maintenance overhead with no benefit.

**Fix:** None needed — but should be removed if confirmed unused.

---

## 7. Orphan `updateStatus` method in Org AdoptionController

**File:** `app/Http/Controllers/Web/Organization/AdoptionController.php:145-178`

**Error:** The `updateStatus()` method is defined in the controller but no route is registered for it in `web.php`.

**Impact:** Dead code — the method can never be reached via a request.

**Fix:** Either register a route for it (e.g., `POST /adoptions/{adoption}/status`) or remove the method.
