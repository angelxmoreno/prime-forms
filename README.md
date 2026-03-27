# Prime Forms

Prime Forms is a CakePHP 5 form and survey platform. Forms are defined from markdown schemas and rendered through generated CakePHP form classes and templates. Submission data is stored in a shared `submissions` table with JSON payloads, while runtime form registration lives in the `forms` table.

The project is still in the platform-foundation stage. The current codebase is a freshly baked CakePHP app plus planning documents.

## Current Architecture Direction

- Appwrite is the only identity provider
- Local CakePHP users are projections of authenticated Appwrite users
- Authentication and authorization are split into reusable plugins
- Standard generated forms extend a shared `BaseSubmissionForm`
- Standard form submissions use shared storage instead of per-form tables

Planned local plugins:
- `Appwrite`
- `AppwriteUsers`
- `AppwriteAuthentication`
- `UserAccess`

## Documentation

- [project-docs/overview.md](project-docs/overview.md)
- [project-docs/auth-architecture.md](project-docs/auth-architecture.md)
- [project-docs/app-schema.md](project-docs/app-schema.md)
- [project-docs/roadmap.md](project-docs/roadmap.md)
- [AGENTS.md](AGENTS.md)

## Local Development

Install dependencies:

```bash
composer install
```

Run the local server:

```bash
bin/cake server -p 8765
```

Run tests:

```bash
composer test
```

Run coding standards:

```bash
composer cs-check
```

Auto-fix coding standards where possible:

```bash
composer cs-fix
```

## Status

Not implemented yet:
- Appwrite integration
- reusable auth/authz plugins
- shared platform schema
- generated form runtime
- admin panel and CRM features

The next work should follow the phased plan in `project-docs/roadmap.md`.
