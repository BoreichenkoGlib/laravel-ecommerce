## Laravel Electronics Ecommerce Website
Technologies used in this project:
- Laravel
- Vue.js

### Live preview
Website: https://laravuecommerce.online

Admin Panel: https://admin.laravuecommerce.online
```
admin@example.com
admin123
```

### Installation with docker
Before installation you need:
- MySQL
- PHP 8.1
- Composer
- Node.js

#### 1. Clone the project
```
git clone _______
```

#### 2. Run `composer install`
Navigate into project folder using terminal and run

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

#### 3. Copy `.env.example` into `.env`

```
cp .env.example .env
```
#### 4. Configure stripe keys in `.env` file from your stripe account

#### 5. Start the project in detached mode

```
./vendor/bin/sail up -d
```
From now on to run artisan command do this from the container. <br>
To access docker container run
```
./vendor/bin/sail bash
```

#### 6. Set encryption key

```
php artisan key:generate --ansi
```

#### 7. Run migrations

```
php artisan migrate --seed
```

#### 8. Install Laravel website frontend
Open new terminal and navigate to the project root directory
```
npm install
```
To start Vite server for Laravel frontend run
```
npm run dev
```

#### 9. Install Vue.js Admin Panel
Open new terminal and navigate to `/admin-panel` directory
```
npm install
```
```
cp .env.example .env
```
`VITE_API_BASE_URL` key in `admin-panel/.env` must be set to your Laravel API host. <br>
In project by default: `VITE_API_BASE_URL=http://localhost:8000`

To start Vite server run
```
npm run dev
```

#### 10. Open Laravel Website http://localhost:8000

#### 11. Open Vue.js Admin Panel and login http://localhost:3001
```
admin@example.com
admin123
```

#### 12. Run tests
```
./vendor/bin/phpunit
```
