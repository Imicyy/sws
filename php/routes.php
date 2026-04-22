<?php
declare(strict_types=1);

/** @var Router $router */
/** @var Controller $controller */

$requireAuth = static function (): bool {
    if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => '403 Forbidden access']);
        return false;
    }
    return true;
};

$router->add('GET', '/pdao-admin-dashboard', static function () use ($controller) {
    $controller->renderPdaoAdminDashboard();
}, [$requireAuth]);

$router->add('GET', '/', static function () {
    View::render('auth/login', []);
});

$router->add('GET', '/register', static function () {
    View::render('auth/register', []);
});

$router->add('POST', '/create-user', static function () use ($controller) {
    $controller->createUser();
});
$router->add('POST', '/login', static function () use ($controller) {
    $controller->login();
});
$router->add('POST', '/verify-login-code', static function () use ($controller) {
    $controller->verifyLoginCode();
});
$router->add('GET', '/logout', static function () use ($controller) {
    $controller->logout();
});

$router->add('POST', '/send-sms', static function () use ($controller) {
    $controller->sendSms();
}, [$requireAuth]);
$router->add('GET', '/sms-history', static function () use ($controller) {
    $controller->getSmsHistory();
}, [$requireAuth]);
$router->add('PUT', '/update-sms-received', static function () use ($controller) {
    $controller->updateSmsReceived();
}, [$requireAuth]);

$router->add('GET', '/Index', static function () use ($controller) {
    $controller->renderAdminDashboard();
}, [$requireAuth]);
$router->add('GET', '/superadmin', static function () use ($controller) {
    $controller->renderSuperAdmin();
}, [$requireAuth]);
$router->add('GET', '/superadmin-logs', static function () use ($controller) {
    $controller->renderSuperAdminLogs();
}, [$requireAuth]);
$router->add('GET', '/Admin', static function () use ($controller) {
    $controller->renderSuperAdmin();
}, [$requireAuth]);
$router->add('GET', '/Map', static function () use ($controller) {
    $controller->renderPwdMapPage();
});
$router->add('GET', '/Maps', static function () use ($controller) {
    $controller->renderSeniorMapPage();
});
$router->add('GET', '/Analytics', static function () use ($controller) {
    $controller->renderAnalyticsPage();
}, [$requireAuth]);
$router->add('GET', '/User', static function () use ($controller) {
    $controller->renderUserManagementPage();
}, [$requireAuth]);

$router->add('GET', '/index-staff', static function () use ($controller) {
    $controller->renderStaffDashboard();
}, [$requireAuth]);

$router->add('GET', '/staff-dashboard', static function () use ($controller) {
    $controller->renderStaffDashboard();
}, [$requireAuth]);

$router->add('GET', '/osca-dashboard', static function () use ($controller) {
    $controller->renderOscaDashboard();
}, [$requireAuth]);

$router->add('GET', '/pdao-dashboard', static function () use ($controller) {
    $controller->renderPdaoDashboard();
}, [$requireAuth]);

$router->add('POST', '/register-pwd', static function () use ($controller) {
    $controller->registerPwd();
});
$router->add('POST', '/update-pwd', static function () use ($controller) {
    $controller->updatePwd();
}, [$requireAuth]);
$router->add('POST', '/archive-pwd', static function () use ($controller) {
    $controller->archivePwd();
}, [$requireAuth]);
$router->add('POST', '/unarchive-pwd', static function () use ($controller) {
    $controller->unarchivePwd();
}, [$requireAuth]);
$router->add('POST', '/update-senior', static function () use ($controller) {
    $controller->updateSenior();
}, [$requireAuth]);
$router->add('POST', '/archive-senior', static function () use ($controller) {
    $controller->archiveSenior();
}, [$requireAuth]);
$router->add('POST', '/unarchive-senior', static function () use ($controller) {
    $controller->unarchiveSenior();
}, [$requireAuth]);

$router->add('GET', '/add_senior', static function () use ($controller) {
    $controller->renderAddSenior();
});
$router->add('GET', '/add_pwd', static function () use ($controller) {
    $controller->renderAddPWD();
});

$router->add('GET', '/index-superadmin', static function () use ($controller) {
    $controller->renderSuperAdminIndex();
});
$router->add('GET', '/superadmin-users', static function () use ($controller) {
    $controller->renderSuperAdminUser();
});
$router->add('POST', '/update-user', static function () use ($controller) {
    $controller->updateUser();
}, [$requireAuth]);
$router->add('POST', '/edit-user', static function () use ($controller) {
    $controller->editUserStatus();
}, [$requireAuth]);

