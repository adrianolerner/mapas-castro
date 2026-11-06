param(
    [switch]$Build,
    [switch]$Down,
    [switch]$Detach
)

$ErrorActionPreference = "Stop"
Push-Location $PSScriptRoot

try {
    $dirs = @(
        "docker-data/assets",
        "docker-data/logs",
        "docker-data/private-files",
        "docker-data/public-files",
        "docker-data/saas-files"
    )
    foreach ($d in $dirs) {
        if (-not (Test-Path $d)) {
            New-Item -ItemType Directory -Path $d -Force | Out-Null
        }
    }
    if (-not (Test-Path "docker-data/logs/app.log")) {
        New-Item -ItemType File -Path "docker-data/logs/app.log" -Force | Out-Null
    }

    if ($Build) {
        docker compose build --pull
    }

    if ($Down) {
        docker compose down
    }

    if ($Detach) {
        docker compose up -d
        Write-Host "`nAmbiente de desenvolvimento iniciado em background!" -ForegroundColor Green
        Write-Host "Acesse: http://localhost/  |  MailHog: http://localhost:8025/" -ForegroundColor Cyan
    } else {
        docker compose run --service-ports mapas
        docker compose down
    }
} finally {
    Pop-Location
}

