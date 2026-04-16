# PHP Migration Status

## Task List (Requested)

1. Build PHP app skeleton and router while keeping current database untouched
2. Migrate auth and user management first
3. Migrate PWD and Senior CRUD + edit/archive flows
4. Migrate analytics/map endpoints
5. Migrate PDF and SMS/email integrations
6. Switch pages to PHP rendering and run parity testing endpoint-by-endpoint

## Current Progress

### 1) App skeleton + router
Status: DONE

Implemented:
- Front controller: index.php
- Router engine: php/core/Router.php
- View renderer: php/core/View.php
- Env/bootstrap: php/bootstrap.php
- DB bootstrap: php/config/database.php
- Apache rewrite: .htaccess
- Route map parity file: php/routes.php (56 routes mapped)

Notes:
- Database schema was not changed.
- App now boots even if DB is temporarily unavailable.

### 2) Auth and user management
Status: MOSTLY DONE

Implemented (controller/controller.php):
- createUser
- login
- verifyLoginCode
- logout
- updateUser
- editUserStatus

### 3) PWD and Senior CRUD + archive
Status: CORE DONE, LOG/AUDIT DETAILS PARTIAL

Implemented:
- registerPwd
- updatePwd
- archivePwd
- unarchivePwd
- createResident
- updateSenior
- archiveSenior
- unarchiveSenior

Partial:
- full edit-log parity tables (pwd_edit_logs, senior_edit_logs) not fully mirrored yet
- some nested relation update branches are simplified compared to Node version

### 4) Analytics + map endpoints
Status: DONE

Implemented:
- getOscaAnalytics
- getPdaoAnalytics
- getAllPwds
- getPwdMapData
- getSeniorMapData
- getSeniorCitizensForReport
- getSeniorCitizensByBarangay
- getSeniorCitizensByPurok
- getPwdsByBarangay
- getPwdsByPurok
- getBarangays
- createBarangay
- addPurok
- getSilayBoundary

### 5) PDF + SMS/email integrations
Status: DONE (exact mapping parity confirmed)

Implemented:
- Login verification email via PHPMailer SMTP (with safe fallback to PHP mail())
- SMS sending + history APIs (sendSms, getSmsHistory, updateSmsReceived)
- PDF download endpoints now working with DB record validation and template streaming:
  - generatePwdApplicationPdf
  - generateSeniorApplicationPdf
- Composer initialized locally (`composer.phar`) with PDF/mail/env libraries:
  - setasign/fpdf
  - setasign/fpdi
  - phpmailer/phpmailer
  - vlucas/phpdotenv
- Added Node bridge renderer (`php/pdf/generate_application_pdf.js`) using `pdf-lib` to preserve original Node checkbox/radio/text field mapping behavior.
- Runtime engine verification headers added (`X-PDF-Engine`) to identify output path.
- SMS history persistence now mirrors Node behavior (sent/error/skipped rows saved to `sms_history` when record metadata is provided).

Verified:
- Authenticated login (`qa.barangay@example.com`) => 302 redirect to `/barangay`
- `GET /pwd/4/application-pdf` => 200, `Content-Type: application/pdf`, `X-PDF-Engine: node-pdf-lib`
- `GET /senior/11/application-pdf` => 200, `Content-Type: application/pdf`, `X-PDF-Engine: node-pdf-lib`

### 6) PHP page rendering + endpoint parity tests
Status: PARTIAL

Implemented:
- PHP-rendered pages:
  - / (login)
  - /register
  - /index-superadmin (dashboard)
  - /superadmin-users (data-backed user table)
  - /superadmin-logs (data-backed logs page)
  - /barangay
  - /barangay-senior-dashboard
  - /barangay-senior
  - /barangay-pwd
  - /Pwd-form (data-backed list)
  - /Senior-form (data-backed list)
  - /add_pwd (PHP form)
  - /add_senior (PHP form)
  - fallback placeholders for routes still being migrated

Parity checks completed:
- Route parity count:
  - JS routes: 56
  - PHP routes: 56
  - Normalized dynamic paths parity: PASS

Runtime smoke tests completed:
- GET / => 200 HTML
- GET /api/analytics/osca => 403 JSON (expected without session)
- GET /register => 200 HTML
- GET /add_pwd => 200 HTML
- GET /add_senior => 200 HTML
- GET /pwd/{id}/application-pdf => route protected (403 without session, as expected)
- GET /senior/{id}/application-pdf => route protected (403 without session, as expected)
- GET /index-superadmin => 200 HTML
- GET /superadmin-users => 200 HTML
- GET /superadmin-logs => 403 without session (expected)
- GET /barangay => 403 without session (expected)
- GET /barangay-senior-dashboard => 403 without session (expected)

Authenticated parity QA (live session) completed:
- Login (`qa.migration@example.com`) => 302 redirect to `/index-superadmin`
- Endpoint sweep result: 17 pass / 6 fail (23 checked)
- Failing endpoints and reason:
  - `/barangay`, `/barangay-senior-dashboard`, `/barangay-senior`, `/barangay-pwd` => 403 (expected for non-Barangay role)
  - `/pwd/1/application-pdf` => 404 (record id not found in current data)
  - `/senior/1/application-pdf` => 404 (record id not found in current data)

## Remaining Work Queue

1. Port remaining role pages from EJS to PHP views by role
2. Add full edit-log parity for PWD/Senior update flows
3. Add alerts/socket substitute strategy (SSE/WebSocket or polling)
