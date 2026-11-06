Push-Location $PSScriptRoot
try {
    docker compose logs -f --tail=100 $args
} finally {
    Pop-Location
}

