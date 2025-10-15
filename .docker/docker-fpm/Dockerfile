FROM php:8.4.5-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libicu-dev \
    libonig-dev \
    libssl-dev \
    gnupg2 \
    supervisor \
    procps \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath xml zip intl gd opcache

# Instala la extensión redis via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Copiar php.ini local (si tienes ajustes)
COPY ./.docker/php/local-php.ini /usr/local/etc/php/conf.d/local-php.ini

# Instalar Composer (más fiable: copia desde la imagen oficial)
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar composer files primero (cache layer)
COPY src/composer.json src/composer.lock ./

# Instalar dependencias composer (sin dev en imagen de prod; en dev puedes incluir --dev)
RUN composer install --no-interaction --prefer-dist --no-scripts --no-dev --optimize-autoloader || true

# Ahora copia el resto del código
COPY src/ ./

# Asegurar permisos para storage y cache
RUN chown -R appuser:appgroup /var/www/html/storage /var/www/html/bootstrap/cache || true \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true


# Crear directorios de logs y dar permisos
RUN mkdir -p /var/log/supervisord && chown -R appuser:appgroup /var/log/supervisord


# Copiar supervisord conf (si lo vas a usar para workers)
COPY ./.docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Exponer el puerto FPM (informativo)
EXPOSE 9000

# Copiar configuración de supervisord
COPY ./.docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# arrancar supervisord
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]