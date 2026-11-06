Push-Location $PSScriptRoot
try {
    docker compose exec mapas bash
} finally {
    Pop-Location
}

