<?php

declare(strict_types=1);

/**
 * PHP port of controller/controller.js.
 *
 * This class keeps method names close to the original Node controller so existing
 * route intent is easy to map while migrating from Express to PHP.
 */
class Controller
{
    private ?PDO $db;
    private string $projectRoot;
    private string $allBarangaysGeojsonPath;

    /** @var array<int, array{name:string,lat:float,lon:float}>|null */
    private ?array $barangayCentroidsCache = null;

    public function __construct(?PDO $db, string $projectRoot)
    {
        $this->db = $db;
        $this->projectRoot = $projectRoot;
        $this->allBarangaysGeojsonPath = $projectRoot . DIRECTORY_SEPARATOR
            . 'files' . DIRECTORY_SEPARATOR
            . 'assets' . DIRECTORY_SEPARATOR
            . 'data' . DIRECTORY_SEPARATOR
            . 'all_barangays.geojson';

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private function jsonResponse(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    private function input(): array
    {
        if (!empty($_POST)) {
            return $_POST;
        }

        $raw = file_get_contents('php://input');
        if ($raw === false || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function queryAll(string $sql, array $params = []): array
    {
        if ($this->db === null) {
            throw new RuntimeException('Database connection is not available.');
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function queryOne(string $sql, array $params = []): ?array
    {
        if ($this->db === null) {
            throw new RuntimeException('Database connection is not available.');
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    private function execute(string $sql, array $params = []): int
    {
        if ($this->db === null) {
            throw new RuntimeException('Database connection is not available.');
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    private function render(string $view, array $data = []): void
    {
        if (!class_exists('View')) {
            http_response_code(500);
            echo 'View renderer not loaded';
            exit;
        }

        View::render($view, $data);
    }

    private function parseDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $ts = strtotime($value);
        if ($ts === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $ts);
    }

    private function normalizeEnumValue(mixed $value, array $allowed): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        return in_array($text, $allowed, true) ? $text : null;
    }

    private function fetchBarangays(): array
    {
        $rows = $this->queryAll(
            "SELECT b.id, b.barangay, GROUP_CONCAT(p.purok ORDER BY p.purok) AS puroks
             FROM barangays b
             LEFT JOIN puroks p ON b.id = p.barangay_id
             GROUP BY b.id, b.barangay
             ORDER BY b.barangay"
        );

        $barangays = [];
        foreach ($rows as $row) {
            $barangays[(string) $row['barangay']] = isset($row['puroks']) && $row['puroks'] !== null
                ? explode(',', (string) $row['puroks'])
                : [];
        }

        return $barangays;
    }

    private function getPwdByIdWithRelations(int $id): ?array
    {
        $pwd = $this->queryOne('SELECT * FROM pwd WHERE id = ? LIMIT 1', [$id]);
        if ($pwd === null) {
            return null;
        }

        $contacts = $this->queryAll('SELECT type, name, relationship, phone, email FROM pwd_contacts WHERE pwd_id = ?', [$id]);
        $disabilityRows = $this->queryAll('SELECT disability FROM pwd_disabilities WHERE pwd_id = ?', [$id]);
        $causeRows = $this->queryAll('SELECT cause FROM pwd_disability_causes WHERE pwd_id = ?', [$id]);

        $disabilities = array_values(array_filter(array_map(static fn(array $r): ?string => $r['disability'] ?? null, $disabilityRows)));
        $causes = array_values(array_filter(array_map(static fn(array $r): ?string => $r['cause'] ?? null, $causeRows)));

        $pwd['contacts'] = $contacts;
        $pwd['disability'] = $disabilities;
        $pwd['cause_disability'] = $causes;
        $pwd['_id'] = (int) $pwd['id'];
        return $pwd;
    }

    private function getSeniorByIdWithRelations(int $id): ?array
    {
        $row = $this->queryOne('SELECT * FROM senior_citizens WHERE id = ? LIMIT 1', [$id]);
        if ($row === null) {
            return null;
        }

        $children = $this->queryAll('SELECT full_name, occupation, income, age, working_status FROM senior_children WHERE senior_id = ?', [$id]);
        $educationRows = $this->queryAll('SELECT educational_attainment FROM senior_education WHERE senior_id = ?', [$id]);
        $skillRows = $this->queryAll('SELECT skill FROM senior_skills WHERE senior_id = ?', [$id]);
        $serviceRows = $this->queryAll('SELECT service FROM senior_community_services WHERE senior_id = ?', [$id]);
        $contactRows = $this->queryAll('SELECT type, name, relationship, phone, email FROM senior_contacts WHERE senior_id = ?', [$id]);

        $skillValues = array_values(array_filter(array_map(static fn(array $r): ?string => $r['skill'] ?? null, $skillRows)));
        $skillOtherText = null;
        $normalizedSkills = [];
        foreach ($skillValues as $skillValue) {
            $skillText = trim((string) $skillValue);
            if (stripos($skillText, 'Other:') === 0) {
                $candidate = trim(substr($skillText, 6));
                if ($candidate !== '') {
                    $skillOtherText = $candidate;
                }
                if (!in_array('Other', $normalizedSkills, true)) {
                    $normalizedSkills[] = 'Other';
                }
                continue;
            }
            $normalizedSkills[] = $skillText;
        }

        return [
            '_id' => (int) $row['id'],
            'reference_code' => $row['reference_code'] ?? null,
            'identifying_information' => [
                'name' => [
                    'last_name' => $row['last_name'] ?? null,
                    'first_name' => $row['first_name'] ?? null,
                    'middle_name' => $row['middle_name'] ?? null,
                    'extension' => $row['extension'] ?? null,
                ],
                'address' => [
                    'barangay' => $row['barangay'] ?? null,
                    'purok' => $row['purok'] ?? null,
                ],
                'date_of_birth' => $row['date_of_birth'] ?? null,
                'age' => isset($row['age']) ? (int) $row['age'] : null,
                'marital_status' => $row['marital_status'] ?? null,
                'gender' => $row['gender'] ?? null,
                'place_of_birth' => !empty($row['place_of_birth']) ? [(string) $row['place_of_birth']] : [],
                'contacts' => $contactRows,
                'osca_id_number' => $row['osca_id_number'] ?? null,
                'gsis_sss' => $row['gsis_sss'] ?? null,
                'philhealth' => $row['philhealth'] ?? null,
                'sc_association_org_id_no' => $row['sc_association_org_id_no'] ?? null,
                'tin' => $row['tin'] ?? null,
                'other_govt_id' => $row['other_govt_id'] ?? null,
                'service_business_employment' => $row['service_business_employment'] ?? null,
                'current_pension' => $row['current_pension'] ?? null,
                'capability_to_travel' => $row['capability_to_travel'] ?? null,
            ],
            'family_composition' => [
                'spouse' => ['name' => $row['spouse_name'] ?? null],
                'father' => [
                    'last_name' => $row['father_last_name'] ?? null,
                    'first_name' => $row['father_first_name'] ?? null,
                    'middle_name' => $row['father_middle_name'] ?? null,
                    'extension' => $row['father_extension'] ?? null,
                ],
                'mother' => [
                    'last_name' => $row['mother_last_name'] ?? null,
                    'first_name' => $row['mother_first_name'] ?? null,
                    'middle_name' => $row['mother_middle_name'] ?? null,
                ],
                'children' => $children,
            ],
            'education_hr_profile' => [
                'educational_attainment' => array_values(array_filter(array_map(static fn(array $r): ?string => $r['educational_attainment'] ?? null, $educationRows))),
                'skills' => array_values(array_filter($normalizedSkills)),
                'skill_other_text' => $skillOtherText,
            ],
            'community_service' => array_values(array_filter(array_map(static fn(array $r): ?string => $r['service'] ?? null, $serviceRows))),
            'community_service_other_text' => $row['community_service_other_text'] ?? null,
            'status' => $row['status'] ?? null,
            'archive_reason' => $row['archive_reason'] ?? null,
            'created_at' => $row['created_at'] ?? null,
        ];
    }

    private function normalizeLogValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_array($value) || is_object($value)) {
            $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            return $json === false ? null : $json;
        }

        $text = trim((string) $value);
        return $text === '' ? null : $text;
    }

    private function valuesDiffer(mixed $oldValue, mixed $newValue): bool
    {
        return $this->normalizeLogValue($oldValue) !== $this->normalizeLogValue($newValue);
    }

    private function canonicalizeSeniorContacts(array $contacts): array
    {
        $normalized = [];
        foreach ($contacts as $contact) {
            if (!is_array($contact)) {
                continue;
            }

            $name = trim((string) ($contact['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $normalized[] = [
                'type' => trim((string) ($contact['type'] ?? 'primary')),
                'name' => $name,
                'relationship' => trim((string) ($contact['relationship'] ?? '')),
                'phone' => trim((string) ($contact['phone'] ?? '')),
                'email' => trim((string) ($contact['email'] ?? '')),
            ];
        }

        usort($normalized, static function (array $a, array $b): int {
            return strcmp(json_encode($a, JSON_UNESCAPED_UNICODE) ?: '', json_encode($b, JSON_UNESCAPED_UNICODE) ?: '');
        });

        return $normalized;
    }

    private function canonicalizeSeniorChildren(array $children): array
    {
        $normalized = [];
        foreach ($children as $child) {
            if (!is_array($child)) {
                continue;
            }

            $fullName = trim((string) ($child['full_name'] ?? ''));
            if ($fullName === '') {
                continue;
            }

            $normalized[] = [
                'full_name' => $fullName,
                'occupation' => trim((string) ($child['occupation'] ?? '')),
                'income' => trim((string) ($child['income'] ?? '')),
                'age' => trim((string) ($child['age'] ?? '')),
                'working_status' => trim((string) ($child['working_status'] ?? '')),
            ];
        }

        usort($normalized, static function (array $a, array $b): int {
            return strcmp(json_encode($a, JSON_UNESCAPED_UNICODE) ?: '', json_encode($b, JSON_UNESCAPED_UNICODE) ?: '');
        });

        return $normalized;
    }

    private function canonicalizeStringList(array $items): array
    {
        $normalized = [];
        foreach ($items as $item) {
            $value = trim((string) $item);
            if ($value !== '') {
                $normalized[] = $value;
            }
        }

        sort($normalized);
        return array_values(array_unique($normalized));
    }

    private function addSeniorEditLog(int $seniorId, string $field, mixed $oldValue, mixed $newValue, string $editedBy, string $editedAt): void
    {
        if (!$this->valuesDiffer($oldValue, $newValue)) {
            return;
        }

        try {
            $this->execute(
                'INSERT INTO senior_edit_logs (senior_id, field, old_value, new_value, edited_by, edited_at) VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $seniorId,
                    $field,
                    $this->normalizeLogValue($oldValue),
                    $this->normalizeLogValue($newValue),
                    $editedBy,
                    $editedAt,
                ]
            );
        } catch (Throwable $e) {
            error_log('[SeniorEditLog] Failed to write log: ' . $e->getMessage());
        }
    }

    private function canonicalizePwdContacts(array $contacts): array
    {
        $normalized = [];
        foreach ($contacts as $contact) {
            if (!is_array($contact)) {
                continue;
            }

            $name = trim((string) ($contact['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $normalized[] = [
                'type' => trim((string) ($contact['type'] ?? 'primary')),
                'name' => $name,
                'relationship' => trim((string) ($contact['relationship'] ?? '')),
                'phone' => trim((string) ($contact['phone'] ?? '')),
                'email' => trim((string) ($contact['email'] ?? '')),
            ];
        }

        usort($normalized, static function (array $a, array $b): int {
            return strcmp(json_encode($a, JSON_UNESCAPED_UNICODE) ?: '', json_encode($b, JSON_UNESCAPED_UNICODE) ?: '');
        });

        return $normalized;
    }

    private function addPwdEditLog(int $pwdId, string $field, mixed $oldValue, mixed $newValue, string $editedBy, string $editedAt): void
    {
        if (!$this->valuesDiffer($oldValue, $newValue)) {
            return;
        }

        try {
            $this->execute(
                'INSERT INTO pwd_edit_logs (pwd_id, field, old_value, new_value, edited_by, edited_at) VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $pwdId,
                    $field,
                    $this->normalizeLogValue($oldValue),
                    $this->normalizeLogValue($newValue),
                    $editedBy,
                    $editedAt,
                ]
            );
        } catch (Throwable $e) {
            error_log('[PwdEditLog] Failed to write log: ' . $e->getMessage());
        }
    }

    private function outputPdfFromTemplateWithText(string $templatePath, string $filename, callable $drawPage1): void
    {
        if (!class_exists('setasign\\Fpdi\\Fpdi')) {
            throw new RuntimeException('FPDI is not available and Node PDF engine failed.');
        }

        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($templatePath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                if ($pageNo === 1) {
                    $pdf->SetFont('Helvetica', '', 8);
                    $pdf->SetTextColor(0, 0, 0);
                    $drawPage1($pdf);
                }
            }

            $bytes = $pdf->Output('S');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . rawurlencode($filename) . '"');
            header('Content-Length: ' . (string) strlen($bytes));
            echo $bytes;
            exit;
        } catch (Throwable $e) {
            throw new RuntimeException('FPDI fallback failed: ' . $e->getMessage(), 0, $e);
        }
    }

    private function generatePdfViaNode(string $type, array $record, string $templatePath): ?string
    {
        $scriptPath = $this->projectRoot . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'generate_application_pdf.js';
        if (!file_exists($scriptPath)) {
            return null;
        }

        $payload = json_encode([
            'record' => $record,
            'templatePath' => $templatePath,
        ], JSON_UNESCAPED_UNICODE);
        if ($payload === false) {
            return null;
        }

        $nodeCandidates = [
            getenv('NODE_BINARY') ?: null,
            'node',
            'nodejs',
            'C:\\Program Files\\nodejs\\node.exe',
            'C:\\Program Files (x86)\\nodejs\\node.exe',
        ];
        $errors = [];

        foreach ($nodeCandidates as $nodeBinaryRaw) {
            if (!is_string($nodeBinaryRaw) || trim($nodeBinaryRaw) === '') {
                continue;
            }
            $nodeBinary = trim($nodeBinaryRaw);

            $cmd = escapeshellarg($nodeBinary) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($type);
            $descriptors = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = @proc_open($cmd, $descriptors, $pipes, $this->projectRoot);
            if (is_resource($process)) {
                fwrite($pipes[0], $payload);
                fclose($pipes[0]);

                $pdfBytes = stream_get_contents($pipes[1]);
                fclose($pipes[1]);

                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[2]);

                $exitCode = proc_close($process);
                if ($exitCode === 0 && is_string($pdfBytes) && $pdfBytes !== '') {
                    return $pdfBytes;
                }
                if (!empty($stderr)) {
                    $errors[] = '[' . $nodeBinary . ' proc_open] ' . trim($stderr);
                }
            } else {
                $errors[] = '[' . $nodeBinary . ' proc_open] unable to start process';
            }

            // Fallback when proc_open/pipe execution is restricted.
            $tmpBase = tempnam(sys_get_temp_dir(), 'pdfbridge_');
            if ($tmpBase !== false) {
                $inputPath = $tmpBase . '.json';
                $outputPath = $tmpBase . '.pdf';
                @unlink($tmpBase);
                @file_put_contents($inputPath, $payload);

                $fileCmd = escapeshellarg($nodeBinary)
                    . ' ' . escapeshellarg($scriptPath)
                    . ' ' . escapeshellarg($type)
                    . ' --input ' . escapeshellarg($inputPath)
                    . ' --output ' . escapeshellarg($outputPath);
                $fileOut = [];
                $fileCode = 1;
                @exec($fileCmd . ' 2>&1', $fileOut, $fileCode);
                if ($fileCode === 0 && file_exists($outputPath)) {
                    $bytes = @file_get_contents($outputPath);
                    @unlink($inputPath);
                    @unlink($outputPath);
                    if (is_string($bytes) && $bytes !== '') {
                        return $bytes;
                    }
                }
                @unlink($inputPath);
                @unlink($outputPath);
                if (!empty($fileOut)) {
                    $errors[] = '[' . $nodeBinary . ' file-mode] ' . trim(implode("\n", $fileOut));
                } else {
                    $errors[] = '[' . $nodeBinary . ' file-mode] failed with exit code ' . $fileCode;
                }
            }
        }

        if (!empty($errors)) {
            error_log('[PDF bridge] Node generator failed: ' . implode(' || ', $errors));
        } else {
            error_log('[PDF bridge] Node generator failed: no node candidates available.');
        }
        return null;
    }

    private function getRedirectPathByRole(array $user): string
    {
        $role = $user['role'] ?? '';
        $staffClassification = $user['staff_classification'] ?? null;


        if ($role === 'Admin') {
            if ($staffClassification === 'OSCA') {
                return '/Analytics';
            }
            if ($staffClassification === 'PDAO') {
                return '/pdao-admin-dashboard';
            }
            return '/Index';
        }

        if ($role === 'Staff') {
            if ($staffClassification === 'OSCA') {
                return '/osca-dashboard';
            }
            return '/pdao-dashboard';
        }

        if ($role === 'Super Admin') {
            return '/index-superadmin';
        }

        if ($role === 'Barangay') {
            return '/barangay';
        }

        return '/index';
    }

    public function renderPdaoAdminDashboard(): void
    {
        try {
            $sql = "SELECT barangay AS name, COUNT(*) AS pdaoCount FROM pwd WHERE status <> 'Archived' GROUP BY barangay ORDER BY barangay ASC";
            $rows = $this->queryAll($sql);
            $barangayData = [];
            $totalPwds = 0;
            foreach ($rows as $row) {
                $barangayData[] = [
                    'name' => $row['name'],
                    'pwdCount' => (int) $row['pdaoCount'],
                ];
                $totalPwds += (int) $row['pdaoCount'];
            }

            $this->render('admin/pdao_dashboard', [
                'title' => 'PDAO Admin Dashboard',
                'user' => $_SESSION['user'] ?? null,
                'barangayData' => $barangayData,
                'totalPwds' => $totalPwds,
            ]);
        } catch (Throwable $e) {
            $this->render('admin/pdao_dashboard', [
                'title' => 'PDAO Admin Dashboard',
                'user' => $_SESSION['user'] ?? null,
                'barangayData' => [],
                'totalPwds' => 0,
            ]);
        }
    }

    private function generateVerificationCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function sendLoginVerificationEmail(string $toEmail, string $code): bool
    {
        $subject = 'Your login verification code';
        $textBody = "Your verification code is {$code}. This code will expire in 10 minutes.";
        $htmlBody = '<p>Your verification code is <strong>' . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . '</strong>.</p>'
            . '<p>This code will expire in 10 minutes.</p>';

        $smtpUser = getenv('SMTP_USER') ?: '';
        $smtpPass = getenv('SMTP_PASS') ?: '';
        $smtpHost = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $smtpPort = (int) (getenv('SMTP_PORT') ?: 587);
        $smtpSecure = strtolower((string) (getenv('SMTP_SECURE') ?: 'false')) === 'true';
        $from = getenv('SMTP_FROM') ?: ($smtpUser !== '' ? $smtpUser : 'no-reply@example.com');

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer') && $smtpUser !== '' && $smtpPass !== '') {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $smtpHost;
                $mail->SMTPAuth = true;
                $mail->Username = $smtpUser;
                $mail->Password = $smtpPass;
                $mail->Port = $smtpPort;
                if ($smtpSecure) {
                    $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                } else {
                    $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                }

                $mail->setFrom($from);
                $mail->addAddress($toEmail);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;
                $mail->AltBody = $textBody;
                $mail->send();
                return true;
            } catch (Throwable $e) {
                // Fall back to mail() below.
            }
        }

        $headers = [];
        $headers[] = 'From: ' . $from;
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        return mail($toEmail, $subject, $textBody, implode("\r\n", $headers));
    }

    private function computePolygonCentroid(array $feature): ?array
    {
        $geometry = $feature['geometry'] ?? null;
        if (!is_array($geometry)) {
            return null;
        }

        $type = $geometry['type'] ?? '';
        $ring = null;

        if ($type === 'Polygon') {
            $ring = $geometry['coordinates'][0] ?? null;
        } elseif ($type === 'MultiPolygon') {
            $ring = $geometry['coordinates'][0][0] ?? null;
        }

        if (!is_array($ring) || count($ring) === 0) {
            return null;
        }

        $sumLon = 0.0;
        $sumLat = 0.0;
        $count = 0;

        foreach ($ring as $point) {
            if (!is_array($point) || count($point) < 2) {
                continue;
            }

            $lon = filter_var($point[0], FILTER_VALIDATE_FLOAT);
            $lat = filter_var($point[1], FILTER_VALIDATE_FLOAT);
            if ($lon === false || $lat === false) {
                continue;
            }

            $sumLon += (float) $lon;
            $sumLat += (float) $lat;
            $count++;
        }

        if ($count === 0) {
            return null;
        }

        return [
            'lon' => $sumLon / $count,
            'lat' => $sumLat / $count,
        ];
    }

    private function getBarangayCentroids(): array
    {
        if ($this->barangayCentroidsCache !== null) {
            return $this->barangayCentroidsCache;
        }

        if (!file_exists($this->allBarangaysGeojsonPath)) {
            return [];
        }

        $raw = file_get_contents($this->allBarangaysGeojsonPath);
        if ($raw === false) {
            return [];
        }

        $geojson = json_decode($raw, true);
        if (!is_array($geojson)) {
            return [];
        }

        $list = [];
        foreach (($geojson['features'] ?? []) as $feature) {
            if (!is_array($feature)) {
                continue;
            }

            $name = $feature['properties']['ADM4_EN'] ?? null;
            if (!is_string($name) || trim($name) === '') {
                continue;
            }

            $centroid = $this->computePolygonCentroid($feature);
            if ($centroid === null) {
                continue;
            }

            $list[] = [
                'name' => $name,
                'lat' => $centroid['lat'],
                'lon' => $centroid['lon'],
            ];
        }

        $this->barangayCentroidsCache = $list;
        return $list;
    }

    private function fetchBarangayScopeForSessionUser(array $sessionUser): ?array
    {
        if (($sessionUser['role'] ?? '') !== 'Barangay') {
            return null;
        }

        $barangayId = $sessionUser['barangay_id'] ?? null;
        if ($barangayId === null || $barangayId === '') {
            return null;
        }

        return $this->queryOne('SELECT id, barangay FROM barangays WHERE id = ? LIMIT 1', [(int) $barangayId]);
    }

    public function createUser(): void
    {
        try {
            $body = $this->input();

            $name = trim((string) ($body['name'] ?? ''));
            $email = trim((string) ($body['email'] ?? ''));
            $password = (string) ($body['password'] ?? '');
            $confirmPassword = (string) ($body['confirm_password'] ?? '');
            $role = (string) ($body['role'] ?? '');
            $barangayId = $body['barangay_id'] ?? null;
            $staffClassification = $body['staff_classification'] ?? null;

            if ($name === '' || $email === '' || $password === '' || $confirmPassword === '' || $role === 'user') {
                $this->jsonResponse(['success' => false, 'error' => 'All fields are required'], 400);
            }

            if ($password !== $confirmPassword) {
                $this->jsonResponse(['success' => false, 'error' => 'Passwords do not match'], 400);
            }

            $staffClassificationVal = null;
            if ($role === 'Staff' || $role === 'Admin') {
                $normalized = strtoupper(trim((string) $staffClassification));
                if ($normalized !== 'PDAO' && $normalized !== 'OSCA') {
                    $this->jsonResponse([
                        'success' => false,
                        'error' => 'Invalid staff classification. Must be PDAO or OSCA.',
                    ], 400);
                }
                $staffClassificationVal = $normalized;
            }

            $barangayIdVal = null;
            if ($role === 'Barangay') {
                if ($barangayId === null || trim((string) $barangayId) === '') {
                    $this->jsonResponse([
                        'success' => false,
                        'error' => 'Please select a barangay for Barangay accounts.',
                    ], 400);
                }

                $parsedId = (int) $barangayId;
                $barangay = $this->queryOne('SELECT id FROM barangays WHERE id = ? LIMIT 1', [$parsedId]);
                if ($barangay === null) {
                    $this->jsonResponse(['success' => false, 'error' => 'Invalid barangay selection.'], 400);
                }
                $barangayIdVal = $parsedId;
            }

            $existing = $this->queryOne('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
            if ($existing !== null) {
                $this->jsonResponse(['success' => false, 'error' => 'Email already exists'], 400);
            }

            $hashed = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->db->prepare(
                "INSERT INTO users (name, email, password, role, status, barangay_id, staff_classification) VALUES (?, ?, ?, ?, 'Active', ?, ?)"
            );
            $stmt->execute([$name, $email, $hashed, $role, $barangayIdVal, $staffClassificationVal]);
            $id = (int) $this->db->lastInsertId();

            $this->jsonResponse([
                'success' => true,
                'message' => 'User created successfully',
                'user' => [
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                    'status' => 'Active',
                    'barangay_id' => $barangayIdVal,
                    'staff_classification' => $staffClassificationVal,
                ],
            ], 201);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function login(): void
    {
        try {
            $body = $this->input();
            $email = trim((string) ($body['email'] ?? ''));
            $password = (string) ($body['password'] ?? '');

            if ($email === '' || $password === '') {
                $this->jsonResponse(['success' => false, 'error' => 'All fields are required'], 400);
            }

            $user = $this->queryOne(
                'SELECT id, name, email, password, role, status, barangay_id, staff_classification, is_verified FROM users WHERE email = ? LIMIT 1',
                [$email]
            );

            if ($user === null) {
                $this->jsonResponse(['success' => false, 'error' => 'Invalid credentials'], 401);
            }

            if (($user['status'] ?? '') !== 'Active') {
                $this->execute('INSERT INTO login_logs (user_id, status) VALUES (?, ?)', [(int) $user['id'], 'failed']);
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Account is not active. Please contact the administrator.',
                ], 403);
            }

            if (!password_verify($password, (string) $user['password'])) {
                $this->execute('INSERT INTO login_logs (user_id, status) VALUES (?, ?)', [(int) $user['id'], 'failed']);
                $this->jsonResponse(['success' => false, 'error' => 'Invalid credentials'], 401);
            }

            $code = $this->generateVerificationCode();
            $_SESSION['pendingVerification'] = [
                'userId' => (int) $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'barangay_id' => $user['barangay_id'] !== null ? (int) $user['barangay_id'] : null,
                'staff_classification' => $user['staff_classification'] ?? null,
                'code' => $code,
                'expiresAt' => time() + (10 * 60),
            ];

            if (!$this->sendLoginVerificationEmail((string) $user['email'], $code)) {
                unset($_SESSION['pendingVerification']);
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Unable to send verification email. Please try again.',
                ], 500);
            }

            $this->jsonResponse([
                'success' => true,
                'verificationRequired' => true,
                'message' => 'A verification code has been sent to your email.',
            ]);

            $this->execute('INSERT INTO login_logs (user_id, status) VALUES (?, ?)', [(int) $user['id'], 'success']);

            $_SESSION['user'] = [
                '_id' => (int) $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'barangay_id' => $user['barangay_id'] !== null ? (int) $user['barangay_id'] : null,
                'staff_classification' => $user['staff_classification'] ?? null,
            ];

            $this->redirect($this->getRedirectPathByRole($user));
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function verifyLoginCode(): void
    {
        try {
            $body = $this->input();
            $code = trim((string) ($body['code'] ?? ''));
            $pending = $_SESSION['pendingVerification'] ?? null;

            if (!is_array($pending)) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'No pending verification found. Please log in again.',
                ], 400);
            }

            if ($code === '') {
                $this->jsonResponse(['success' => false, 'error' => 'Verification code is required.'], 400);
            }

            if (time() > (int) ($pending['expiresAt'] ?? 0)) {
                unset($_SESSION['pendingVerification']);
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Verification code has expired. Please log in again.',
                ], 400);
            }

            if ($code !== (string) ($pending['code'] ?? '')) {
                $this->jsonResponse(['success' => false, 'error' => 'Invalid verification code.'], 401);
            }

            $this->execute('UPDATE users SET is_verified = 1 WHERE id = ?', [(int) $pending['userId']]);
            $this->execute('INSERT INTO login_logs (user_id, status) VALUES (?, ?)', [(int) $pending['userId'], 'success']);

            $_SESSION['user'] = [
                '_id' => (int) $pending['userId'],
                'email' => $pending['email'],
                'role' => $pending['role'],
                'barangay_id' => $pending['barangay_id'] ?? null,
                'staff_classification' => $pending['staff_classification'] ?? null,
            ];

            unset($_SESSION['pendingVerification']);

            $this->jsonResponse([
                'success' => true,
                'redirectUrl' => $this->getRedirectPathByRole($_SESSION['user']),
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        $this->redirect('/');
    }

    public function updateUser(): void
    {
        try {
            $body = $this->input();

            $id = isset($body['id']) ? (int) $body['id'] : 0;
            if ($id <= 0) {
                $this->jsonResponse(['message' => 'User id is required'], 400);
            }

            $fields = [];
            $params = [];

            if (!empty($body['name'])) {
                $fields[] = 'name = ?';
                $params[] = trim((string) $body['name']);
            }
            if (!empty($body['email'])) {
                $fields[] = 'email = ?';
                $params[] = trim((string) $body['email']);
            }
            if (!empty($body['role'])) {
                $role = trim((string) $body['role']);
                $fields[] = 'role = ?';
                $params[] = $role;
                $fields[] = 'barangay_id = ?';
                $params[] = ($role === 'Barangay' && !empty($body['barangay_id'])) ? (int) $body['barangay_id'] : null;
            }
            if (!empty($body['status'])) {
                $fields[] = 'status = ?';
                $params[] = trim((string) $body['status']);
            }

            $password = trim((string) ($body['password'] ?? ''));
            $confirm = trim((string) ($body['confirm_password'] ?? ''));
            if ($password !== '' || $confirm !== '') {
                if ($password === '' || $confirm === '') {
                    $this->jsonResponse(['message' => 'Both password and confirm_password are required'], 400);
                }
                if (strlen($password) < 6) {
                    $this->jsonResponse(['message' => 'Password must be at least 6 characters'], 400);
                }
                if ($password !== $confirm) {
                    $this->jsonResponse(['message' => 'Passwords do not match'], 400);
                }
                $fields[] = 'password = ?';
                $params[] = password_hash($password, PASSWORD_BCRYPT);
            }

            if (count($fields) === 0) {
                $this->redirect('/superadmin-users');
            }

            $params[] = $id;
            $changed = $this->execute('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?', $params);
            if ($changed === 0) {
                $this->jsonResponse(['message' => 'User not found'], 404);
            }

            $this->redirect('/superadmin-users');
        } catch (Throwable $e) {
            $this->jsonResponse(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function editUserStatus(): void
    {
        try {
            $body = $this->input();
            $id = isset($body['id']) ? (int) $body['id'] : 0;
            if ($id <= 0) {
                $this->jsonResponse(['message' => 'User id is required'], 400);
            }

            $row = $this->queryOne('SELECT status FROM users WHERE id = ? LIMIT 1', [$id]);
            if ($row === null) {
                $this->jsonResponse(['message' => 'User not found'], 404);
            }

            $next = (($row['status'] ?? '') === 'Inactive') ? 'Active' : 'Inactive';
            $this->execute('UPDATE users SET status = ? WHERE id = ?', [$next, $id]);
            $this->redirect('/superadmin-users');
        } catch (Throwable $e) {
            $this->jsonResponse(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function registerPwd(): void
    {
        try {
            $body = $this->input();
            $birthday = $this->parseDate((string) ($body['birthday'] ?? ''));
            if ($birthday === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Birthday is invalid'], 400);
            }

            $firstName = trim((string) ($body['first_name'] ?? ''));
            $lastName = trim((string) ($body['last_name'] ?? ''));
            if ($firstName === '' || $lastName === '') {
                $this->jsonResponse(['success' => false, 'message' => 'First name and last name are required'], 400);
            }

            $exists = $this->queryOne(
                'SELECT id FROM pwd WHERE first_name = ? AND last_name = ? AND DATE(birthday) = DATE(?) LIMIT 1',
                [$firstName, $lastName, $birthday]
            );
            if ($exists !== null) {
                $this->jsonResponse([
                    'success' => false,
                    'isDuplicate' => true,
                    'message' => 'Duplicate PWD record found',
                ], 400);
            }

            $employmentStatus = $this->normalizeEnumValue(
                $body['employment_status'] ?? null,
                ['Employee', 'Unemployed', 'Self-employed']
            );

            if ($employmentStatus === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Employment status is invalid'], 400);
            }

            $employmentCategory = $this->normalizeEnumValue(
                $body['employment_category'] ?? null,
                ['Government', 'Private']
            );
            $employmentType = $this->normalizeEnumValue(
                $body['employment_type'] ?? null,
                ['Permanent/Regular', 'Seasonal', 'Casual', 'Emergency']
            );

            if ($employmentStatus !== 'Employee') {
                $employmentCategory = null;
                $employmentType = null;
            }

            $stmt = $this->db->prepare(
                'INSERT INTO pwd (
                    first_name, middle_name, last_name,
                    barangay, purok,
                    birthday, age, gender,
                    place_of_birth, civil_status, spouse_name,
                    fatherLastName, fatherFirstName, fatherMiddleName, fatherExtension,
                    motherLastName, motherFirstName, motherMiddleName,
                    sss_id, gsis_sss_no, psn_no, philhealth_no,
                    education_level, employment_status, employment_category, employment_type,
                    disability_other_text, cause_other_text, status, archive_reason
                 ) VALUES (
                    ?, ?, ?,
                    ?, ?,
                    ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, "Active", NULL
                 )'
            );

            $stmt->execute([
                $firstName,
                $body['middle_name'] ?? null,
                $lastName,
                $body['barangay'] ?? null,
                $body['purok'] ?? null,
                $birthday,
                isset($body['age']) ? (int) $body['age'] : null,
                $body['gender'] ?? null,
                $body['place_of_birth'] ?? null,
                $body['civil_status'] ?? null,
                $body['spouse_name'] ?? null,
                $body['fatherLastName'] ?? null,
                $body['fatherFirstName'] ?? null,
                $body['fatherMiddleName'] ?? null,
                $body['fatherExtension'] ?? null,
                $body['motherLastName'] ?? null,
                $body['motherFirstName'] ?? null,
                $body['motherMiddleName'] ?? null,
                $body['sss_id'] ?? null,
                $body['gsis_sss_no'] ?? null,
                $body['psn_no'] ?? null,
                $body['philhealth_no'] ?? null,
                $body['education_level'] ?? null,
                $employmentStatus,
                $employmentCategory,
                $employmentType,
                $body['disability_other_text'] ?? null,
                $body['cause_other_text'] ?? null,
            ]);

            $pwdId = (int) $this->db->lastInsertId();

            $contacts = isset($body['contacts']) && is_array($body['contacts']) ? $body['contacts'] : [];
            foreach ($contacts as $c) {
                if (!is_array($c) || empty($c['name'])) {
                    continue;
                }
                $this->execute(
                    'INSERT INTO pwd_contacts (pwd_id, type, name, relationship, phone, email) VALUES (?, ?, ?, ?, ?, ?)',
                    [$pwdId, $c['type'] ?? null, $c['name'], $c['relationship'] ?? null, $c['phone'] ?? null, $c['email'] ?? null]
                );
            }

            $disabilities = isset($body['disability']) && is_array($body['disability']) ? $body['disability'] : [];
            foreach ($disabilities as $d) {
                if (!is_string($d) || trim($d) === '') {
                    continue;
                }
                $this->execute('INSERT INTO pwd_disabilities (pwd_id, disability) VALUES (?, ?)', [$pwdId, $d]);
            }

            $causes = isset($body['cause_disability']) && is_array($body['cause_disability']) ? $body['cause_disability'] : [];
            foreach ($causes as $c) {
                if (!is_string($c) || trim($c) === '') {
                    continue;
                }
                $this->execute('INSERT INTO pwd_disability_causes (pwd_id, cause) VALUES (?, ?)', [$pwdId, $c]);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'PWD registration successful',
                'data' => $this->getPwdByIdWithRelations($pwdId),
            ], 201);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePwd(): void
    {
        try {
            $body = $this->input();
            $id = isset($body['pwd_id']) ? (int) $body['pwd_id'] : 0;
            if ($id <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'PWD ID is required'], 400);
            }

            $before = $this->getPwdByIdWithRelations($id);
            if ($before === null) {
                $this->jsonResponse(['success' => false, 'message' => 'PWD record not found'], 404);
            }

            $mapping = [
                'first_name','middle_name','last_name','barangay','purok','birthday','age','gender','place_of_birth','civil_status',
                'spouse_name','fatherLastName','fatherFirstName','fatherMiddleName','fatherExtension','motherLastName','motherFirstName',
                'motherMiddleName','sss_id','gsis_sss_no','psn_no','philhealth_no','education_level','employment_status','employment_category',
                'employment_type','disability_other_text','cause_other_text','status','archive_reason'
            ];

            $resolvedEmploymentStatus = array_key_exists('employment_status', $body)
                ? $this->normalizeEnumValue($body['employment_status'], ['Employee', 'Unemployed', 'Self-employed'])
                : ($before['employment_status'] ?? null);
            if (array_key_exists('employment_status', $body) && $resolvedEmploymentStatus === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Employment status is invalid'], 400);
            }

            $resolvedEmploymentCategory = array_key_exists('employment_category', $body)
                ? $this->normalizeEnumValue($body['employment_category'], ['Government', 'Private'])
                : ($before['employment_category'] ?? null);
            $resolvedEmploymentType = array_key_exists('employment_type', $body)
                ? $this->normalizeEnumValue($body['employment_type'], ['Permanent/Regular', 'Seasonal', 'Casual', 'Emergency'])
                : ($before['employment_type'] ?? null);

            if ($resolvedEmploymentStatus !== 'Employee') {
                $resolvedEmploymentCategory = null;
                $resolvedEmploymentType = null;
            }

            $editor = (string) ($_SESSION['user']['email'] ?? ($body['edited_by'] ?? 'Unknown'));
            $editedAt = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            $fields = [];
            $params = [];
            foreach ($mapping as $field) {
                if (array_key_exists($field, $body)) {
                    $fields[] = $field . ' = ?';
                    if ($field === 'birthday') {
                        $params[] = $this->parseDate((string) $body[$field]);
                    } elseif ($field === 'age') {
                        $params[] = ($body[$field] === '' || $body[$field] === null) ? null : (int) $body[$field];
                    } elseif ($field === 'employment_status') {
                        $params[] = $resolvedEmploymentStatus;
                    } elseif ($field === 'employment_category') {
                        $params[] = $resolvedEmploymentCategory;
                    } elseif ($field === 'employment_type') {
                        $params[] = $resolvedEmploymentType;
                    } else {
                        $value = $body[$field];
                        $params[] = ($value === '' || $value === null) ? null : $value;
                    }
                }
            }

            if (count($fields) > 0) {
                $params[] = $id;
                $this->execute('UPDATE pwd SET ' . implode(', ', $fields) . ' WHERE id = ?', $params);
            }

            if (isset($body['contacts']) && is_array($body['contacts'])) {
                $this->execute('DELETE FROM pwd_contacts WHERE pwd_id = ?', [$id]);
                foreach ($body['contacts'] as $c) {
                    if (!is_array($c) || empty($c['name'])) {
                        continue;
                    }
                    $this->execute(
                        'INSERT INTO pwd_contacts (pwd_id, type, name, relationship, phone, email) VALUES (?, ?, ?, ?, ?, ?)',
                        [$id, $c['type'] ?? null, $c['name'], $c['relationship'] ?? null, $c['phone'] ?? null, $c['email'] ?? null]
                    );
                }
            }

            if (isset($body['disability']) && is_array($body['disability'])) {
                $this->execute('DELETE FROM pwd_disabilities WHERE pwd_id = ?', [$id]);
                foreach ($body['disability'] as $d) {
                    if (!is_string($d) || trim($d) === '') {
                        continue;
                    }
                    $this->execute('INSERT INTO pwd_disabilities (pwd_id, disability) VALUES (?, ?)', [$id, $d]);
                }
            }

            if (isset($body['cause_disability']) && is_array($body['cause_disability'])) {
                $this->execute('DELETE FROM pwd_disability_causes WHERE pwd_id = ?', [$id]);
                foreach ($body['cause_disability'] as $c) {
                    if (!is_string($c) || trim($c) === '') {
                        continue;
                    }
                    $this->execute('INSERT INTO pwd_disability_causes (pwd_id, cause) VALUES (?, ?)', [$id, $c]);
                }
            }

            $after = $this->getPwdByIdWithRelations($id);

            $scalarFieldsForLogs = [
                'first_name','middle_name','last_name','barangay','purok','birthday','age','gender','place_of_birth','civil_status',
                'spouse_name','fatherLastName','fatherFirstName','fatherMiddleName','fatherExtension','motherLastName','motherFirstName',
                'motherMiddleName','sss_id','gsis_sss_no','psn_no','philhealth_no','education_level','employment_status','employment_category',
                'employment_type','disability_other_text','cause_other_text','status','archive_reason'
            ];

            foreach ($scalarFieldsForLogs as $field) {
                $this->addPwdEditLog(
                    $id,
                    $field,
                    $before[$field] ?? null,
                    $after[$field] ?? ($before[$field] ?? null),
                    $editor,
                    $editedAt
                );
            }

            $this->addPwdEditLog(
                $id,
                'contacts',
                $this->canonicalizePwdContacts(is_array($before['contacts'] ?? null) ? $before['contacts'] : []),
                $this->canonicalizePwdContacts(is_array($after['contacts'] ?? null) ? $after['contacts'] : []),
                $editor,
                $editedAt
            );
            $this->addPwdEditLog(
                $id,
                'disability',
                $this->canonicalizeStringList(is_array($before['disability'] ?? null) ? $before['disability'] : []),
                $this->canonicalizeStringList(is_array($after['disability'] ?? null) ? $after['disability'] : []),
                $editor,
                $editedAt
            );
            $this->addPwdEditLog(
                $id,
                'cause_disability',
                $this->canonicalizeStringList(is_array($before['cause_disability'] ?? null) ? $before['cause_disability'] : []),
                $this->canonicalizeStringList(is_array($after['cause_disability'] ?? null) ? $after['cause_disability'] : []),
                $editor,
                $editedAt
            );

            $this->jsonResponse([
                'success' => true,
                'message' => 'PWD record updated successfully',
                'data' => $after,
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function archivePwd(): void
    {
        $body = $this->input();
        $id = isset($body['pwd_id']) ? (int) $body['pwd_id'] : 0;
        if ($id <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'PWD ID is required'], 400);
        }

        $changed = $this->execute('UPDATE pwd SET status = "Archived", archive_reason = ? WHERE id = ?', [$body['reason'] ?? null, $id]);
        if ($changed === 0) {
            $this->jsonResponse(['success' => false, 'message' => 'PWD record not found'], 404);
        }

        $this->jsonResponse(['success' => true, 'message' => 'PWD record archived successfully', 'data' => $this->getPwdByIdWithRelations($id)]);
    }

    public function unarchivePwd(): void
    {
        $body = $this->input();
        $id = isset($body['pwd_id']) ? (int) $body['pwd_id'] : 0;
        if ($id <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'PWD ID is required'], 400);
        }

        $changed = $this->execute('UPDATE pwd SET status = "Active", archive_reason = NULL WHERE id = ?', [$id]);
        if ($changed === 0) {
            $this->jsonResponse(['success' => false, 'message' => 'PWD record not found'], 404);
        }

        $this->jsonResponse(['success' => true, 'message' => 'PWD record unarchived successfully', 'data' => $this->getPwdByIdWithRelations($id)]);
    }

    public function createResident(): void
    {
        try {
            $body = $this->input();
            $firstName = trim((string) ($body['first_name'] ?? $body['identifying_information']['name']['first_name'] ?? ''));
            $lastName = trim((string) ($body['last_name'] ?? $body['identifying_information']['name']['last_name'] ?? ''));
            $dobRaw = (string) ($body['birthday'] ?? $body['date_of_birth'] ?? $body['identifying_information']['date_of_birth'] ?? '');
            $dob = $this->parseDate($dobRaw);

            if ($firstName === '' || $lastName === '' || $dob === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Name and date of birth are required'], 400);
            }

            $exists = $this->queryOne(
                'SELECT id FROM senior_citizens WHERE first_name = ? AND last_name = ? AND DATE(date_of_birth) = DATE(?) AND status = "Active" LIMIT 1',
                [$firstName, $lastName, $dob]
            );
            if ($exists !== null) {
                $this->jsonResponse(['success' => false, 'isDuplicate' => true, 'message' => 'Duplicate senior citizen record found'], 400);
            }

            $stmt = $this->db->prepare(
                'INSERT INTO senior_citizens (
                    reference_code,last_name,first_name,middle_name,extension,barangay,purok,date_of_birth,age,place_of_birth,
                    marital_status,gender,osca_id_number,gsis_sss,philhealth,sc_association_org_id_no,tin,other_govt_id,
                    service_business_employment,current_pension,capability_to_travel,spouse_name,
                    father_last_name,father_first_name,father_middle_name,father_extension,
                    mother_last_name,mother_first_name,mother_middle_name,community_service_other_text,status,archive_reason,edited_by,edited_at
                 ) VALUES (
                    NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NULL,?,?,?,?,?,?,?,?,?,?,?,?,"Active",NULL,NULL,NULL
                 )'
            );

            $stmt->execute([
                $lastName,
                $firstName,
                $body['middle_name'] ?? $body['identifying_information']['name']['middle_name'] ?? null,
                $body['extension'] ?? $body['identifying_information']['name']['extension'] ?? null,
                $body['barangay'] ?? $body['identifying_information']['address']['barangay'] ?? null,
                $body['purok'] ?? $body['identifying_information']['address']['purok'] ?? null,
                $dob,
                isset($body['age']) ? (int) $body['age'] : (isset($body['identifying_information']['age']) ? (int) $body['identifying_information']['age'] : null),
                $body['place_of_birth'] ?? $body['identifying_information']['place_of_birth'] ?? null,
                $body['marital_status'] ?? $body['civil_status'] ?? $body['identifying_information']['marital_status'] ?? null,
                $body['gender'] ?? $body['identifying_information']['gender'] ?? null,
                $body['osca_id'] ?? $body['identifying_information']['osca_id_number'] ?? null,
                $body['gsis_sss'] ?? $body['identifying_information']['gsis_sss'] ?? null,
                $body['philhealth'] ?? $body['identifying_information']['philhealth'] ?? null,
                $body['sc_association_id'] ?? $body['identifying_information']['sc_association_org_id_no'] ?? null,
                $body['tin'] ?? $body['identifying_information']['tin'] ?? null,
                $body['service_business_employment'] ?? $body['identifying_information']['service_business_employment'] ?? null,
                $body['current_pension'] ?? $body['identifying_information']['current_pension'] ?? null,
                (($body['capability_to_travel'] ?? $body['identifying_information']['capability_to_travel'] ?? 'No') === 'Yes') ? 'Yes' : 'No',
                $body['spouse_name'] ?? $body['family_composition']['spouse']['name'] ?? null,
                $body['father_last_name'] ?? $body['family_composition']['father']['last_name'] ?? null,
                $body['father_first_name'] ?? $body['family_composition']['father']['first_name'] ?? null,
                $body['father_middle_name'] ?? $body['family_composition']['father']['middle_name'] ?? null,
                $body['father_extension'] ?? $body['family_composition']['father']['extension'] ?? null,
                $body['mother_last_name'] ?? $body['family_composition']['mother']['last_name'] ?? null,
                $body['mother_first_name'] ?? $body['family_composition']['mother']['first_name'] ?? null,
                $body['mother_middle_name'] ?? $body['family_composition']['mother']['middle_name'] ?? null,
                $body['community_service_other_text'] ?? null,
            ]);

            $seniorId = (int) $this->db->lastInsertId();
            $contacts = $body['contacts'] ?? $body['identifying_information']['contacts'] ?? [];
            if (is_array($contacts)) {
                foreach ($contacts as $contact) {
                    if (!is_array($contact) || empty($contact['name'])) {
                        continue;
                    }
                    $this->execute(
                        'INSERT INTO senior_contacts (senior_id, type, name, relationship, phone, email) VALUES (?, ?, ?, ?, ?, ?)',
                        [$seniorId, $contact['type'] ?? 'primary', $contact['name'], $contact['relationship'] ?? null, $contact['phone'] ?? null, $contact['email'] ?? null]
                    );
                }
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Senior citizen record created successfully',
                'data' => $this->getSeniorByIdWithRelations($seniorId),
            ], 201);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateSenior(): void
    {
        try {
            $body = $this->input();
            $id = isset($body['residentId']) ? (int) $body['residentId'] : 0;
            if ($id <= 0) {
                $this->jsonResponse(['success' => false, 'error' => 'Resident ID is required'], 400);
            }

            $existing = $this->queryOne('SELECT * FROM senior_citizens WHERE id = ? LIMIT 1', [$id]);
            if (!is_array($existing)) {
                $this->jsonResponse(['success' => false, 'error' => 'Senior citizen not found'], 404);
            }

            $beforeContacts = $this->queryAll('SELECT type, name, relationship, phone, email FROM senior_contacts WHERE senior_id = ?', [$id]);
            $beforeChildren = $this->queryAll('SELECT full_name, occupation, income, age, working_status FROM senior_children WHERE senior_id = ?', [$id]);
            $beforeEducationRows = $this->queryAll('SELECT educational_attainment FROM senior_education WHERE senior_id = ?', [$id]);
            $beforeSkillRows = $this->queryAll('SELECT skill FROM senior_skills WHERE senior_id = ?', [$id]);
            $beforeCommunityRows = $this->queryAll('SELECT service FROM senior_community_services WHERE senior_id = ?', [$id]);

            $map = [
                'first_name',
                'middle_name',
                'last_name',
                'extension',
                'barangay',
                'purok',
                'age',
                'place_of_birth',
                'marital_status',
                'gender',
                'osca_id_number',
                'gsis_sss',
                'philhealth',
                'sc_association_org_id_no',
                'tin',
                'other_govt_id',
                'service_business_employment',
                'current_pension',
                'capability_to_travel',
                'spouse_name',
                'father_last_name',
                'father_first_name',
                'father_middle_name',
                'father_extension',
                'mother_last_name',
                'mother_first_name',
                'mother_middle_name',
                'community_service_other_text',
                'status',
                'archive_reason',
            ];

            $fields = [];
            $params = [];
            foreach ($map as $field) {
                if (array_key_exists($field, $body)) {
                    $fields[] = $field . ' = ?';
                    $params[] = $body[$field] === '' ? null : $body[$field];
                }
            }

            if (array_key_exists('birthday', $body)) {
                $fields[] = 'date_of_birth = ?';
                $params[] = $this->parseDate((string) $body['birthday']);
            }

            $editor = $_SESSION['user']['email'] ?? ($body['edited_by'] ?? 'Unknown');
            $editedAt = date('Y-m-d H:i:s');
            $fields[] = 'edited_by = ?';
            $fields[] = 'edited_at = ?';
            $params[] = $editor;
            $params[] = $editedAt;

            $params[] = $id;
            $this->execute('UPDATE senior_citizens SET ' . implode(', ', $fields) . ' WHERE id = ?', $params);

            $this->execute('DELETE FROM senior_contacts WHERE senior_id = ?', [$id]);
            $this->execute('DELETE FROM senior_children WHERE senior_id = ?', [$id]);
            $this->execute('DELETE FROM senior_education WHERE senior_id = ?', [$id]);
            $this->execute('DELETE FROM senior_skills WHERE senior_id = ?', [$id]);
            $this->execute('DELETE FROM senior_community_services WHERE senior_id = ?', [$id]);

            $contacts = $body['contacts'] ?? $body['identifying_information']['contacts'] ?? [];
            if (is_array($contacts)) {
                foreach ($contacts as $contact) {
                    if (!is_array($contact) || empty($contact['name'])) {
                        continue;
                    }
                    $this->execute(
                        'INSERT INTO senior_contacts (senior_id, type, name, relationship, phone, email) VALUES (?, ?, ?, ?, ?, ?)',
                        [$id, $contact['type'] ?? 'primary', $contact['name'], $contact['relationship'] ?? null, $contact['phone'] ?? null, $contact['email'] ?? null]
                    );
                }
            }

            $children = $body['children'] ?? $body['family_composition']['children'] ?? [];
            if (is_array($children)) {
                foreach ($children as $child) {
                    if (!is_array($child) || empty($child['full_name'])) {
                        continue;
                    }
                    $this->execute(
                        'INSERT INTO senior_children (senior_id, full_name, occupation, income, age, working_status) VALUES (?, ?, ?, ?, ?, ?)',
                        [$id, $child['full_name'], $child['occupation'] ?? null, $child['income'] ?? null, $child['age'] ?? null, $child['working_status'] ?? null]
                    );
                }
            }

            $educationalAttainment = $body['educational_attainment'] ?? $body['education_hr_profile']['educational_attainment'] ?? [];
            if (is_array($educationalAttainment)) {
                foreach ($educationalAttainment as $educationItem) {
                    if ($educationItem === null || $educationItem === '') {
                        continue;
                    }
                    $this->execute(
                        'INSERT INTO senior_education (senior_id, educational_attainment) VALUES (?, ?)',
                        [$id, $educationItem]
                    );
                }
            }

            $skills = $body['skills'] ?? $body['education_hr_profile']['skills'] ?? [];
            $skillOtherText = trim((string) ($body['skill_other_text'] ?? $body['education_hr_profile']['skill_other_text'] ?? ''));
            if (is_array($skills)) {
                foreach ($skills as $skill) {
                    if ($skill === null || $skill === '') {
                        continue;
                    }

                    if ((string) $skill === 'Other' && $skillOtherText !== '') {
                        $this->execute(
                            'INSERT INTO senior_skills (senior_id, skill) VALUES (?, ?)',
                            [$id, 'Other: ' . $skillOtherText]
                        );
                        continue;
                    }

                    $this->execute(
                        'INSERT INTO senior_skills (senior_id, skill) VALUES (?, ?)',
                        [$id, $skill]
                    );
                }
            }

            $communityServices = $body['community_service'] ?? [];
            if (is_array($communityServices)) {
                foreach ($communityServices as $service) {
                    if ($service === null || $service === '') {
                        continue;
                    }
                    $this->execute(
                        'INSERT INTO senior_community_services (senior_id, service) VALUES (?, ?)',
                        [$id, $service]
                    );
                }
            }

            $scalarFieldsForLogs = [
                'first_name',
                'middle_name',
                'last_name',
                'extension',
                'barangay',
                'purok',
                'age',
                'place_of_birth',
                'marital_status',
                'gender',
                'osca_id_number',
                'gsis_sss',
                'philhealth',
                'sc_association_org_id_no',
                'tin',
                'other_govt_id',
                'service_business_employment',
                'current_pension',
                'capability_to_travel',
                'spouse_name',
                'father_last_name',
                'father_first_name',
                'father_middle_name',
                'father_extension',
                'mother_last_name',
                'mother_first_name',
                'mother_middle_name',
                'community_service_other_text',
                'status',
                'archive_reason',
            ];

            foreach ($scalarFieldsForLogs as $field) {
                $beforeValue = $existing[$field] ?? null;
                $afterValue = array_key_exists($field, $body)
                    ? ($body[$field] === '' ? null : $body[$field])
                    : $beforeValue;
                $this->addSeniorEditLog($id, $field, $beforeValue, $afterValue, (string) $editor, $editedAt);
            }

            $beforeDob = $existing['date_of_birth'] ?? null;
            $afterDob = array_key_exists('birthday', $body)
                ? $this->parseDate((string) $body['birthday'])
                : $beforeDob;
            $this->addSeniorEditLog($id, 'date_of_birth', $beforeDob, $afterDob, (string) $editor, $editedAt);

            $beforeEducation = $this->canonicalizeStringList(array_map(static fn(array $row): string => (string) ($row['educational_attainment'] ?? ''), $beforeEducationRows));
            $beforeSkills = $this->canonicalizeStringList(array_map(static fn(array $row): string => (string) ($row['skill'] ?? ''), $beforeSkillRows));
            $beforeCommunity = $this->canonicalizeStringList(array_map(static fn(array $row): string => (string) ($row['service'] ?? ''), $beforeCommunityRows));

            $afterEducation = $this->canonicalizeStringList(is_array($educationalAttainment) ? $educationalAttainment : []);
            $beforeSkillOtherText = null;
            foreach ($beforeSkills as $skillItem) {
                if (stripos((string) $skillItem, 'Other:') === 0) {
                    $beforeSkillOtherText = trim(substr((string) $skillItem, 6));
                    break;
                }
            }
            $afterSkills = $this->canonicalizeStringList(is_array($skills) ? $skills : []);
            $afterCommunity = $this->canonicalizeStringList(is_array($communityServices) ? $communityServices : []);

            $this->addSeniorEditLog(
                $id,
                'contacts',
                $this->canonicalizeSeniorContacts($beforeContacts),
                $this->canonicalizeSeniorContacts(is_array($contacts) ? $contacts : []),
                (string) $editor,
                $editedAt
            );
            $this->addSeniorEditLog(
                $id,
                'children',
                $this->canonicalizeSeniorChildren($beforeChildren),
                $this->canonicalizeSeniorChildren(is_array($children) ? $children : []),
                (string) $editor,
                $editedAt
            );
            $this->addSeniorEditLog($id, 'educational_attainment', $beforeEducation, $afterEducation, (string) $editor, $editedAt);
            $this->addSeniorEditLog($id, 'skills', $beforeSkills, $afterSkills, (string) $editor, $editedAt);
            $this->addSeniorEditLog($id, 'skill_other_text', $beforeSkillOtherText, $skillOtherText, (string) $editor, $editedAt);
            $this->addSeniorEditLog($id, 'community_service', $beforeCommunity, $afterCommunity, (string) $editor, $editedAt);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Senior citizen updated successfully',
                'data' => $this->getSeniorByIdWithRelations($id),
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getSeniorEditLogs(): void
    {
        try {
            $seniorId = isset($_GET['senior_id']) ? (int) $_GET['senior_id'] : 0;
            if ($seniorId <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'Senior ID is required'], 400);
            }

            $logs = $this->queryAll(
                'SELECT id, senior_id, field, old_value, new_value, edited_by, edited_at
                 FROM senior_edit_logs
                 WHERE senior_id = ?
                 ORDER BY edited_at DESC, id DESC',
                [$seniorId]
            );

            $this->jsonResponse(['success' => true, 'data' => $logs]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load senior edit logs'], 500);
        }
    }

    public function getPwdEditLogs(): void
    {
        try {
            $pwdId = isset($_GET['pwd_id']) ? (int) $_GET['pwd_id'] : 0;
            if ($pwdId <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'PWD ID is required'], 400);
            }

            $logs = $this->queryAll(
                'SELECT id, pwd_id, field, old_value, new_value, edited_by, edited_at
                 FROM pwd_edit_logs
                 WHERE pwd_id = ?
                 ORDER BY edited_at DESC, id DESC',
                [$pwdId]
            );

            $this->jsonResponse(['success' => true, 'data' => $logs]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load PWD edit logs'], 500);
        }
    }

    public function archiveSenior(): void
    {
        $body = $this->input();
        $id = isset($body['senior_id']) ? (int) $body['senior_id'] : 0;
        if ($id <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Senior Citizen ID is required'], 400);
        }

        $changed = $this->execute('UPDATE senior_citizens SET status = "Archived", archive_reason = ? WHERE id = ?', [$body['reason'] ?? null, $id]);
        if ($changed === 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Senior Citizen record not found'], 404);
        }
        $this->jsonResponse(['success' => true, 'message' => 'Senior Citizen record archived successfully', 'data' => $this->getSeniorByIdWithRelations($id)]);
    }

    public function unarchiveSenior(): void
    {
        $body = $this->input();
        $id = isset($body['senior_id']) ? (int) $body['senior_id'] : 0;
        if ($id <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Senior Citizen ID is required'], 400);
        }

        $changed = $this->execute('UPDATE senior_citizens SET status = "Active", archive_reason = NULL WHERE id = ?', [$id]);
        if ($changed === 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Senior Citizen record not found'], 404);
        }
        $this->jsonResponse(['success' => true, 'message' => 'Senior Citizen record unarchived successfully', 'data' => $this->getSeniorByIdWithRelations($id)]);
    }

    public function getSeniorCitizensForReport(): void
    {
        try {
            $filters = ["status <> 'Archived'"];
            $params = [];

            $sessionUser = $_SESSION['user'] ?? null;
            if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
                $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
                if ($scope === null) {
                    $this->jsonResponse(['success' => false, 'message' => 'Barangay account has no assigned barangay.'], 403);
                }
                $filters[] = 'barangay = ?';
                $params[] = $scope['barangay'];
            }

            if (!empty($_GET['month'])) {
                $month = max(1, min(12, (int) $_GET['month']));
                $year = !empty($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
                $start = sprintf('%04d-%02d-01 00:00:00', $year, $month);
                $end = date('Y-m-t 23:59:59', strtotime($start));
                $filters[] = 'created_at >= ?';
                $filters[] = 'created_at <= ?';
                $params[] = $start;
                $params[] = $end;
            } elseif (!empty($_GET['year'])) {
                $year = (int) $_GET['year'];
                $filters[] = 'created_at >= ?';
                $filters[] = 'created_at <= ?';
                $params[] = sprintf('%04d-01-01 00:00:00', $year);
                $params[] = sprintf('%04d-12-31 23:59:59', $year);
            }

            $rows = $this->queryAll(
                'SELECT id, barangay, purok, gender FROM senior_citizens WHERE ' . implode(' AND ', $filters),
                $params
            );

            $data = array_map(static function (array $r): array {
                return [
                    '_id' => (int) $r['id'],
                    'identifying_information' => [
                        'address' => ['barangay' => $r['barangay'] ?? null, 'purok' => $r['purok'] ?? null],
                        'gender' => $r['gender'] ?? null,
                    ],
                ];
            }, $rows);

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load senior citizens data'], 500);
        }
    }

    public function getSeniorCitizensByBarangay(): void
    {
        $barangay = (string) ($_GET['barangay'] ?? '');
        if (trim($barangay) === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Barangay is required'], 400);
        }

        $filters = ['s.barangay = ?', "s.status <> 'Archived'"];
        $params = [$barangay];
        $month = isset($_GET['month']) ? (int) $_GET['month'] : 0;
        $year = isset($_GET['year']) ? (int) $_GET['year'] : 0;

        if ($month >= 1 && $month <= 12) {
            $filters[] = 'MONTH(s.created_at) = ?';
            $params[] = $month;
            if ($year >= 2000) {
                $filters[] = 'YEAR(s.created_at) = ?';
                $params[] = $year;
            }
        } elseif ($year >= 2000) {
            $filters[] = 'YEAR(s.created_at) = ?';
            $params[] = $year;
        }

        $sessionUser = $_SESSION['user'] ?? null;
        if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
            $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
            if ($scope === null || trim((string) $scope['barangay']) !== trim($barangay)) {
                $this->jsonResponse(['success' => false, 'message' => 'Forbidden'], 403);
            }
        }

        $rows = $this->queryAll(
            'SELECT s.id, s.last_name, s.first_name, s.middle_name, s.extension, s.age, s.gender,
                    (SELECT sc.phone FROM senior_contacts sc WHERE sc.senior_id = s.id AND sc.phone IS NOT NULL AND sc.phone <> ""
                     ORDER BY CASE WHEN sc.type = "primary" THEN 0 ELSE 1 END, sc.id ASC LIMIT 1) AS contact
             FROM senior_citizens s
             WHERE ' . implode(' AND ', $filters) . '
             ORDER BY s.id DESC',
            $params
        );

        $data = array_map(static function (array $s): array {
            $full = trim(implode(' ', array_filter([$s['last_name'] ?? null, $s['first_name'] ?? null, $s['middle_name'] ?? null, $s['extension'] ?? null])));
            return [
                'id' => (int) $s['id'],
                'fullName' => $full !== '' ? $full : 'Unnamed',
                'gender' => $s['gender'] ?? 'N/A',
                'age' => $s['age'] ?? 'N/A',
                'contact' => $s['contact'] ?? 'N/A',
            ];
        }, $rows);

        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getSeniorCitizensByPurok(): void
    {
        $purok = (string) ($_GET['purok'] ?? '');
        if (trim($purok) === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Purok is required'], 400);
        }

        $filters = ['s.purok = ?', "s.status <> 'Archived'"];
        $params = [$purok];
        $month = isset($_GET['month']) ? (int) $_GET['month'] : 0;
        $year = isset($_GET['year']) ? (int) $_GET['year'] : 0;

        if ($month >= 1 && $month <= 12) {
            $filters[] = 'MONTH(s.created_at) = ?';
            $params[] = $month;
            if ($year >= 2000) {
                $filters[] = 'YEAR(s.created_at) = ?';
                $params[] = $year;
            }
        } elseif ($year >= 2000) {
            $filters[] = 'YEAR(s.created_at) = ?';
            $params[] = $year;
        }

        $sessionUser = $_SESSION['user'] ?? null;
        if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
            $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
            if ($scope === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Barangay account has no assigned barangay.'], 403);
            }
            $filters[] = 's.barangay = ?';
            $params[] = $scope['barangay'];
        }

        $rows = $this->queryAll(
            'SELECT s.id, s.last_name, s.first_name, s.middle_name, s.extension, s.age, s.gender,
                    (SELECT sc.phone FROM senior_contacts sc WHERE sc.senior_id = s.id AND sc.phone IS NOT NULL AND sc.phone <> ""
                     ORDER BY CASE WHEN sc.type = "primary" THEN 0 ELSE 1 END, sc.id ASC LIMIT 1) AS contact
             FROM senior_citizens s
             WHERE ' . implode(' AND ', $filters) . '
             ORDER BY s.id DESC',
            $params
        );

        $data = array_map(static function (array $s): array {
            $full = trim(implode(' ', array_filter([$s['last_name'] ?? null, $s['first_name'] ?? null, $s['middle_name'] ?? null, $s['extension'] ?? null])));
            return [
                'id' => (int) $s['id'],
                'fullName' => $full !== '' ? $full : 'Unnamed',
                'gender' => $s['gender'] ?? 'N/A',
                'age' => $s['age'] ?? 'N/A',
                'contact' => $s['contact'] ?? 'N/A',
            ];
        }, $rows);

        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getPwdsByBarangay(): void
    {
        $barangay = (string) ($_GET['barangay'] ?? '');
        if (trim($barangay) === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Barangay is required'], 400);
        }

        $filters = ['p.barangay = ?', "p.status <> 'Archived'"];
        $params = [$barangay];
        $month = isset($_GET['month']) ? (int) $_GET['month'] : 0;
        $year = isset($_GET['year']) ? (int) $_GET['year'] : 0;

        if ($month >= 1 && $month <= 12) {
            $filters[] = 'MONTH(p.created_at) = ?';
            $params[] = $month;
            if ($year >= 2000) {
                $filters[] = 'YEAR(p.created_at) = ?';
                $params[] = $year;
            }
        } elseif ($year >= 2000) {
            $filters[] = 'YEAR(p.created_at) = ?';
            $params[] = $year;
        }

        $sessionUser = $_SESSION['user'] ?? null;
        if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
            $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
            if ($scope === null || trim((string) $scope['barangay']) !== trim($barangay)) {
                $this->jsonResponse(['success' => false, 'message' => 'Forbidden'], 403);
            }
        }

        $rows = $this->queryAll(
            'SELECT p.id, p.first_name, p.middle_name, p.last_name, p.age, p.gender,
                    c.phone AS primary_phone,
                    GROUP_CONCAT(DISTINCT d.disability ORDER BY d.disability SEPARATOR ", ") AS disabilities
             FROM pwd p
             LEFT JOIN pwd_contacts c ON c.pwd_id = p.id AND c.type = "primary"
             LEFT JOIN pwd_disabilities d ON d.pwd_id = p.id
             WHERE ' . implode(' AND ', $filters) . '
             GROUP BY p.id, p.first_name, p.middle_name, p.last_name, p.age, p.gender, c.phone',
            $params
        );

        $data = array_map(static function (array $pwd): array {
            $full = trim(implode(' ', array_filter([$pwd['last_name'] ?? null, $pwd['first_name'] ?? null, $pwd['middle_name'] ?? null])));
            return [
                'id' => (int) $pwd['id'],
                'fullName' => $full !== '' ? $full : 'Unnamed',
                'gender' => $pwd['gender'] ?? 'N/A',
                'age' => $pwd['age'] ?? 'N/A',
                'contact' => $pwd['primary_phone'] ?? 'N/A',
                'disability' => $pwd['disabilities'] ?? 'N/A',
            ];
        }, $rows);

        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getPwdsByPurok(): void
    {
        $purok = (string) ($_GET['purok'] ?? '');
        if (trim($purok) === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Purok is required'], 400);
        }

        $filters = ['p.purok = ?', "p.status <> 'Archived'"];
        $params = [$purok];
        $month = isset($_GET['month']) ? (int) $_GET['month'] : 0;
        $year = isset($_GET['year']) ? (int) $_GET['year'] : 0;

        if ($month >= 1 && $month <= 12) {
            $filters[] = 'MONTH(p.created_at) = ?';
            $params[] = $month;
            if ($year >= 2000) {
                $filters[] = 'YEAR(p.created_at) = ?';
                $params[] = $year;
            }
        } elseif ($year >= 2000) {
            $filters[] = 'YEAR(p.created_at) = ?';
            $params[] = $year;
        }

        $sessionUser = $_SESSION['user'] ?? null;
        if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
            $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
            if ($scope === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Barangay account has no assigned barangay.'], 403);
            }
            $filters[] = 'p.barangay = ?';
            $params[] = $scope['barangay'];
        }

        $rows = $this->queryAll(
            'SELECT p.id, p.first_name, p.middle_name, p.last_name, p.age, p.gender,
                    c.phone AS primary_phone,
                    GROUP_CONCAT(DISTINCT d.disability ORDER BY d.disability SEPARATOR ", ") AS disabilities
             FROM pwd p
             LEFT JOIN pwd_contacts c ON c.pwd_id = p.id AND c.type = "primary"
             LEFT JOIN pwd_disabilities d ON d.pwd_id = p.id
             WHERE ' . implode(' AND ', $filters) . '
             GROUP BY p.id, p.first_name, p.middle_name, p.last_name, p.age, p.gender, c.phone',
            $params
        );

        $data = array_map(static function (array $pwd): array {
            $full = trim(implode(' ', array_filter([$pwd['last_name'] ?? null, $pwd['first_name'] ?? null, $pwd['middle_name'] ?? null])));
            return [
                'id' => (int) $pwd['id'],
                'fullName' => $full !== '' ? $full : 'Unnamed',
                'gender' => $pwd['gender'] ?? 'N/A',
                'age' => $pwd['age'] ?? 'N/A',
                'contact' => $pwd['primary_phone'] ?? 'N/A',
                'disability' => $pwd['disabilities'] ?? 'N/A',
            ];
        }, $rows);

        $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    public function getBarangays(): void
    {
        try {
            $rows = $this->queryAll(
                'SELECT b.id, b.barangay, GROUP_CONCAT(p.purok ORDER BY p.purok) AS puroks
                 FROM barangays b
                 LEFT JOIN puroks p ON b.id = p.barangay_id
                 GROUP BY b.id, b.barangay
                 ORDER BY b.barangay'
            );

            $barangays = [];
            $barangayList = [];
            foreach ($rows as $row) {
                $puroks = !empty($row['puroks']) ? explode(',', (string) $row['puroks']) : [];
                $barangays[(string) $row['barangay']] = $puroks;
                $barangayList[] = ['id' => (int) $row['id'], 'barangay' => $row['barangay'], 'puroks' => $puroks];
            }

            $this->jsonResponse(['success' => true, 'barangays' => $barangays, 'barangayList' => $barangayList]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    public function createBarangay(): void
    {
        $body = $this->input();
        $name = trim((string) ($body['barangayName'] ?? ''));
        if ($name === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Barangay name is required'], 400);
        }

        $exists = $this->queryOne('SELECT id FROM barangays WHERE LOWER(barangay) = LOWER(?) LIMIT 1', [$name]);
        if ($exists !== null) {
            $this->jsonResponse(['success' => false, 'message' => 'Barangay already exists'], 400);
        }

        $stmt = $this->db->prepare('INSERT INTO barangays (barangay) VALUES (?)');
        $stmt->execute([$name]);
        $id = (int) $this->db->lastInsertId();

        $this->jsonResponse(['success' => true, 'message' => 'Barangay created successfully', 'barangay' => ['id' => $id, 'barangay' => $name, 'puroks' => []]]);
    }

    public function addPurok(): void
    {
        $body = $this->input();
        $barangayId = isset($body['barangayId']) ? (int) $body['barangayId'] : 0;
        $purokName = trim((string) ($body['purokName'] ?? ''));

        if ($barangayId <= 0 || $purokName === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Barangay and purok name are required'], 400);
        }

        $barangay = $this->queryOne('SELECT id, barangay FROM barangays WHERE id = ? LIMIT 1', [$barangayId]);
        if ($barangay === null) {
            $this->jsonResponse(['success' => false, 'message' => 'Barangay not found'], 404);
        }

        $dup = $this->queryOne('SELECT id FROM puroks WHERE barangay_id = ? AND LOWER(purok) = LOWER(?) LIMIT 1', [$barangayId, $purokName]);
        if ($dup !== null) {
            $this->jsonResponse(['success' => false, 'message' => 'Purok already exists in this barangay'], 400);
        }

        $this->execute('INSERT INTO puroks (barangay_id, purok) VALUES (?, ?)', [$barangayId, $purokName]);
        $this->jsonResponse(['success' => true, 'message' => 'Purok added successfully', 'barangay' => $barangay]);
    }

    public function getSilayBoundary(): void
    {
        try {
            $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Silay City.geojson';
            if (!file_exists($path)) {
                $this->jsonResponse(['success' => false, 'message' => 'Boundary file not found'], 404);
            }

            $raw = file_get_contents($path);
            $json = $raw !== false ? json_decode($raw, true) : null;
            if (!is_array($json)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid boundary file'], 500);
            }

            $this->jsonResponse($json);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load Silay City boundary'], 500);
        }
    }

    public function sendSms(): void
    {
        try {
            $body = $this->input();
            $recipients = $body['recipients'] ?? null;
            $message = isset($body['message']) ? (string) $body['message'] : '';
            $sentBy = (string) ($_SESSION['user']['email'] ?? 'Unknown');

            $apiToken = getenv('API_TOKEN') ?: '';
            if ($apiToken === '') {
                $this->jsonResponse(['success' => false, 'message' => 'API_TOKEN not configured on server'], 500);
            }

            if (!is_array($recipients) || count($recipients) === 0 || trim($message) === '') {
                $this->jsonResponse(['success' => false, 'message' => 'Recipients and message are required'], 400);
            }

            $results = [];
            $historyRows = [];
            foreach ($recipients as $r) {
                if (!is_array($r) || empty($r['phone'])) {
                    $results[] = ['status' => 'skipped', 'reason' => 'no phone'];

                    if (is_array($r) && !empty($r['record_id'])) {
                        $historyRows[] = [
                            'recipient_type' => (string) ($r['recipient_type'] ?? 'PWD'),
                            'record_id' => (int) $r['record_id'],
                            'phone_number' => (string) ($r['phone'] ?? ''),
                            'first_name' => (string) ($r['first_name'] ?? ''),
                            'middle_name' => (string) ($r['middle_name'] ?? ''),
                            'last_name' => (string) ($r['last_name'] ?? ''),
                            'barangay' => (string) ($r['barangay'] ?? ''),
                            'purok' => (string) ($r['purok'] ?? ''),
                            'message' => $message,
                            'status' => 'skipped',
                            'sent_by' => $sentBy,
                            'received' => 0,
                        ];
                    }
                    continue;
                }

                $payload = json_encode([
                    'api_token' => $apiToken,
                    'phone_number' => (string) $r['phone'],
                    'message' => $message,
                ]);

                $ch = curl_init('https://sms.iprogtech.com/api/v1/sms_messages');
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_TIMEOUT => 15,
                ]);
                $response = curl_exec($ch);
                $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);

                if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
                    $results[] = ['phone' => $r['phone'], 'status' => 'sent', 'response' => json_decode((string) $response, true)];
                    if (!empty($r['record_id'])) {
                        $historyRows[] = [
                            'recipient_type' => (string) ($r['recipient_type'] ?? 'PWD'),
                            'record_id' => (int) $r['record_id'],
                            'phone_number' => (string) ($r['phone'] ?? ''),
                            'first_name' => (string) ($r['first_name'] ?? ''),
                            'middle_name' => (string) ($r['middle_name'] ?? ''),
                            'last_name' => (string) ($r['last_name'] ?? ''),
                            'barangay' => (string) ($r['barangay'] ?? ''),
                            'purok' => (string) ($r['purok'] ?? ''),
                            'message' => $message,
                            'status' => 'sent',
                            'sent_by' => $sentBy,
                            'received' => 0,
                        ];
                    }
                } else {
                    $results[] = ['phone' => $r['phone'], 'status' => 'error', 'error' => $error !== '' ? $error : $response];
                    if (!empty($r['record_id'])) {
                        $historyRows[] = [
                            'recipient_type' => (string) ($r['recipient_type'] ?? 'PWD'),
                            'record_id' => (int) $r['record_id'],
                            'phone_number' => (string) ($r['phone'] ?? ''),
                            'first_name' => (string) ($r['first_name'] ?? ''),
                            'middle_name' => (string) ($r['middle_name'] ?? ''),
                            'last_name' => (string) ($r['last_name'] ?? ''),
                            'barangay' => (string) ($r['barangay'] ?? ''),
                            'purok' => (string) ($r['purok'] ?? ''),
                            'message' => $message,
                            'status' => 'error',
                            'sent_by' => $sentBy,
                            'received' => 0,
                        ];
                    }
                }
            }

            if (!empty($historyRows)) {
                try {
                    $sql = 'INSERT INTO sms_history
                        (recipient_type, record_id, phone_number, first_name, middle_name, last_name, barangay, purok, message, status, sent_by, received)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    $stmt = $this->db?->prepare($sql);
                    if ($stmt !== null) {
                        foreach ($historyRows as $h) {
                            $stmt->execute([
                                $h['recipient_type'],
                                $h['record_id'],
                                $h['phone_number'],
                                $h['first_name'],
                                $h['middle_name'],
                                $h['last_name'],
                                $h['barangay'],
                                $h['purok'],
                                $h['message'],
                                $h['status'],
                                $h['sent_by'],
                                $h['received'],
                            ]);
                        }
                    }
                } catch (Throwable $e) {
                    // Do not fail send response if history logging fails.
                }
            }

            $allSent = count(array_filter($results, static fn(array $r): bool => ($r['status'] ?? '') !== 'sent')) === 0;
            if ($allSent) {
                $this->jsonResponse(['success' => true, 'results' => $results]);
            }

            $this->jsonResponse(['success' => false, 'message' => 'SMS service is down at the moment', 'results' => $results], 503);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getSmsHistory(): void
    {
        try {
            $recipientType = isset($_GET['recipient_type']) ? (string) $_GET['recipient_type'] : null;
            $limit = isset($_GET['limit']) ? max(1, (int) $_GET['limit']) : 100;
            $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
            $offset = ($page - 1) * $limit;

            $allowed = ['PWD', 'Youth', 'Senior'];
            $hasType = $recipientType !== null && in_array($recipientType, $allowed, true);

            $where = $hasType ? 'WHERE recipient_type = ?' : '';
            $params = $hasType ? [$recipientType] : [];

            $rows = $this->queryAll(
                'SELECT id, recipient_type, record_id, phone_number, first_name, middle_name, last_name, barangay, purok, message, status, sent_by, sent_at, received
                 FROM sms_history ' . $where . ' ORDER BY sent_at DESC LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset,
                $params
            );

            $history = array_map(static function (array $r): array {
                $r['_id'] = (int) $r['id'];
                $r['received'] = (bool) $r['received'];
                unset($r['id']);
                return $r;
            }, $rows);

            $countRows = $this->queryAll('SELECT COUNT(*) AS total FROM sms_history ' . $where, $params);
            $total = isset($countRows[0]['total']) ? (int) $countRows[0]['total'] : 0;

            $this->jsonResponse([
                'success' => true,
                'data' => $history,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => (int) ceil($total / $limit),
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function updateSmsReceived(): void
    {
        $body = $this->input();
        $smsId = isset($body['smsId']) ? (int) $body['smsId'] : 0;
        if ($smsId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'SMS ID is required'], 400);
        }

        $received = !empty($body['received']) ? 1 : 0;
        $changed = $this->execute('UPDATE sms_history SET received = ? WHERE id = ?', [$received, $smsId]);
        if ($changed === 0) {
            $this->jsonResponse(['success' => false, 'message' => 'SMS record not found'], 404);
        }

        $row = $this->queryOne('SELECT * FROM sms_history WHERE id = ? LIMIT 1', [$smsId]);
        if ($row !== null) {
            $row['_id'] = (int) $row['id'];
            $row['received'] = (bool) $row['received'];
        }

        $this->jsonResponse(['success' => true, 'message' => 'SMS received status updated successfully', 'data' => $row]);
    }

    public function renderSuperAdmin(): void
    {
        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('superadmin/dashboard', [
            'title' => 'Super Admin',
            'user' => $_SESSION['user'] ?? null,
            'barangays' => $barangays,
        ]);
    }

    public function renderSuperAdminLogs(): void
    {
        try {
            $pwdLogs = $this->queryAll(
                'SELECT l.id, l.pwd_id, l.field, l.old_value, l.new_value, l.edited_by, l.edited_at,
                        TRIM(CONCAT(IFNULL(p.first_name, ""), " ", IFNULL(p.middle_name, ""), " ", IFNULL(p.last_name, ""))) AS record_name
                 FROM pwd_edit_logs l
                 LEFT JOIN pwd p ON p.id = l.pwd_id
                 ORDER BY (l.edited_at IS NULL), l.edited_at DESC, l.id DESC'
            );
        } catch (Throwable $e) {
            $pwdLogs = [];
        }

        try {
            $seniorLogs = $this->queryAll(
                'SELECT l.id, l.senior_id, l.field, l.old_value, l.new_value, l.edited_by, l.edited_at,
                        TRIM(CONCAT(IFNULL(s.first_name, ""), " ", IFNULL(s.middle_name, ""), " ", IFNULL(s.last_name, ""))) AS record_name
                 FROM senior_edit_logs l
                 LEFT JOIN senior_citizens s ON s.id = l.senior_id
                 ORDER BY (l.edited_at IS NULL), l.edited_at DESC, l.id DESC'
            );
        } catch (Throwable $e) {
            $seniorLogs = [];
        }

        try {
            $loginLogs = $this->queryAll(
                'SELECT l.id, l.user_id, l.status AS login_result, l.created_at,
                        u.name AS user_name, u.email AS user_email, u.role AS user_role, u.status AS user_status
                 FROM login_logs l
                 LEFT JOIN users u ON u.id = l.user_id
                 ORDER BY l.created_at DESC, l.id DESC'
            );
        } catch (Throwable $e) {
            $loginLogs = [];
        }

        $this->render('superadmin/logs', [
            'title' => 'Super Admin Logs',
            'user' => $_SESSION['user'] ?? null,
            'pwdLogs' => $pwdLogs,
            'seniorLogs' => $seniorLogs,
            'loginLogs' => $loginLogs,
        ]);
    }

    public function renderAddSenior(): void
    {
        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('staff/add_senior', [
            'title' => 'Add Senior',
            'user' => $_SESSION['user'] ?? null,
            'barangays' => $barangays,
        ]);
    }

    public function renderAddPWD(): void
    {
        $editPwd = null;
        $isEditMode = false;

        $editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
        if ($editId > 0) {
            try {
                $editPwd = $this->getPwdByIdWithRelations($editId);
                $isEditMode = $editPwd !== null;
            } catch (Throwable $e) {
                $editPwd = null;
                $isEditMode = false;
            }
        }

        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('staff/add_pwd', [
            'title' => $isEditMode ? 'Edit PWD' : 'Add PWD',
            'user' => $_SESSION['user'] ?? null,
            'barangays' => $barangays,
            'isEditMode' => $isEditMode,
            'editPwd' => $editPwd,
        ]);
    }

    public function renderSuperAdminIndex(): void
    {
        $this->renderSuperAdmin();
    }

    public function renderSuperAdminUser(): void
    {
        try {
            $users = $this->queryAll('SELECT id, name, email, role, status, barangay_id, staff_classification FROM users ORDER BY id ASC');
            $barangayList = $this->queryAll('SELECT id, barangay FROM barangays ORDER BY barangay ASC');
        } catch (Throwable $e) {
            $users = [];
            $barangayList = [];
        }

        $this->render('superadmin/users', [
            'title' => 'Super Admin Users',
            'user' => $_SESSION['user'] ?? null,
            'users' => $users,
            'barangayList' => $barangayList,
        ]);
    }

    public function renderRegister(): void
    {
        try {
            $barangayList = $this->queryAll('SELECT id, barangay FROM barangays ORDER BY barangay ASC');
        } catch (Throwable $e) {
            $barangayList = [];
        }

        $this->render('auth/register', [
            'barangayList' => $barangayList,
        ]);
    }

    public function renderSeniorForm(): void
    {
        try {
            $where = "WHERE status <> 'Archived'";
            if (isset($_GET['status']) && $_GET['status'] === 'archived') {
                $where = "WHERE status = 'Archived'";
            } elseif (isset($_GET['status']) && $_GET['status'] === 'all') {
                $where = '';
            }

            $rows = $this->queryAll(
                'SELECT id, last_name, first_name, middle_name, extension, barangay, purok, age, gender, status, created_at
                 FROM senior_citizens ' . $where . ' ORDER BY created_at DESC'
            );

        } catch (Throwable $e) {
            $rows = [];
        }

        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('staff/senior_form', [
            'title' => 'Senior Form',
            'user' => $_SESSION['user'] ?? null,
            'barangays' => $barangays,
            'seniors' => $rows,
        ]);
    }

    public function renderPWDForm(): void
    {
        try {
            $where = "WHERE status <> 'Archived'";
            if (isset($_GET['status']) && $_GET['status'] === 'archived') {
                $where = "WHERE status = 'Archived'";
            } elseif (isset($_GET['status']) && $_GET['status'] === 'all') {
                $where = '';
            }

            $rows = $this->queryAll(
                'SELECT id, last_name, first_name, middle_name, barangay, purok, age, gender, status, created_at
                 FROM pwd ' . $where . ' ORDER BY created_at DESC'
            );

        } catch (Throwable $e) {
            $rows = [];
        }

        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('staff/pwd_form', [
            'title' => 'PWD Form',
            'user' => $_SESSION['user'] ?? null,
            'barangays' => $barangays,
            'pwds' => $rows,
        ]);
    }

    public function generatePwdApplicationPdf(): void
    {
        try {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid PWD ID'], 400);
            }

            $pwd = $this->getPwdByIdWithRelations($id);
            if ($pwd === null) {
                $this->jsonResponse(['success' => false, 'message' => 'PWD record not found'], 404);
            }

            $templatePath = $this->projectRoot . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'PWD-APPLICATION-FORMFIELD.pdf';
            if (!file_exists($templatePath)) {
                $this->jsonResponse(['success' => false, 'message' => 'PWD PDF template not found'], 404);
            }

            $safeLast = preg_replace('/\s+/', '-', (string) ($pwd['last_name'] ?? 'PWD'));
            $safeFirst = preg_replace('/\s+/', '-', (string) ($pwd['first_name'] ?? 'Record'));
            $filename = 'PWD-' . ($safeLast ?: 'PWD') . '-' . ($safeFirst ?: 'Record') . '.pdf';

            // Prefer exact original Node pdf-lib mapping when available.
            $nodePdf = $this->generatePdfViaNode('pwd', $pwd, $templatePath);
            if ($nodePdf !== null) {
                header('X-PDF-Engine: node-pdf-lib');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . rawurlencode($filename) . '"');
                header('Content-Length: ' . (string) strlen($nodePdf));
                echo $nodePdf;
                exit;
            }

            $primaryPhone = '';
            if (!empty($pwd['contacts']) && is_array($pwd['contacts'])) {
                foreach ($pwd['contacts'] as $c) {
                    if (!empty($c['phone'])) {
                        $primaryPhone = (string) $c['phone'];
                        break;
                    }
                }
            }

            $this->outputPdfFromTemplateWithText(
                $templatePath,
                $filename,
                static function ($pdf) use ($pwd, $primaryPhone): void {
                    // Coordinates tuned for the current PDF template.
                    $pdf->SetXY(22, 33);
                    $pdf->Cell(48, 4, (string) ($pwd['last_name'] ?? ''));
                    $pdf->SetXY(74, 33);
                    $pdf->Cell(48, 4, (string) ($pwd['first_name'] ?? ''));
                    $pdf->SetXY(126, 33);
                    $pdf->Cell(34, 4, (string) ($pwd['middle_name'] ?? ''));

                    $pdf->SetXY(22, 44);
                    $pdf->Cell(82, 4, (string) ($pwd['barangay'] ?? ''));
                    $pdf->SetXY(108, 44);
                    $pdf->Cell(46, 4, (string) ($pwd['purok'] ?? ''));

                    $pdf->SetXY(22, 52);
                    $birthday = '';
                    if (!empty($pwd['birthday'])) {
                        $ts = strtotime((string) $pwd['birthday']);
                        $birthday = $ts ? date('m/d/Y', $ts) : '';
                    }
                    $pdf->Cell(40, 4, $birthday);

                    $pdf->SetXY(156, 52);
                    $pdf->Cell(34, 4, (string) ($pwd['gender'] ?? ''));

                    $pdf->SetXY(22, 72);
                    $pdf->Cell(60, 4, (string) ($pwd['civil_status'] ?? ''));
                    $pdf->SetXY(88, 72);
                    $pdf->Cell(90, 4, $primaryPhone);

                    $pdf->SetXY(22, 84);
                    $pdf->Cell(84, 4, (string) ($pwd['education_level'] ?? ''));
                    $pdf->SetXY(110, 84);
                    $pdf->Cell(80, 4, (string) ($pwd['employment_status'] ?? ''));

                    $pdf->SetXY(22, 95);
                    $pdf->Cell(60, 4, (string) ($pwd['sss_id'] ?? ''));
                    $pdf->SetXY(86, 95);
                    $pdf->Cell(60, 4, (string) ($pwd['gsis_sss_no'] ?? ''));
                    $pdf->SetXY(150, 95);
                    $pdf->Cell(40, 4, (string) ($pwd['philhealth_no'] ?? ''));
                }
            );
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to generate PWD PDF', 'error' => $e->getMessage()], 500);
        }
    }

    public function servePdfTemplate(): void
    {
        try {
            $type = strtolower((string) ($_GET['type'] ?? ''));
            $fileName = match ($type) {
                'pwd' => 'PWD-APPLICATION-FORMFIELD.pdf',
                'senior' => 'SENIOR-FORMFIELD.pdf',
                default => '',
            };

            if ($fileName === '') {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid template type'], 400);
            }

            $path = $this->projectRoot . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . $fileName;
            if (!file_exists($path)) {
                $this->jsonResponse(['success' => false, 'message' => 'Template not found'], 404);
            }

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . rawurlencode($fileName) . '"');
            header('Content-Length: ' . (string) filesize($path));
            readfile($path);
            exit;
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to serve template', 'error' => $e->getMessage()], 500);
        }
    }

    public function generateSeniorApplicationPdf(): void
    {
        try {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid Senior Citizen ID'], 400);
            }

            $senior = $this->getSeniorByIdWithRelations($id);
            if ($senior === null) {
                $this->jsonResponse(['success' => false, 'message' => 'Senior Citizen record not found'], 404);
            }

            $templatePath = $this->projectRoot . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'SENIOR-FORMFIELD.pdf';
            if (!file_exists($templatePath)) {
                $this->jsonResponse(['success' => false, 'message' => 'Senior PDF template not found'], 404);
            }

            $name = $senior['identifying_information']['name'] ?? [];
            $address = $senior['identifying_information']['address'] ?? [];
            $safeLast = preg_replace('/\s+/', '-', (string) ($name['last_name'] ?? 'Senior'));
            $safeFirst = preg_replace('/\s+/', '-', (string) ($name['first_name'] ?? 'Record'));
            $filename = 'Senior-' . ($safeLast ?: 'Senior') . '-' . ($safeFirst ?: 'Record') . '.pdf';

            // Prefer exact original Node pdf-lib mapping when available.
            $nodePdf = $this->generatePdfViaNode('senior', $senior, $templatePath);
            if ($nodePdf !== null) {
                header('X-PDF-Engine: node-pdf-lib');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . rawurlencode($filename) . '"');
                header('Content-Length: ' . (string) strlen($nodePdf));
                echo $nodePdf;
                exit;
            }

            $primaryPhone = '';
            $contacts = $senior['identifying_information']['contacts'] ?? [];
            if (is_array($contacts)) {
                foreach ($contacts as $c) {
                    if (!empty($c['phone'])) {
                        $primaryPhone = (string) $c['phone'];
                        break;
                    }
                }
            }

            $this->outputPdfFromTemplateWithText(
                $templatePath,
                $filename,
                static function ($pdf) use ($name, $address, $senior, $primaryPhone): void {
                    $info = $senior['identifying_information'] ?? [];

                    $pdf->SetXY(18, 32);
                    $pdf->Cell(55, 4, (string) ($name['last_name'] ?? ''));
                    $pdf->SetXY(75, 32);
                    $pdf->Cell(55, 4, (string) ($name['first_name'] ?? ''));
                    $pdf->SetXY(132, 32);
                    $pdf->Cell(48, 4, (string) ($name['middle_name'] ?? ''));

                    $pdf->SetXY(18, 42);
                    $pdf->Cell(84, 4, (string) ($address['barangay'] ?? ''));
                    $pdf->SetXY(104, 42);
                    $pdf->Cell(76, 4, (string) ($address['purok'] ?? ''));

                    $dob = '';
                    if (!empty($info['date_of_birth'])) {
                        $ts = strtotime((string) $info['date_of_birth']);
                        $dob = $ts ? date('m/d/Y', $ts) : '';
                    }
                    $pdf->SetXY(18, 52);
                    $pdf->Cell(40, 4, $dob);

                    $pdf->SetXY(62, 52);
                    $pdf->Cell(30, 4, (string) ($info['age'] ?? ''));
                    $pdf->SetXY(96, 52);
                    $pdf->Cell(28, 4, (string) ($info['gender'] ?? ''));
                    $pdf->SetXY(126, 52);
                    $pdf->Cell(54, 4, (string) ($info['marital_status'] ?? ''));

                    $pdf->SetXY(18, 63);
                    $pdf->Cell(84, 4, (string) ($info['osca_id_number'] ?? ''));
                    $pdf->SetXY(104, 63);
                    $pdf->Cell(76, 4, (string) ($info['gsis_sss'] ?? ''));

                    $pdf->SetXY(18, 73);
                    $pdf->Cell(84, 4, (string) ($info['philhealth'] ?? ''));
                    $pdf->SetXY(104, 73);
                    $pdf->Cell(76, 4, (string) ($info['tin'] ?? ''));

                    $pdf->SetXY(18, 83);
                    $pdf->Cell(162, 4, $primaryPhone);
                }
            );
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to generate Senior PDF', 'error' => $e->getMessage()], 500);
        }
    }

    public function renderAdminDashboard(): void
    {
        $this->render('admin/dashboard', ['title' => 'Admin Dashboard', 'user' => $_SESSION['user'] ?? null]);
    }

    public function renderPwdMapPage(): void
    {
        $this->render('admin/pwd_map', ['title' => 'PWD Map', 'user' => $_SESSION['user'] ?? null]);
    }

    public function renderSeniorMapPage(): void
    {
        $this->render('admin/senior_map', ['title' => 'Senior Map', 'user' => $_SESSION['user'] ?? null]);
    }

    public function renderAnalyticsPage(): void
    {
        $seniors = [];
        try {
            $where = "WHERE status <> 'Archived'";
            $basicRows = $this->queryAll(
                'SELECT id, last_name, first_name, middle_name, extension, barangay, purok, age, gender, status, created_at
                 FROM senior_citizens ' . $where . ' ORDER BY created_at DESC'
            );
            $seniors = array_values(array_filter(array_map(function (array $row): ?array {
                $full = $this->getSeniorByIdWithRelations((int) $row['id']);
                if ($full === null) {
                    return null;
                }
                $full['id'] = (int) $row['id'];
                $full['first_name'] = $row['first_name'] ?? null;
                $full['middle_name'] = $row['middle_name'] ?? null;
                $full['last_name'] = $row['last_name'] ?? null;
                $full['extension'] = $row['extension'] ?? null;
                $full['barangay'] = $row['barangay'] ?? null;
                $full['purok'] = $row['purok'] ?? null;
                $full['age'] = isset($row['age']) ? (int) $row['age'] : null;
                $full['gender'] = $row['gender'] ?? null;
                $full['status'] = $row['status'] ?? null;
                $full['created_at'] = $row['created_at'] ?? null;
                return $full;
            }, $basicRows)));
        } catch (Throwable $e) {
            $seniors = [];
        }

        $this->render('admin/analytics', [
            'title' => 'Analytics',
            'user' => $_SESSION['user'] ?? null,
            'seniors' => $seniors,
        ]);
    }

    public function renderUserManagementPage(): void
    {
        $this->render('admin/users', ['title' => 'User Management', 'user' => $_SESSION['user'] ?? null]);
    }

    public function renderStaffDashboard(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $this->render('staff/dashboard', ['title' => 'Staff Dashboard', 'user' => $_SESSION['user'] ?? null]);
    }

    public function renderOscaDashboard(): void
    {
        $seniors = [];
        $totalSeniors = 0;

        try {
            $where = "WHERE status <> 'Archived'";
            if (isset($_GET['status']) && $_GET['status'] === 'archived') {
                $where = "WHERE status = 'Archived'";
            } elseif (isset($_GET['status']) && $_GET['status'] === 'all') {
                $where = '';
            }

            $basicRows = $this->queryAll(
                'SELECT id, last_name, first_name, middle_name, extension, barangay, purok, age, gender, status, created_at
                 FROM senior_citizens ' . $where . ' ORDER BY created_at DESC'
            );

            $seniors = array_values(array_filter(array_map(function (array $row): ?array {
                $full = $this->getSeniorByIdWithRelations((int) $row['id']);
                if ($full === null) {
                    return null;
                }

                $full['id'] = (int) $row['id'];
                $full['first_name'] = $row['first_name'] ?? null;
                $full['middle_name'] = $row['middle_name'] ?? null;
                $full['last_name'] = $row['last_name'] ?? null;
                $full['extension'] = $row['extension'] ?? null;
                $full['barangay'] = $row['barangay'] ?? null;
                $full['purok'] = $row['purok'] ?? null;
                $full['age'] = isset($row['age']) ? (int) $row['age'] : null;
                $full['gender'] = $row['gender'] ?? null;
                $full['status'] = $row['status'] ?? null;
                $full['created_at'] = $row['created_at'] ?? null;
                return $full;
            }, $basicRows)));

            $totalSeniors = count($seniors);
        } catch (Throwable $e) {
            $seniors = [];
        }

        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('staff/staff_senior', [
            'title' => 'OSCA Staff Dashboard',
            'user' => $_SESSION['user'] ?? null,
            'totalSeniors' => $totalSeniors,
            'barangays' => $barangays,
            'seniors' => $seniors,
        ]);
    }

    public function renderPdaoDashboard(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        $pwds = [];
        $totalPwd = 0;

        try {
            $where = "WHERE status <> 'Archived'";
            if (isset($_GET['status']) && $_GET['status'] === 'archived') {
                $where = "WHERE status = 'Archived'";
            } elseif (isset($_GET['status']) && $_GET['status'] === 'all') {
                $where = '';
            }

            $pwds = $this->queryAll(
                'SELECT id, last_name, first_name, middle_name, barangay, purok, age, gender, status, created_at
                 FROM pwd ' . $where . ' ORDER BY created_at DESC'
            );
            $totalPwd = count($pwds);
        } catch (Throwable $e) {
            $pwds = [];
        }

        try {
            $barangays = $this->fetchBarangays();
        } catch (Throwable $e) {
            $barangays = [];
        }

        $this->render('staff/staff_pwd', [
            'title' => 'PDAO Staff Dashboard',
            'user' => $_SESSION['user'] ?? null,
            'totalPwd' => $totalPwd,
            'barangays' => $barangays,
            'pwds' => $pwds,
        ]);
    }

    public function renderAdminAlert(): void
    {
        $this->render('admin/alert', ['title' => 'Admin Alert', 'user' => $_SESSION['user'] ?? null]);
    }

    private function ensureNotificationsTables(): void
    {
        $this->execute(
            'CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                target_role VARCHAR(32) NOT NULL,
                target_user_id INT NULL,
                target_barangay VARCHAR(190) NULL,
                target_staff_classification VARCHAR(32) NULL,
                subject VARCHAR(255) NULL,
                message TEXT NOT NULL,
                created_by_user_id INT NULL,
                created_by_name VARCHAR(190) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_target_role (target_role),
                INDEX idx_target_user_id (target_user_id),
                INDEX idx_target_barangay (target_barangay),
                INDEX idx_target_staff_classification (target_staff_classification)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        $this->execute(
            'CREATE TABLE IF NOT EXISTS notification_reads (
                id INT AUTO_INCREMENT PRIMARY KEY,
                notification_id INT NOT NULL,
                user_id INT NOT NULL,
                read_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_notification_user (notification_id, user_id),
                INDEX idx_user_id (user_id),
                CONSTRAINT fk_notification_reads_notification
                    FOREIGN KEY (notification_id) REFERENCES notifications(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }

    private function normalizeSelectionValues(mixed $values): array
    {
        if (!is_array($values)) {
            return [];
        }
        $normalized = [];
        foreach ($values as $value) {
            $v = trim((string) $value);
            if ($v !== '') {
                $normalized[] = $v;
            }
        }
        return array_values(array_unique($normalized));
    }

    private function insertNotificationRow(
        string $targetRole,
        ?int $targetUserId,
        ?string $targetBarangay,
        ?string $targetStaffClassification,
        ?string $subject,
        string $message,
        ?int $createdByUserId,
        ?string $createdByName
    ): void {
        $this->execute(
            'INSERT INTO notifications
                (target_role, target_user_id, target_barangay, target_staff_classification, subject, message, created_by_user_id, created_by_name)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $targetRole,
                $targetUserId,
                $targetBarangay,
                $targetStaffClassification,
                $subject,
                $message,
                $createdByUserId,
                $createdByName,
            ]
        );
    }

    public function sendAlert(): void
    {
        $body = $this->input();
        $message = trim((string) ($body['message'] ?? ''));
        $room = trim((string) ($body['room'] ?? ''));
        $subject = trim((string) ($body['subject'] ?? ''));
        $staffTargets = $this->normalizeSelectionValues($body['staffTargets'] ?? []);
        $barangayTargets = $this->normalizeSelectionValues($body['barangayTargets'] ?? []);

        if ($message === '' || $room === '') {
            $this->jsonResponse(['success' => false, 'error' => 'Message and room are required'], 400);
        }

        if (!in_array($room, ['staff', 'barangay'], true)) {
            $this->jsonResponse(['success' => false, 'error' => "Invalid room. Must be 'staff' or 'barangay'"], 400);
        }

        try {
            $this->ensureNotificationsTables();
            $sessionUser = $_SESSION['user'] ?? null;
            $createdByUserId = is_array($sessionUser) && isset($sessionUser['id']) ? (int) $sessionUser['id'] : null;
            $createdByName = is_array($sessionUser) ? (string) ($sessionUser['name'] ?? $sessionUser['email'] ?? 'Admin') : 'Admin';
            $subject = $subject !== '' ? $subject : null;

            $inserted = 0;

            if ($room === 'staff') {
                $targetUserIds = [];
                $targetClassifications = [];
                $sendToAllStaff = empty($staffTargets);

                foreach ($staffTargets as $target) {
                    if ($target === '__all_staff__') {
                        $sendToAllStaff = true;
                        continue;
                    }
                    if ($target === '__all_pdao__') {
                        $targetClassifications['PDAO'] = true;
                        continue;
                    }
                    if ($target === '__all_osca__') {
                        $targetClassifications['OSCA'] = true;
                        continue;
                    }
                    if (ctype_digit($target)) {
                        $targetUserIds[(int) $target] = true;
                    }
                }

                // If target tokens were provided but none mapped to a valid selector,
                // fall back to all staff so alerts are not silently dropped.
                if (!$sendToAllStaff && count($targetUserIds) === 0 && count($targetClassifications) === 0) {
                    $sendToAllStaff = true;
                }

                if ($sendToAllStaff) {
                    $this->insertNotificationRow('staff', null, null, null, $subject, $message, $createdByUserId, $createdByName);
                    $inserted += 1;
                } else {
                    foreach (array_keys($targetClassifications) as $classification) {
                        $this->insertNotificationRow('staff', null, null, $classification, $subject, $message, $createdByUserId, $createdByName);
                        $inserted += 1;
                    }
                    foreach (array_keys($targetUserIds) as $userId) {
                        $this->insertNotificationRow('staff', $userId, null, null, $subject, $message, $createdByUserId, $createdByName);
                        $inserted += 1;
                    }
                }
            }

            if ($room === 'barangay') {
                $sendToAllBarangays = empty($barangayTargets);
                $targetBarangays = [];
                foreach ($barangayTargets as $target) {
                    if ($target === '__all_barangay__') {
                        $sendToAllBarangays = true;
                        continue;
                    }
                    $targetBarangays[$target] = true;
                }

                // If tokens exist but no concrete barangay parsed, send to all barangays.
                if (!$sendToAllBarangays && count($targetBarangays) === 0) {
                    $sendToAllBarangays = true;
                }

                if ($sendToAllBarangays) {
                    $this->insertNotificationRow('barangay', null, null, null, $subject, $message, $createdByUserId, $createdByName);
                    $inserted += 1;
                } else {
                    foreach (array_keys($targetBarangays) as $barangayName) {
                        $this->insertNotificationRow('barangay', null, $barangayName, null, $subject, $message, $createdByUserId, $createdByName);
                        $inserted += 1;
                    }
                }
            }

            if ($inserted <= 0) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'No recipients resolved for this alert.',
                ], 400);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Alert sent successfully.',
                'notifications_created' => $inserted,
            ]);
        } catch (Throwable $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to save alert notification.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getNotifications(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if (!is_array($sessionUser)) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $this->ensureNotificationsTables();
            $userId = (int) ($sessionUser['id'] ?? 0);
            $role = strtolower((string) ($sessionUser['role'] ?? ''));
            $isBarangay = $role === 'barangay';
            $targetRole = $isBarangay ? 'barangay' : 'staff';

            $params = [$userId, $targetRole];
            $filters = ['n.target_role = ?'];
            if ($isBarangay) {
                $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
                if ($scope === null || trim((string) ($scope['barangay'] ?? '')) === '') {
                    $this->jsonResponse(['success' => true, 'data' => []]);
                }
                $barangayName = trim((string) $scope['barangay']);
                $filters[] = '(n.target_user_id IS NULL OR n.target_user_id = ?)';
                $params[] = $userId;
                $filters[] = '(n.target_barangay IS NULL OR n.target_barangay = ?)';
                $params[] = $barangayName;
            } else {
                $staffClassification = strtoupper(trim((string) ($sessionUser['staff_classification'] ?? '')));
                $filters[] = '(n.target_user_id IS NULL OR n.target_user_id = ?)';
                $params[] = $userId;
                if ($staffClassification !== '') {
                    $filters[] = '(n.target_staff_classification IS NULL OR UPPER(n.target_staff_classification) = ?)';
                    $params[] = $staffClassification;
                } else {
                    $filters[] = 'n.target_staff_classification IS NULL';
                }
            }

            $sql = 'SELECT
                        n.id,
                        n.subject,
                        n.message,
                        n.created_by_name,
                        n.created_at,
                        CASE WHEN nr.id IS NULL THEN 0 ELSE 1 END AS is_read
                    FROM notifications n
                    LEFT JOIN notification_reads nr
                        ON nr.notification_id = n.id AND nr.user_id = ?
                    WHERE ' . implode(' AND ', $filters) . '
                    ORDER BY n.created_at DESC
                    LIMIT 100';

            $rows = $this->queryAll($sql, $params);
            $data = [];
            foreach ($rows as $row) {
                $data[] = [
                    'id' => (int) ($row['id'] ?? 0),
                    'subject' => (string) ($row['subject'] ?? ''),
                    'message' => (string) ($row['message'] ?? ''),
                    'from' => (string) ($row['created_by_name'] ?? 'Admin'),
                    'created_at' => (string) ($row['created_at'] ?? ''),
                    'is_read' => (int) ($row['is_read'] ?? 0) === 1,
                ];
            }

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load notifications'], 500);
        }
    }

    public function markNotificationRead(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if (!is_array($sessionUser)) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $body = $this->input();
        $notificationId = isset($body['notification_id']) ? (int) $body['notification_id'] : 0;
        $markAll = !empty($body['all']);
        $userId = (int) ($sessionUser['id'] ?? 0);

        if ($userId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid user'], 400);
        }

        try {
            $this->ensureNotificationsTables();
            if ($markAll) {
                $list = $this->queryAll('SELECT id FROM notifications ORDER BY id DESC LIMIT 500');
                foreach ($list as $row) {
                    $nid = (int) ($row['id'] ?? 0);
                    if ($nid <= 0) {
                        continue;
                    }
                    $this->execute(
                        'INSERT IGNORE INTO notification_reads (notification_id, user_id, read_at) VALUES (?, ?, NOW())',
                        [$nid, $userId]
                    );
                }
                $this->jsonResponse(['success' => true]);
            }

            if ($notificationId <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'notification_id is required'], 400);
            }

            $this->execute(
                'INSERT IGNORE INTO notification_reads (notification_id, user_id, read_at) VALUES (?, ?, NOW())',
                [$notificationId, $userId]
            );
            $this->jsonResponse(['success' => true]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to mark notification as read'], 500);
        }
    }

    public function renderBarangay(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if (!is_array($sessionUser)) {
            $this->redirect('/');
        }

        if (($sessionUser['role'] ?? '') !== 'Barangay') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }

        $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
        $this->render('barangay/dashboard', [
            'title' => 'Barangay Dashboard',
            'user' => $sessionUser,
            'assignedBarangayName' => $scope['barangay'] ?? '',
        ]);
    }

    public function renderBarangaySeniorDashboard(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if (!is_array($sessionUser)) {
            $this->redirect('/');
        }

        if (($sessionUser['role'] ?? '') !== 'Barangay') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }

        $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
        if ($scope === null) {
            http_response_code(403);
            echo 'This account is not linked to a barangay. Contact an administrator.';
            exit;
        }

        $this->render('barangay/senior_dashboard', [
            'title' => 'Barangay — Senior Citizen Analytics',
            'user' => $sessionUser,
            'assignedBarangayName' => $scope['barangay'] ?? '',
        ]);
    }

