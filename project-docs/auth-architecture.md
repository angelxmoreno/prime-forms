# Prime Forms Auth Architecture

## Status

Working architecture document for Phase 1. This document captures the current auth decisions and the main open items that must be resolved before implementation moves beyond scaffolding.

## Goals

- Appwrite is the only identity provider
- Prime Forms keeps a local CakePHP user record for app-specific data
- Authentication and authorization packages are reusable and publishable to Packagist
- The web app uses a CakePHP session after successful Appwrite authentication
- The architecture can later support stateless JSON API request validation

## Package Split

### `Appwrite`

Low-level integration package.

Responsibilities:
- Appwrite configuration
- SDK client factory
- Common service wrappers
- Error translation
- Health checks and connection diagnostics

This package should not own local user persistence, authorization rules, or CakePHP auth flow.

### `AppwriteUsers`

Local user projection package. Depends on `Appwrite`.

Responsibilities:
- Map authenticated Appwrite users into local CakePHP user records
- Own the local users table model and related persistence logic
- Define the synchronization rules between Appwrite user data and local app metadata

### `AppwriteAuthentication`

CakePHP authentication bridge package. Depends on `Appwrite` and `AppwriteUsers`.

Responsibilities:
- Wrap CakePHP Authentication integration
- Handle login, logout, and identity resolution for the web app
- Bridge Appwrite-authenticated users into the CakePHP request lifecycle

This package should remain orchestration-focused. It should not become the home for low-level Appwrite SDK logic or local user data rules.

### `UserAccess`

Reusable authorization package for app roles, permissions, assignments, and CakePHP Authorization integration.

Responsibilities:
- Own local role and permission modeling
- Integrate with CakePHP Authorization
- Support configurable policies for different apps
- Provide reusable primitives for scoped access such as form assignments

This package should not own Appwrite SDK concerns or external identity validation.

## Source Of Truth

### Appwrite-authoritative

- Appwrite user id
- Authentication state
- External identity providers and linked auth methods
- Identity-level attributes such as verified email

### Local DB-authoritative

- Roles
- Permissions
- Authorization assignments
- Project-specific metadata
- Form assignments
- App-specific timestamps and operational data

## Authorization Model

Prime Forms will use local authorization data, backed by the reusable `UserAccess` plugin.

Initial roles:
- `super_admin`
- `manager`
- `pending`

Appwrite authenticates the user, but the application or authorization plugin determines what the user can do locally.

Role and permission policy should be configurable so other apps can reuse the authorization package without inheriting Prime Forms-specific behavior.

## Admin Access Model

Prime Forms does not create authoritative identities locally, but it does own admin authorization locally.

Operational model:
- Appwrite is the source of truth for identity
- Prime Forms creates or updates the local user projection only after successful Appwrite authentication
- Prime Forms owns local roles, permissions, app metadata, and form assignments

Initial access rules:
- new users receive the `pending` local role
- `super_admin` grants full admin access
- `manager` grants admin access limited by local form assignments
- `pending` grants no admin panel access until promoted

In Phase 1, the implementation can stop at local role assignment, email-verification enforcement, and access control. A full admin-role management UI is deferred to Phase 2.

Future direction:
- Prime Forms can expose administrative tooling to manage local roles and assignments
- other apps can reuse the same authorization package with different role definitions and policies

## Local User Model

Each authenticated Appwrite user gets a local CakePHP user row on first successful login.

Required fields:
- `id`
- `appwrite_user_id`
- `email`
- `display_name`
- `last_login_at`
- `created`
- `modified`

Notes:
- No local password is stored
- `appwrite_user_id` must be unique
- The local record exists to support Prime Forms metadata and relations, not to replace Appwrite identity
- Role and permission data are stored locally through the authorization layer
- `display_name` is resolved from Appwrite `name`, then email local-part, then `User {short-id}`

## Login Flows

Phase 1 login flows:
- email/password
- Google

On successful login:
1. Authenticate with Appwrite
2. Create or update the local CakePHP user projection
3. Assign the default local role if the user has no local authorization record yet
4. Resolve local authorization state
5. Establish the CakePHP web session

Users with the default role should authenticate successfully but should not receive admin access until promoted.
Verified email is required for admin access, but not for local user projection.

## Session Strategy

For the server-rendered web app, CakePHP will keep its own session after Appwrite authentication succeeds.

Later, when the auth packages are used in a JSON API server, request authentication may need to be revalidated per request instead of depending on the web session model.

## Synchronization Strategy

Initial strategy:
- First-login projection creates the local user row automatically
- Subsequent logins update core synced fields and `last_login_at`
- First-login projection also ensures a default local authorization state exists
- Local roles and form assignments remain local and are not synchronized back to Appwrite

Future strategy:
- Add Appwrite webhook support for profile changes, role changes, or deprovisioning events

Webhooks are a future enhancement, not a Phase 1 prerequisite.

## Accepted Phase 1 Decisions

- The default local role is `pending`
- Verified email is required for admin access, but not for local projection
- Display name mapping is: Appwrite `name`, then email local-part, then `User {short-id}`
- Full admin-role management UI is deferred to Phase 2
- Phase 1 can use a manual or development-oriented promotion path for initial admins

## Deferred To Later Chunks

- Decide how Google login is exposed in the CakePHP UI and plugin API when Chunk 6 is implemented
- Decide package vendor names and repository split strategy before Packagist publication
