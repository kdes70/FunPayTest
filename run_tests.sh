#!/bin/bash

# Проверяем наличие .env файла
if [ ! -f .env ]; then
    echo ".env file not found. Please create it with necessary environment variables."
    exit 1
fi

# Собираем Docker-образ
docker-compose build

# Запускаем контейнеры
docker-compose up -d

# Ждем, пока базы данных будут готовы
echo "Waiting for databases to be ready..."
sleep 15

# Запускаем тесты
docker-compose run app php test.php

# Останавливаем контейнеры
docker-compose down