    public function renderBarangaySenior(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if (!is_array($sessionUser)) {
            $this->redirect('/');
        }

        if (($sessionUser['role'] ?? '') !== 'Barangay') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }

        $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
        if ($scope === null) {
            http_response_code(403);
            echo 'This account is not linked to a barangay. Contact an administrator.';
            exit;
        }

        $statusSql = "WHERE status <> 'Archived' AND barangay = ?";
        if (isset($_GET['status']) && $_GET['status'] === 'archived') {
            $statusSql = "WHERE status = 'Archived' AND barangay = ?";
        } elseif (isset($_GET['status']) && $_GET['status'] === 'all') {
            $statusSql = 'WHERE barangay = ?';
        }

        $rows = $this->queryAll(
            'SELECT id, last_name, first_name, middle_name, extension, barangay, purok, age, gender, status, created_at
             FROM senior_citizens ' . $statusSql . ' ORDER BY created_at DESC',
            [$scope['barangay']]
        );

        $barangays = $this->fetchBarangays();
        $puroks = [];
        if (is_array($barangays) && isset($barangays[$scope['barangay']])) {
            $puroks = $barangays[$scope['barangay']];
        }

        $this->render('barangay/senior_list', [
            'title' => 'Barangay Senior',
            'user' => $sessionUser,
            'assignedBarangayName' => $scope['barangay'],
            'seniors' => $rows,
            'puroks' => $puroks,
        ]);
    }

    public function renderBarangayPwd(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;
        if (!is_array($sessionUser)) {
            $this->redirect('/');
        }

        if (($sessionUser['role'] ?? '') !== 'Barangay') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }

        $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
        if ($scope === null) {
            http_response_code(403);
            echo 'This account is not linked to a barangay. Contact an administrator.';
            exit;
        }

        $statusSql = "WHERE status <> 'Archived' AND barangay = ?";
        if (isset($_GET['status']) && $_GET['status'] === 'archived') {
            $statusSql = "WHERE status = 'Archived' AND barangay = ?";
        } elseif (isset($_GET['status']) && $_GET['status'] === 'all') {
            $statusSql = 'WHERE barangay = ?';
        }

        $rows = $this->queryAll(
            'SELECT id, last_name, first_name, middle_name, barangay, purok, age, gender, status, created_at
             FROM pwd ' . $statusSql . ' ORDER BY created_at DESC',
            [$scope['barangay']]
        );

        $barangays = $this->fetchBarangays();
        $puroks = [];
        if (is_array($barangays) && isset($barangays[$scope['barangay']])) {
            $puroks = $barangays[$scope['barangay']];
        }

        $this->render('barangay/pwd_list', [
            'title' => 'Barangay PWD',
            'user' => $sessionUser,
            'assignedBarangayName' => $scope['barangay'],
            'pwds' => $rows,
            'puroks' => $puroks,
        ]);
    }

    public function getOscaAnalytics(): void
    {
        try {
            $groupBy = (isset($_GET['groupBy']) && strtolower((string) $_GET['groupBy']) === 'purok') ? 'purok' : 'barangay';
            $groupField = $groupBy === 'purok' ? 'purok' : 'barangay';

            $filters = ["status <> 'Archived'"];
            $params = [];

            $sessionUser = $_SESSION['user'] ?? null;
            if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
                $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
                if ($scope === null) {
                    $this->jsonResponse(['success' => false, 'message' => 'Barangay account has no assigned barangay.'], 403);
                }
                $filters[] = 'barangay = ?';
                $params[] = $scope['barangay'];
            }

            $sql = sprintf(
                'SELECT %s AS name,
                        COUNT(*) AS total,
                        SUM(CASE WHEN gender = \'Male\' THEN 1 ELSE 0 END) AS male,
                        SUM(CASE WHEN gender = \'Female\' THEN 1 ELSE 0 END) AS female
                 FROM senior_citizens
                 WHERE %s
                 GROUP BY %s
                 ORDER BY %s ASC',
                $groupField,
                implode(' AND ', $filters),
                $groupField,
                $groupField
            );

            $rows = $this->queryAll($sql, $params);
            $data = [];
            $i = 1;
            foreach ($rows as $row) {
                if (!isset($row['name']) || trim((string) $row['name']) === '') {
                    continue;
                }
                $data[] = [
                    'id' => $i++,
                    'name' => $row['name'],
                    'total' => (int) $row['total'],
                    'male' => (int) $row['male'],
                    'female' => (int) $row['female'],
                ];
            }

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load OSCA analytics'], 500);
        }
    }

    public function getPdaoAnalytics(): void
    {
        try {
            $groupBy = (isset($_GET['groupBy']) && strtolower((string) $_GET['groupBy']) === 'purok') ? 'purok' : 'barangay';
            $groupField = $groupBy === 'purok' ? 'purok' : 'barangay';

            $filters = ["status = 'Active'"];
            $params = [];

            $sessionUser = $_SESSION['user'] ?? null;
            if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
                $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
                if ($scope) {
                    $filters[] = 'barangay = ?';
                    $params[] = $scope['barangay'];
                }
            }

            $sql = sprintf(
                'SELECT %s AS name, COUNT(*) AS total,
                        SUM(CASE WHEN gender = \'Male\' THEN 1 ELSE 0 END) AS male,
                        SUM(CASE WHEN gender = \'Female\' THEN 1 ELSE 0 END) AS female
                 FROM pwd
                 WHERE %s
                 GROUP BY %s
                 ORDER BY %s ASC',
                $groupField,
                implode(' AND ', $filters),
                $groupField,
                $groupField
            );

            $rows = $this->queryAll($sql, $params);
            $data = [];
            $i = 1;
            foreach ($rows as $row) {
                if (!isset($row['name']) || trim((string) $row['name']) === '') {
                    continue;
                }
                $data[] = [
                    'id' => $i++,
                    'name' => $row['name'],
                    'total' => (int) $row['total'],
                    'male' => (int) $row['male'],
                    'female' => (int) $row['female'],
                ];
            }

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load PDAO analytics'], 500);
        }
    }

    public function getAllPwds(): void
    {
        try {
            $filters = [];
            $params = [];

            $sessionUser = $_SESSION['user'] ?? null;
            if (is_array($sessionUser) && (($sessionUser['role'] ?? '') === 'Barangay')) {
                $scope = $this->fetchBarangayScopeForSessionUser($sessionUser);
                if ($scope === null) {
                    $this->jsonResponse(['success' => false, 'error' => 'Barangay account has no assigned barangay.'], 403);
                }
                $filters[] = 'barangay = ?';
                $params[] = $scope['barangay'];
            }

            if (($_GET['status'] ?? '') !== 'all') {
                $filters[] = "status <> 'Archived'";
            }

            $month = isset($_GET['month']) ? (int) $_GET['month'] : 0;
            $year = isset($_GET['year']) ? (int) $_GET['year'] : 0;
            if ($month >= 1 && $month <= 12) {
                $filters[] = 'MONTH(created_at) = ?';
                $params[] = $month;
                if ($year >= 2000) {
                    $filters[] = 'YEAR(created_at) = ?';
                    $params[] = $year;
                }
            } elseif ($year >= 2000) {
                $filters[] = 'YEAR(created_at) = ?';
                $params[] = $year;
            }

            $whereSql = count($filters) ? ('WHERE ' . implode(' AND ', $filters)) : '';
            $rows = $this->queryAll("SELECT * FROM pwd {$whereSql} ORDER BY created_at DESC", $params);

            $this->jsonResponse(['success' => true, 'pwds' => $rows]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to fetch PWD data'], 500);
        }
    }

    public function getPwdMapData(): void
    {
        if (!$this->db) {
            $this->jsonResponse(['success' => false, 'message' => 'Database not connected'], 500);
            return;
        }
        try {
            $counts = $this->queryAll(
                "SELECT barangay AS name,
                        COUNT(*) AS pwdCount,
                        SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount,
                        SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount
                 FROM pwd
                 WHERE COALESCE(status, 'Active') <> 'Archived'
                 GROUP BY barangay"
            );

            $disabilityRows = $this->queryAll(
                "SELECT p.barangay AS name, d.disability AS type, COUNT(*) AS count
                 FROM pwd p
                 INNER JOIN pwd_disabilities d ON d.pwd_id = p.id
                 WHERE COALESCE(p.status, 'Active') <> 'Archived'
                   AND d.disability IS NOT NULL AND d.disability <> ''
                 GROUP BY p.barangay, d.disability"
            );

            $countMap = [];
            foreach ($counts as $row) {
                $countMap[(string) $row['name']] = [
                    'pwdCount' => (int) $row['pwdCount'],
                    'maleCount' => (int) $row['maleCount'],
                    'femaleCount' => (int) $row['femaleCount'],
                ];
            }

            $disabilityMap = [];
            foreach ($disabilityRows as $row) {
                $key = (string) $row['name'];
                if (!isset($disabilityMap[$key])) {
                    $disabilityMap[$key] = [];
                }
                $disabilityMap[$key][] = [
                    'type' => $row['type'],
                    'count' => (int) $row['count'],
                ];
            }

            foreach ($disabilityMap as &$list) {
                usort($list, static fn(array $a, array $b): int => $b['count'] <=> $a['count']);
            }
            unset($list);

            $result = [];
            foreach ($this->getBarangayCentroids() as $barangay) {
                $name = $barangay['name'];
                $stats = $countMap[$name] ?? ['pwdCount' => 0, 'maleCount' => 0, 'femaleCount' => 0];
                $result[] = [
                    'name' => $name,
                    'lat' => $barangay['lat'],
                    'lon' => $barangay['lon'],
                    'population' => 0,
                    'pwdCount' => $stats['pwdCount'],
                    'maleCount' => $stats['maleCount'],
                    'femaleCount' => $stats['femaleCount'],
                    'disabilities' => $disabilityMap[$name] ?? [],
                ];
            }

            $this->jsonResponse(['success' => true, 'data' => $result]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load PWD map data'], 500);
        }
    }

    public function getSeniorMapData(): void
    {
        try {
            $counts = $this->queryAll(
                "SELECT barangay AS name,
                        COUNT(*) AS seniorCount,
                        SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS maleCount,
                        SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS femaleCount
                 FROM senior_citizens
                 WHERE COALESCE(status, 'Active') <> 'Archived'
                 GROUP BY barangay"
            );

            $countMap = [];
            foreach ($counts as $row) {
                $countMap[(string) $row['name']] = [
                    'seniorCount' => (int) $row['seniorCount'],
                    'maleCount' => (int) $row['maleCount'],
                    'femaleCount' => (int) $row['femaleCount'],
                ];
            }

            $result = [];
            foreach ($this->getBarangayCentroids() as $barangay) {
                $name = $barangay['name'];
                $stats = $countMap[$name] ?? ['seniorCount' => 0, 'maleCount' => 0, 'femaleCount' => 0];

                $result[] = [
                    'name' => $name,
                    'lat' => $barangay['lat'],
                    'lon' => $barangay['lon'],
                    'population' => 0,
                    'seniorCount' => $stats['seniorCount'],
                    'maleCount' => $stats['maleCount'],
                    'femaleCount' => $stats['femaleCount'],
                ];
            }

            $this->jsonResponse(['success' => true, 'data' => $result]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to load senior map data'], 500);
        }
    }

    // The full JS controller also includes EJS rendering, PDF generation, and SMS gateway endpoints.
    // Keep method placeholders here during migration so route names can remain stable.
    public function notImplemented(string $methodName): void
    {
        $this->jsonResponse([
            'success' => false,
            'message' => $methodName . ' is not yet ported to PHP.',
        ], 501);
    }

    public function getUserEditLogs(): void
    {
        try {
            $user = $_GET['user'] ?? '';
            $hours = isset($_GET['hours']) ? (int) $_GET['hours'] : 24;
            
            if (empty($user)) {
                $this->jsonResponse(['success' => false, 'message' => 'User identifier is required'], 400);
            }

            // Calculate the timestamp for the last X hours
            $since = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));

            // Get PWD edit logs
            $pwdLogs = $this->queryAll(
                'SELECT \'pwd\' AS record_type, l.id, l.pwd_id AS record_id, l.field, l.old_value, l.new_value, l.edited_by, l.edited_at,
                        TRIM(CONCAT(IFNULL(p.first_name, ""), " ", IFNULL(p.middle_name, ""), " ", IFNULL(p.last_name, ""))) AS record_name
                 FROM pwd_edit_logs l
                 LEFT JOIN pwd p ON p.id = l.pwd_id
                 WHERE l.edited_by = ? AND l.edited_at >= ?
                 ORDER BY l.edited_at DESC',
                [$user, $since]
            );

            // Get Senior edit logs
            $seniorLogs = $this->queryAll(
                'SELECT \'senior\' AS record_type, l.id, l.senior_id AS record_id, l.field, l.old_value, l.new_value, l.edited_by, l.edited_at,
                        TRIM(CONCAT(IFNULL(s.first_name, ""), " ", IFNULL(s.middle_name, ""), " ", IFNULL(s.last_name, ""))) AS record_name
                 FROM senior_edit_logs l
                 LEFT JOIN senior_citizens s ON s.id = l.senior_id
                 WHERE l.edited_by = ? AND l.edited_at >= ?
                 ORDER BY l.edited_at DESC',
                [$user, $since]
            );

            // Combine and sort by edited_at
            $allLogs = array_merge($pwdLogs, $seniorLogs);
            usort($allLogs, function($a, $b) {
                return strtotime($b['edited_at']) - strtotime($a['edited_at']);
            });

            $this->jsonResponse(['success' => true, 'logs' => $allLogs]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to fetch user edit logs'], 500);
        }
    }

    public function getUserActivities(): void
    {
        try {
            $user = $_GET['user'] ?? '';
            $hours = isset($_GET['hours']) ? (int) $_GET['hours'] : 24;
            
            if (empty($user)) {
                $this->jsonResponse(['success' => false, 'message' => 'User identifier is required'], 400);
            }

            // Calculate the timestamp for the last X hours
            $since = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));

            try {
                // Try to get activities by user email first
                $activities = $this->queryAll(
                    'SELECT ua.id, ua.user_id, ua.activity_type, ua.activity_description, ua.created_at, u.email, u.name
                     FROM user_activities ua
                     LEFT JOIN users u ON u.id = ua.user_id
                     WHERE u.email = ? AND ua.created_at >= ?
                     ORDER BY ua.created_at DESC',
                    [$user, $since]
                );

                // If no activities found by email, try by user_id (for login logs)
                if (empty($activities) && is_numeric($user)) {
                    $activities = $this->queryAll(
                        'SELECT ua.id, ua.user_id, ua.activity_type, ua.activity_description, ua.created_at, u.email, u.name
                         FROM user_activities ua
                         LEFT JOIN users u ON u.id = ua.user_id
                         WHERE ua.user_id = ? AND ua.created_at >= ?
                         ORDER BY ua.created_at DESC',
                        [(int)$user, $since]
                    );
                }
            } catch (Throwable $e) {
                // If table doesn't exist yet, return empty array
                $activities = [];
            }

            $this->jsonResponse(['success' => true, 'activities' => $activities]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to fetch user activities'], 500);
        }
    }
}
