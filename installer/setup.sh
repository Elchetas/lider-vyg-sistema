#!/usr/bin/env bash
set -euo pipefail

APP_NAME=${1:-lider-vyg}

if ! command -v composer >/dev/null 2>&1; then
  echo "ERROR: necesitas Composer instalado." >&2
  exit 1
fi

if [ -d "$APP_NAME" ]; then
  echo "ERROR: ya existe la carpeta $APP_NAME" >&2
  exit 1
fi

echo "==> 1) Crear Laravel"
composer create-project laravel/laravel "$APP_NAME"
cd "$APP_NAME"

echo "==> 2) Instalar dependencias (PDF, Excel, Auth)"
composer require barryvdh/laravel-dompdf maatwebsite/excel
composer require laravel/breeze --dev
php artisan breeze:install blade

if command -v npm >/dev/null 2>&1; then
  npm install
  npm run build
else
  echo "AVISO: npm no está instalado. Instala Node.js para compilar los assets (login)."
fi

echo "==> 3) Copiar overlay (código del sistema)"
# Se asume que este script se ejecuta desde el ZIP extraído
# y que existe ../overlay con los archivos personalizados.
cp -R ../overlay/* .

echo "==> 4) Publicar config y storage"
php artisan vendor:publish --provider="Barryvdh\\DomPDF\\ServiceProvider" --tag=config || true
php artisan storage:link || true

echo "==> 5) Configurar .env"
if [ ! -f .env ]; then
  cp .env.example .env
fi
php artisan key:generate

echo "==> 6) Listo. Configura tu DB en .env y ejecuta migraciones"

cat <<'NEXT'

Siguientes comandos (en la carpeta del proyecto):
  1) Edita .env con tus datos MySQL
  2) php artisan migrate --seed
  3) php artisan serve --host=0.0.0.0 --port=8000

Usuarios por defecto:
  Admin: admin@lidervyg.pe / Admin123!
  Operador: operador@lidervyg.pe / Operador123!

NEXT
