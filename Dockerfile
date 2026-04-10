# Stage 1: Build stage
FROM php:8.2-fpm-alpine

# Install library OS & dependencies
RUN apk add --no-cache \
    git \
    curl \
    bash \
    libpng-dev \
    libxml2-dev \
    icu-dev \
    oniguruma-dev \
    zlib-dev \
    libzip-dev \
    nginx \
    supervisor

# Install PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql mbstring gd intl zip bcmath opcache

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application source
COPY . .

# Install dependencies and optimize Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev && \
    chown -R www-data:www-data storage bootstrap/cache public && \
    chmod -R 775 storage bootstrap/cache public

# Fix Nginx runtime permissions
RUN mkdir -p /var/lib/nginx/tmp /var/log/nginx && \
    chown -R www-data:www-data /var/lib/nginx /var/log/nginx /run

# Configuration for Nginx and PHP-FPM
RUN printf "user www-data;\n\
worker_processes auto;\n\
pid /run/nginx.pid;\n\
events { worker_connections 768; }\n\
http {\n\
    include /etc/nginx/mime.types;\n\
    default_type application/octet-stream;\n\
    server {\n\
        listen 80;\n\
        index index.php index.html;\n\
        root /var/www/html/public;\n\
        location /storage/ {\n\
            alias /var/www/html/storage/app/public/;\n\
            autoindex off;\n\
        }\n\
        location / {\n\
            try_files \$uri \$uri/ /index.php?\$query_string;\n\
        }\n\
        location ~ \.php$ {\n\
            fastcgi_split_path_info ^(.+\.php)(/.+)$;\n\
            fastcgi_pass 127.0.0.1:9000;\n\
            fastcgi_index index.php;\n\
            include fastcgi_params;\n\
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n\
            fastcgi_param PATH_INFO \$fastcgi_path_info;\n\
        }\n\
    }\n\
}\n" > /etc/nginx/nginx.conf

# Entrypoint script
RUN printf "#!/bin/bash\n\
if [ ! -f .env ]; then \n\
    cp .env.example .env && \n\
    php artisan key:generate --force \n\
fi\n\
\n\
# Fix permissions at runtime for storage AND public\n\
chown -R www-data:www-data /var/www/html/storage /var/www/html/public\n\
chmod -R 755 /var/www/html/storage /var/www/html/public\n\
\n\
# Re-link storage correctly\n\
rm -rf public/storage\n\
php artisan storage:link --force\n\
\n\
php artisan optimize:clear\n\
\n\
# Start PHP-FPM and Nginx\n\
php-fpm -D\n\
nginx -g 'daemon off;'\n" > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]