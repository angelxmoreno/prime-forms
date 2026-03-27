# Prime Forms App Schema Notes

## Status

Working schema notes for Phase 1 planning. These are intended to guide migrations and plugin boundaries, not to act as final DDL.

## Design Principles

- Appwrite is authoritative for identity, not local authorization
- Prime Forms stores a local user projection for app metadata and access control
- `UserAccess` owns local roles and role assignment state
- Prime Forms uses shared `forms` and `submissions` tables instead of per-form submission tables
- Standard form payloads live in JSON until a specific form proves it needs normalization
- Phase 1 should prefer the smallest schema that supports the accepted auth decisions

## Phase 1 Recommendation Summary

- Keep one global role per user in Phase 1
- Use `pending` as the default role for first-login users
- Require verified email before admin access is granted
- Store manager-to-form access locally through a form assignment table
- Keep permission rules role-based in code for Phase 1; do not add dynamic permission tables yet
- Keep local `users.email` unique
- Keep `forms` metadata minimal in Phase 1 and defer theme/success runtime metadata until later

## Assumptions

- Database: MySQL 8
- Primary keys: `BIGINT UNSIGNED AUTO_INCREMENT`
- Foreign keys use matching unsigned bigint columns
- Timestamps follow CakePHP conventions: `created`, `modified`
- JSON columns use MySQL `JSON`

## Tables

### `users`

Owned conceptually by `AppwriteUsers`, but used by the app and `UserAccess`.

Recommended columns:

```sql
id
appwrite_user_id      VARCHAR(64) UNIQUE NOT NULL
email                 VARCHAR(255) UNIQUE NOT NULL
display_name          VARCHAR(255) NOT NULL
email_verified        TINYINT(1) NOT NULL DEFAULT 0
last_login_at         DATETIME NULL
created               DATETIME NOT NULL
modified              DATETIME NOT NULL
```

Notes:
- `appwrite_user_id` is the stable foreign identity key
- `email` is kept as part of the local user projection for admin UI, lookup, deduplication, and access-control decisions without requiring an Appwrite round-trip on every request
- `email_verified` is cached locally to support the admin-access gate
- `display_name` is derived from Appwrite `name`, then email local-part, then `User {short-id}`
- no local password column should exist
- Appwrite remains authoritative for the identity itself; local email is a synchronized copy, not the source of truth

Suggested indexes:
- unique: `appwrite_user_id`
- unique: `email`
- index: `last_login_at`

Decision:
- `email` remains unique locally

### `user_access_roles`

Owned by `UserAccess`.

Recommended columns:

```sql
id
slug                  VARCHAR(100) UNIQUE NOT NULL
name                  VARCHAR(150) NOT NULL
description           TEXT NULL
is_system             TINYINT(1) NOT NULL DEFAULT 1
created               DATETIME NOT NULL
modified              DATETIME NOT NULL
```

Seeded Phase 1 roles:
- `super_admin`
- `manager`
- `pending`

Notes:
- `slug` is the stable programmatic identifier
- `name` is the UI label
- `is_system` prevents casual deletion of core roles later

### `user_access_user_roles`

Owned by `UserAccess`.

Recommended columns:

```sql
id
user_id               BIGINT UNSIGNED NOT NULL
role_id               BIGINT UNSIGNED NOT NULL
assigned_by_user_id   BIGINT UNSIGNED NULL
created               DATETIME NOT NULL
modified              DATETIME NOT NULL
```

Suggested constraints:
- unique: `user_id`
- foreign key: `user_id -> users.id`
- foreign key: `role_id -> user_access_roles.id`
- foreign key: `assigned_by_user_id -> users.id`

Notes:
- Phase 1 intentionally supports one global role per user
- if multi-role support is needed later, the unique constraint on `user_id` can be relaxed
- `assigned_by_user_id` supports auditability for manual promotion

### `forms`

Owned by the application. This is the runtime registry for generated forms.

Recommended columns:

```sql
id
slug                  VARCHAR(191) UNIQUE NOT NULL
title                 VARCHAR(255) NOT NULL
description           TEXT NULL
form_class            VARCHAR(255) NOT NULL
schema_path           VARCHAR(255) NOT NULL
is_active             TINYINT(1) NOT NULL DEFAULT 1
created               DATETIME NOT NULL
modified              DATETIME NOT NULL
```

