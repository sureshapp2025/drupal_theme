FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y     software-properties-common     curl     git     unzip     ca-certificates     gnupg     nginx     supervisor     && add-apt-repository ppa:ondrej/php -y     && apt-get update && apt-get install -y     php8.3-fpm     php8.3-cli     php8.3-mysql     php8.3-xml     php8.3-gd     php8.3-curl     php8.3-mbstring     php8.3-zip     php8.3-opcache     php8.3-intl     php8.3-bcmath     php8.3-soap     && apt-get clean     && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php --     --install-dir=/usr/local/bin     --filename=composer

RUN sed -i 's|listen = .*|listen = 9000|' /etc/php/8.3/fpm/pool.d/www.conf

COPY php/php.ini /etc/php/8.3/fpm/conf.d/custom.ini
COPY nginx/default.conf /etc/nginx/sites-available/default

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader || true

RUN chown -R www-data:www-data /var/www/html

RUN printf "[supervisord]\nnodaemon=true\n\n[program:php-fpm]\ncommand=/usr/sbin/php-fpm8.3 -F\n\n[program:nginx]\ncommand=/usr/sbin/nginx -g 'daemon off;'\n" > /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord"]
