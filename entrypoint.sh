#!/bin/bash

set -e

echo "🔧 Iniciando Laravel..."

if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✅ .env criado a partir de .env.example"
fi

if [ ! -d "vendor" ]; then
    composer install --no-interaction --optimize-autoloader
    echo "✅ Dependências instaladas"
fi

if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate
    echo "🔑 Chave da aplicação gerada"
fi

if [ ! -f "database/database.sqlite" ]; then
    mkdir -p database
    touch database/database.sqlite
    echo "🗃️ Banco SQLite criado"
fi

php artisan migrate --force
chmod -R 775 storage bootstrap/cache

php artisan serve --host=0.0.0.0 --port=8000
echo "🚀 Servidor Laravel iniciado em http://localhost:8000"
