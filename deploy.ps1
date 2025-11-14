# Script de deployment automatizado para GitHub
# Uso: .\deploy.ps1 "Mensaje del commit"

param(
    [string]$mensaje = "Actualización de código",
    [string]$rama = "master"
)

Write-Host "🚀 Iniciando proceso de carga a GitHub..." -ForegroundColor Green
Write-Host "📝 Mensaje: $mensaje" -ForegroundColor Cyan
Write-Host "🌿 Rama: $rama" -ForegroundColor Cyan

# 1. Agregar todos los cambios
Write-Host "`n📦 Agregando cambios..." -ForegroundColor Yellow
git add .
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Error al agregar cambios" -ForegroundColor Red
    exit 1
}

# 2. Crear commit
Write-Host "💾 Creando commit..." -ForegroundColor Yellow
git commit -m "$mensaje"
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  No hay cambios para hacer commit" -ForegroundColor Yellow
    exit 0
}

# 3. Push a GitHub
Write-Host "📤 Subiendo a GitHub..." -ForegroundColor Yellow
git push -u origin $rama
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Error al hacer push. Verifica tu conexión a internet y credenciales" -ForegroundColor Red
    exit 1
}

Write-Host "`n✅ ¡Carga exitosa!" -ForegroundColor Green
Write-Host "📍 URL: https://github.com/natanaelbarrera9-maker/SWGPI" -ForegroundColor Cyan
