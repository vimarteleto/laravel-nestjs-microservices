#!/bin/bash

cd company-nestjs-service

docker-compose down

docker-compose up -d --build

cd ..

cd user-laravel-service

docker-compose down

docker-compose up -d --build

docker-compose exec laravel /bin/bash -c "composer install && chmod -R 777 storage/ && php artisan key:generate"

# cd user-laravel-service && docker-compose exec laravel /bin/bash -c "php artisan migrate:fresh" && cd ..

# cd user-laravel-service && docker-compose exec laravel /bin/bash -c "php artisan queue:work sqs --queue=users"

cd ..