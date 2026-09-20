FROM php:8.2-cli

# Install system dependencies & Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Install PHP & Node dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

EXPOSE 8080

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
