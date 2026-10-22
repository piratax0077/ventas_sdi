param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$ArtisanArguments
)
$runtimePhp = Join-Path $PSScriptRoot '.runtime/php/php.exe'
if (-not (Test-Path -LiteralPath $runtimePhp)) {
    throw 'No se encontro PHP local en .runtime/php/php.exe.'
}
Push-Location $PSScriptRoot
try {
    & $runtimePhp artisan @ArtisanArguments
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
