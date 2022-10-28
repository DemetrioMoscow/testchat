## Installation

```shell
composer install
cp .env.example .env
./vendor/bin/sail build
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan key:generate
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```
