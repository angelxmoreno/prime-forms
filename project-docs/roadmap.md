# Prime Forms Roadmap

## Phase 1: Decisions And Platform Foundation

Phase 1 is intentionally decision-heavy, but only true implementation blockers should stop the next chunk. Non-blocking product or publication details should be deferred instead of holding up execution.

### Chunk 1: Architecture Decisions
- finalize auth architecture in `project-docs/auth-architecture.md`
- finalize the shared `forms` plus `submissions` storage model
- finalize the runtime registry model: schema markdown at build time, `forms` table at runtime
- finalize the reusable authorization package boundary and default-role behavior

Chunk 1 should only block execution on decisions that affect the next implementation chunk directly.

Status:
- complete enough to proceed to Chunk 2

### Chunk 2: Repository Baseline
- conventional commits
- code quality baseline for PHP tooling already present in the repo
- git hooks
- duplication detection

### Chunk 3: Local Environment And Storage Setup
- create local MySQL database
- proper `.env` and `app_local.php` setup
- configure DB-backed cache for local development if still needed
- configure DB-backed logging for local development if still needed

### Chunk 4: Shared Platform Schema
- migration for local `users` table
- migration for `forms` table
- migration for `submissions` table
- migration for form assignments
- migration or schema design for local roles and authorization assignments

### Chunk 5: Auth Package Contracts And Scaffolds
- `Appwrite` plugin scaffold
- `AppwriteUsers` plugin scaffold
- `AppwriteAuthentication` plugin scaffold
- `UserAccess` plugin scaffold
- document dependency boundaries between the four packages

### Chunk 6: Web Admin Auth Shell
- `/admin/login`
- CakePHP session established after Appwrite login
- first-login local user projection
- first-login default local role assignment to `pending`
- verified-email gate for admin access
- protected admin area with role-aware access rules
- temporary manual promotion path for initial admins

Decision needed when Google login is implemented:
- decide how Google login is exposed in the CakePHP UI and plugin API

### Phase 1 Exit Criteria
- auth package boundaries are documented
- local login works against Appwrite
- local user projection works on first login
- local authorization state is created on first login
- verified-email gate is enforced before admin access
- shared `forms` and `submissions` schema exists
- protected admin shell exists for later feature work

### Proposed Commit Sequence
1. docs: finalize auth architecture, admin access model, and form storage decisions
2. chore: establish repository baseline for commits, hooks, and code-quality tooling
3. chore: configure local environment, MySQL datasource, and local-only cache/logging decisions
4. feat: add shared platform schema migrations for users, forms, submissions, and assignments
5. feat: scaffold `Appwrite` plugin with configuration and client abstractions
6. feat: scaffold `AppwriteUsers` plugin with local user projection contract
7. feat: scaffold `AppwriteAuthentication` plugin with Cake authentication integration contracts
8. feat: scaffold `UserAccess` plugin with local role and policy contracts
9. feat: implement Appwrite-backed web login, first-login local user projection, default `pending` role assignment, and verified-email gate
10. feat: add protected admin shell with role-aware access gates and a temporary promotion path for initial admins

Each commit above should leave the repo in a stable state. If a decision is still unresolved, it should block only the first dependent implementation commit, not unrelated earlier chunks.

## Phase 2: Prime Forms Features

### Form Generation
- `BaseSubmissionForm`
- generated per-form subclasses
- generated public templates
- generated validation and theme metadata
- finalize the exact AI generator output contract for a standard form

### Public Form Runtime
- form registration flow
- public form rendering
- submission persistence into the shared `submissions` table
- success and thank-you handling
- finalize the exact runtime registration artifact, if one is needed beyond `forms` table registration

### Admin Features
- submissions index and detail view
- reviewed status and notes
- role-filtered form access

### CRM Features
- contacts CRUD
- linking contacts to submissions
- duplicate email handling rules

### Future Extensions
- optional normalization for specific forms
- webhook-driven Appwrite user sync
- API-server auth mode
- package vendor names and repository split strategy before Packagist publication