Suggested indexes:
- unique: `slug`
- unique: `form_class`
- index: `is_active`

Notes:
- schema markdown is the build-time source of truth
- this table is the runtime source of truth
- `schema_path` lets the app link a live form back to its schema file

Future direction:
- `forms` may later store runtime metadata such as theme configuration or success behavior
- do not add those fields in Phase 1

### `form_assignments`

Owned by the application, but consumed by `UserAccess` policies.

Recommended columns:

```sql
id
user_id               BIGINT UNSIGNED NOT NULL
form_id               BIGINT UNSIGNED NOT NULL
assigned_by_user_id   BIGINT UNSIGNED NULL
created               DATETIME NOT NULL
modified              DATETIME NOT NULL
```

Suggested constraints:
- unique: `(user_id, form_id)`
- foreign key: `user_id -> users.id`
- foreign key: `form_id -> forms.id`
- foreign key: `assigned_by_user_id -> users.id`

Notes:
- this is the scoped access table for manager users
- `super_admin` does not need explicit form assignments

### `submissions`

Owned by the application.

Recommended columns:

```sql
id
form_id               BIGINT UNSIGNED NOT NULL
payload               JSON NOT NULL
ip_address            VARCHAR(45) NULL
user_agent            TEXT NULL
referrer_url          TEXT NULL
accept_language       VARCHAR(255) NULL
source_url            TEXT NULL
submission_fingerprint VARCHAR(255) NULL
tracking_hash         VARCHAR(255) NULL
reviewed              TINYINT(1) NOT NULL DEFAULT 0
review_notes          TEXT NULL
created               DATETIME NOT NULL
modified              DATETIME NOT NULL
```

Suggested constraints and indexes:
- foreign key: `form_id -> forms.id`
- index: `form_id`
- index: `reviewed`
- composite index: `(form_id, created)`
- index: `submission_fingerprint`
- composite index: `(form_id, submission_fingerprint)`

Notes:
- `payload` stores the submitted form data as JSON
- `user_agent` should be captured from the request by default
- `referrer_url`, `accept_language`, and `source_url` should be captured in Phase 1
- no per-form entity or table is required for standard forms
- do not add `submitted_by_user_id` in Phase 1; if a form needs contact identity, collect it as part of the submitted payload and normalize later only if needed
- `submission_fingerprint` and `tracking_hash` can be used for multi-submission detection, but their generation rules should be documented carefully before implementation
- if a specific form later needs normalized storage, add it separately without changing the shared default

### Submission Context And Detection Fields

These fields are part of the planned schema direction:

```sql
referrer_url          TEXT NULL
accept_language       VARCHAR(255) NULL
source_url            TEXT NULL
submission_fingerprint VARCHAR(255) NULL
tracking_hash         VARCHAR(255) NULL
```

Notes:
- `referrer_url` can help explain where a submission originated, but it is often missing or unreliable
- `accept_language` can be useful for analytics or support context
- `source_url` is only useful if the app may host or embed the same form in multiple places
- `submission_fingerprint` should be treated as a derived detection value, not as a user-facing identifier
- `tracking_hash` can support duplicate or abuse detection when generated consistently
- any device-fingerprint strategy should be documented with privacy and retention rules before implementation

## Deferred For Phase 2

These are valid future additions, but they should not block the initial schema:

- `contacts`
- `contact_submissions`
- full dynamic permission tables such as `user_access_permissions` and `user_access_role_permissions`
- audit/event tables for role changes and submission review history
- normalized tables for special-case forms

## Why No Permission Tables Yet

The current Phase 1 role model is simple:
- `super_admin`
- `manager`
- `pending`

The permission matrix is small and stable enough to keep in code initially. Adding dynamic permission tables in Phase 1 would increase complexity before there is a real need for runtime permission editing.

If the package later needs configurable permissions across many apps, add:
- `user_access_permissions`
- `user_access_role_permissions`

That should be a deliberate Phase 2 or package-evolution step, not a default Phase 1 requirement.

## Migration Order Suggestion

1. `users`
2. `user_access_roles`
3. `user_access_user_roles`
4. `forms`
5. `form_assignments`
6. `submissions`

## Open Schema Questions

- How should `submission_fingerprint` and `tracking_hash` be generated, rotated, and retained?
