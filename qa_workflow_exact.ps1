$ErrorActionPreference='Stop'
Set-Location 'c:\xampp\htdocs\sws\Final_Caps'
$mysql='C:\xampp\mysql\bin\mysql.exe'
$php='C:\xampp\php\php.exe'

# 1) MySQL reachability
try {
  & $mysql -uroot -N -s -e "SELECT 1" | Out-Null
  Write-Host 'MYSQL_OK=1'
} catch {
  Write-Host ('MYSQL_OK=0 ERROR=' + $_.Exception.Message)
  exit 1
}

# 2) Detect barangay row
$barangayRow = & $mysql -uroot -N -s -D ebmag -e "SELECT id, barangay FROM barangays ORDER BY id ASC LIMIT 1;"
if (-not $barangayRow) { Write-Host 'BARANGAY_ROW=NONE'; exit 1 }
$parts = $barangayRow -split "`t"
$barangayId = [int]$parts[0]
$barangayName = $parts[1]
Write-Host ('BARANGAY_ID=' + $barangayId + ' BARANGAY_NAME=' + $barangayName)

# 3) Upsert barangay QA user
$hash = & $php -r "echo password_hash('QaPass123!', PASSWORD_BCRYPT);"
$sql = @"
INSERT INTO users (name, email, password, role, status, barangay_id, staff_classification, is_verified)
VALUES ('QA Barangay','qa.barangay@example.com','$hash','Barangay','Active',$barangayId,NULL,1)
ON DUPLICATE KEY UPDATE
  name=VALUES(name),
  password=VALUES(password),
  role='Barangay',
  status='Active',
  barangay_id=VALUES(barangay_id),
  staff_classification=NULL,
  is_verified=1;
SELECT id,email,role,status,barangay_id,is_verified FROM users WHERE email='qa.barangay@example.com';
"@
& $mysql -uroot -D ebmag -e $sql

# 4) Detect existing IDs (prefer active)
$pwdId = & $mysql -uroot -N -s -D ebmag -e "SELECT id FROM pwd WHERE COALESCE(status,'Active') <> 'Archived' ORDER BY id ASC LIMIT 1;"
if (-not $pwdId) { $pwdId = & $mysql -uroot -N -s -D ebmag -e "SELECT id FROM pwd ORDER BY id ASC LIMIT 1;" }
$seniorId = & $mysql -uroot -N -s -D ebmag -e "SELECT id FROM senior_citizens WHERE COALESCE(status,'Active') <> 'Archived' ORDER BY id ASC LIMIT 1;"
if (-not $seniorId) { $seniorId = & $mysql -uroot -N -s -D ebmag -e "SELECT id FROM senior_citizens ORDER BY id ASC LIMIT 1;" }
Write-Host ('PWD_ID=' + $pwdId + ' SENIOR_ID=' + $seniorId)
if (-not $pwdId -or -not $seniorId) { Write-Host 'MISSING_IDS=1'; exit 1 }

