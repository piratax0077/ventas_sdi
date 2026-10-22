$runtimePhp = Join-Path $PSScriptRoot '.runtime/php/php.exe'
$previousMysqlTestMode = $env:VENTAS_MYSQL_TESTS
Push-Location $PSScriptRoot
try {
    & $runtimePhp tools/create-test-database.php
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
    $env:VENTAS_MYSQL_TESTS = '1'
    & $runtimePhp artisan test
    exit $LASTEXITCODE
} finally {
    $env:VENTAS_MYSQL_TESTS = $previousMysqlTestMode
    Pop-Location
}
