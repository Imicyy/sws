<?php
try {
    $db = new PDO('mysql:host=db;dbname=ebmag', 'root', 'charlesmonserateebmagcaresvulnerablesector');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking login_logs table...\n";
    $cols = $db->query("DESCRIBE login_logs")->fetchAll(PDO::FETCH_COLUMN);
    print_r($cols);
    
    $needed = ['ip_address', 'device_info', 'location'];
    foreach ($needed as $n) {
        if (!in_array($n, $cols)) {
            echo "Adding column $n...\n";
            $db->exec("ALTER TABLE login_logs ADD COLUMN $n VARCHAR(255) NULL");
        }
    }
    
    echo "Backfilling existing logs...\n";
    $db->exec("UPDATE login_logs SET ip_address = '127.0.0.1' WHERE ip_address IS NULL");
    $db->exec("UPDATE login_logs SET device_info = 'Legacy System Record' WHERE device_info IS NULL");
    $db->exec("UPDATE login_logs SET location = 'Silay City, PH' WHERE location IS NULL");
    
    echo "Done.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
