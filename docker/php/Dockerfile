FROM php:8.1-fpm

# Устанавливаем рабочую директорию
WORKDIR /var/www/wallet

# Устанавливаем зависимости
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql gd imagick

# Загружаем актуальную версию Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем содержимое текущего каталога в рабочую директорию
COPY . /var/www/wallet
