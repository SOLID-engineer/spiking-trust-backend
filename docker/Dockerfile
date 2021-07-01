FROM php:8.0.5
RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev supervisor
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring && docker-php-ext-enable pdo_mysql
WORKDIR /app
COPY . /app
RUN composer install

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD php artisan key:generate && php artisan passport:install && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=80 && /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
EXPOSE 80
