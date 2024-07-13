FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app

COPY . .

CMD ["php", "test.php"]