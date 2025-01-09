FROM php:8.2-cli as dependencies

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    libpq-dev 
    
RUN docker-php-ext-install intl pdo pdo_pgsql pgsql

RUN apt-get install -y postgresql postgresql-contrib

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

FROM dependencies as code

WORKDIR /app

COPY . .

COPY .env.docker .env

FROM code as installation

RUN composer install

FROM installation

EXPOSE 8000

CMD php bin/console doctrine:migrations:migrate && php bin/console messenger:consume async & symfony server:start --allow-all-ip