$router->add('GET', '/Senior-form', static function () use ($controller) {
    $controller->renderOscaDashboard();
}, [$requireAuth]);
$router->add('GET', '/Pwd-form', static function () use ($controller) {
    $controller->renderPdaoDashboard();
}, [$requireAuth]);
$router->add('GET', '/pwd/{id}/application-pdf', static function (array $params) use ($controller) {
    $_GET['id'] = $params['id'] ?? null;
    $controller->generatePwdApplicationPdf();
}, [$requireAuth]);
$router->add('GET', '/senior/{id}/application-pdf', static function (array $params) use ($controller) {
    $_GET['id'] = $params['id'] ?? null;
    $controller->generateSeniorApplicationPdf();
}, [$requireAuth]);
$router->add('GET', '/pdf-template/{type}', static function (array $params) use ($controller) {
    $_GET['type'] = $params['type'] ?? null;
    $controller->servePdfTemplate();
}, [$requireAuth]);

$router->add('GET', '/admin-alert', static function () use ($controller) {
    $controller->renderAdminAlert();
}, [$requireAuth]);
$router->add('POST', '/send-alert', static function () use ($controller) {
    $controller->sendAlert();
}, [$requireAuth]);

$router->add('POST', '/add-data', static function () use ($controller) {
    $controller->createResident();
});

$router->add('GET', '/api/analytics/osca', static function () use ($controller) {
    $controller->getOscaAnalytics();
}, [$requireAuth]);
$router->add('GET', '/api/analytics/pdao', static function () use ($controller) {
    $controller->getPdaoAnalytics();
}, [$requireAuth]);

$router->add('GET', '/get-pdao-analytics', static function () use ($controller) {
    $controller->getPdaoAnalytics();
}, [$requireAuth]);
$router->add('GET', '/api/pwds', static function () use ($controller) {
    $controller->getAllPwds();
}, [$requireAuth]);
$router->add('GET', '/api/senior-citizens-for-report', static function () use ($controller) {
    $controller->getSeniorCitizensForReport();
}, [$requireAuth]);
$router->add('GET', '/api/senior-edit-logs/{id}', static function (array $params) use ($controller) {
    $_GET['senior_id'] = (int) ($params['id'] ?? 0);
    $controller->getSeniorEditLogs();
}, [$requireAuth]);
$router->add('GET', '/api/pwd-edit-logs/{id}', static function (array $params) use ($controller) {
    $_GET['pwd_id'] = (int) ($params['id'] ?? 0);
    $controller->getPwdEditLogs();
}, [$requireAuth]);
$router->add('GET', '/api/senior-citizens/barangay/{barangay}', static function (array $params) use ($controller) {
    $_GET['barangay'] = urldecode((string) ($params['barangay'] ?? ''));
    $controller->getSeniorCitizensByBarangay();
}, [$requireAuth]);
$router->add('GET', '/api/senior-citizens/purok/{purok}', static function (array $params) use ($controller) {
    $_GET['purok'] = urldecode((string) ($params['purok'] ?? ''));
    $controller->getSeniorCitizensByPurok();
}, [$requireAuth]);
$router->add('GET', '/api/pwds/barangay/{barangay}', static function (array $params) use ($controller) {
    $_GET['barangay'] = urldecode((string) ($params['barangay'] ?? ''));
    $controller->getPwdsByBarangay();
}, [$requireAuth]);
$router->add('GET', '/api/pwds/purok/{purok}', static function (array $params) use ($controller) {
    $_GET['purok'] = urldecode((string) ($params['purok'] ?? ''));
    $controller->getPwdsByPurok();
}, [$requireAuth]);

$router->add('GET', '/api/barangays', static function () use ($controller) {
    $controller->getBarangays();
}, [$requireAuth]);
$router->add('POST', '/api/barangay', static function () use ($controller) {
    $controller->createBarangay();
}, [$requireAuth]);
$router->add('POST', '/api/purok', static function () use ($controller) {
    $controller->addPurok();
}, [$requireAuth]);

$router->add('GET', '/silay-boundary', static function () use ($controller) {
    $controller->getSilayBoundary();
});
$router->add('GET', '/senior-map-data', static function () use ($controller) {
    $controller->getSeniorMapData();
});
$router->add('GET', '/pwd-map-data', static function () use ($controller) {
    $controller->getPwdMapData();
});

$router->add('GET', '/barangay', static function () use ($controller) {
    $controller->renderBarangay();
}, [$requireAuth]);
$router->add('GET', '/barangay-senior-dashboard', static function () use ($controller) {
    $controller->renderBarangaySeniorDashboard();
}, [$requireAuth]);
$router->add('GET', '/barangay-senior', static function () use ($controller) {
    $controller->renderBarangaySenior();
}, [$requireAuth]);
$router->add('GET', '/barangay-pwd', static function () use ($controller) {
    $controller->renderBarangayPwd();
}, [$requireAuth]);
