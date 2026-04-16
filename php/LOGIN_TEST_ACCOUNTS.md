# Login Test Accounts

Use these accounts for quick login checks against the migrated PHP app.

## Seeded accounts from the SQL dump

The database seed stores these users in hashed form, so the plaintext password is not documented in the dump:

| Role | Email | Notes |
| --- | --- | --- |
| Staff | staff_001@gmail.com | OSCA staff seed |
| Admin | Admin_001@gmail.com | Admin seed |
| Super Admin | Superadmin_001@gmail.com | Super admin seed |
| Barangay | Alicante_001@gmail.com | Barangay account tied to barangay_id 2 |

## Known QA account with plaintext password

| Role | Email | Password |
| --- | --- | --- |
| Super Admin | qa.migration@example.com | QaPass123! |

## Notes

- The seeded users above are present in [model/ebmag2.5.sql](../model/ebmag2.5.sql) and [model/import.sql](../model/import.sql).
- If you want a seeded account reset to a known password for test runs, use the existing QA upsert helper as a template or add a separate reset script.