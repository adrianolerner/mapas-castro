Push-Location $PSScriptRoot
try {
    docker compose exec db psql -U mapas -d mapas
} finally {
    Pop-Location
}

