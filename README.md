## Laravel Setup

* Clone this repo.
* Run ```composer install```
* Copy ```cp .env.example .env```
* Set environment variables in the .env file.
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:GoZmLGF3Y3j48KbnKh3xJwFL7hDt51xM7U0oayDPhfA=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=spiking-trust  
DB_USERNAME=root  
DB_PASSWORD=root
```
* Run ```php artisan key:generate```
* Run ```php artisan migrate:refresh```
* Creating a personal access client.
```php artisan passport:client --personal```
* (Optional) run Laravel db seed.
```php artisan db:seed```
