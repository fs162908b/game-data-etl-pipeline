FROM php:8.2-fpm

# 安裝系統必要的工具 (zip, git 是為了讓 Composer 能運作)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl

# 安裝 PHP 連結 MySQL 的關鍵擴充元件 (pdo_mysql)
RUN docker-php-ext-install pdo_mysql gd

# 從官方映像檔把 Composer (PHP 的套件管理員) 抓過來用
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 設定程式碼在容器內的預設位置
WORKDIR /var/www