# 5) Start server
$server = Start-Process -FilePath $php -ArgumentList '-S','127.0.0.1:8107','index.php' -WorkingDirectory (Get-Location) -PassThru
try {
  $ready=$false
  for($i=0;$i -lt 30;$i++){
    $probe = (& curl.exe -s -o NUL -w "%{http_code}" 'http://127.0.0.1:8107/' 2>$null)
    if($probe -ne '000'){ $ready=$true; break }
  }
  if(-not $ready){ throw 'Server not reachable' }

  # 6) Login
  $cookie='qa_barangay_cookie.txt'
  if(Test-Path $cookie){ Remove-Item $cookie -Force }
  $loginHdr = Join-Path $env:TEMP 'qa_barangay_login_hdr.txt'
  $loginBody = Join-Path $env:TEMP 'qa_barangay_login_body.txt'
  $loginCode = & curl.exe -s -o $loginBody -D $loginHdr -c $cookie -X POST --data-urlencode 'email=qa.barangay@example.com' --data-urlencode 'password=QaPass123!' 'http://127.0.0.1:8107/login' -w "%{http_code}"
  $loc = (Get-Content $loginHdr | Where-Object { $_ -match '^Location:' } | Select-Object -Last 1)
  Write-Host ('LOGIN_STATUS=' + $loginCode + ' LOGIN_LOCATION=' + ($loc -replace '^Location:\s*',''))

  # 7) Barangay suite
  $eps = @('/barangay','/barangay-senior-dashboard','/barangay-senior','/barangay-pwd','/api/analytics/osca','/api/analytics/pdao','/api/pwds','/api/senior-citizens-for-report')
  $results = @()
  foreach($ep in $eps){
    $tmp = Join-Path $env:TEMP ([guid]::NewGuid().ToString()+'.txt')
    $meta = & curl.exe -s -o $tmp -b $cookie -D - ("http://127.0.0.1:8107"+$ep) -w "`nCURL_STATUS:%{http_code}`nCURL_TYPE:%{content_type}`n"
    $status = (($meta -split "`n") | Where-Object { $_ -like 'CURL_STATUS:*' } | Select-Object -Last 1) -replace 'CURL_STATUS:',''
    $ctype = (($meta -split "`n") | Where-Object { $_ -like 'CURL_TYPE:*' } | Select-Object -Last 1) -replace 'CURL_TYPE:',''
    $first = '<empty>'
    if(Test-Path $tmp){
      $firstLine = Get-Content $tmp -TotalCount 1
      if($firstLine){ $first = $firstLine }
      Remove-Item $tmp -Force
    }
    $results += [pscustomobject]@{Endpoint=$ep;Status=[int]$status;Type=$ctype;FirstLine=$first}
  }
  Write-Host '---BARANGAY_SUITE---'
  $results | Format-Table -AutoSize

  # 8) PDF endpoints
  $pdfResults = @()
  foreach($ep in @("/pwd/$pwdId/application-pdf","/senior/$seniorId/application-pdf")){
    $tmp = Join-Path $env:TEMP ([guid]::NewGuid().ToString()+'.pdf')
    $meta = & curl.exe -s -o $tmp -b $cookie -D - ("http://127.0.0.1:8107"+$ep) -w "`nCURL_STATUS:%{http_code}`nCURL_TYPE:%{content_type}`nCURL_SIZE:%{size_download}`n"
    $status = (($meta -split "`n") | Where-Object { $_ -like 'CURL_STATUS:*' } | Select-Object -Last 1) -replace 'CURL_STATUS:',''
    $ctype = (($meta -split "`n") | Where-Object { $_ -like 'CURL_TYPE:*' } | Select-Object -Last 1) -replace 'CURL_TYPE:',''
    $size = (($meta -split "`n") | Where-Object { $_ -like 'CURL_SIZE:*' } | Select-Object -Last 1) -replace 'CURL_SIZE:',''
    $pdfResults += [pscustomobject]@{Endpoint=$ep;Status=[int]$status;Type=$ctype;Size=$size}
    if(Test-Path $tmp){ Remove-Item $tmp -Force }
  }
  Write-Host '---PDF_RESULTS---'
  $pdfResults | Format-Table -AutoSize

  # 10) summary
  $passBarangay = ($results | Where-Object { $_.Status -ge 200 -and $_.Status -lt 300 }).Count
  $failBarangay = $results.Count - $passBarangay
  $passPdf = ($pdfResults | Where-Object { $_.Status -eq 200 -and $_.Type -like 'application/pdf*' }).Count
  $failPdf = $pdfResults.Count - $passPdf
  Write-Host ('SUMMARY BARANGAY_PASS=' + $passBarangay + ' BARANGAY_FAIL=' + $failBarangay + ' PDF_PASS=' + $passPdf + ' PDF_FAIL=' + $failPdf)

  if($failBarangay -gt 0){
    Write-Host '---BARANGAY_FAILS---'
    $results | Where-Object { -not ($_.Status -ge 200 -and $_.Status -lt 300) } | Format-Table -AutoSize
  }
  if($failPdf -gt 0){
    Write-Host '---PDF_FAILS---'
    $pdfResults | Where-Object { -not ($_.Status -eq 200 -and $_.Type -like 'application/pdf*') } | Format-Table -AutoSize
  }
}
finally {
  if($server -and (Get-Process -Id $server.Id -ErrorAction SilentlyContinue)){
    Stop-Process -Id $server.Id -Force
  }
  Write-Host ('SERVER_STOPPED=' + $server.Id)
}
