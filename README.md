## Quick start

1. Clone this repo.
2. Run composer install.
```composer install```
3. Clone .env.sample file. 
```cp .env.sample .env```
4. Set db environment variables in the .env file
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
5. Run Laravel migrate command.
```php artisan migrate:refresh```
6. (Optional) run Laravel db seed.
```php artisan db:seed```
