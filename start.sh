#!/bin/bash

cd company-nestjs-service

docker-compose up -d

cd ..

cd user-laravel-service

docker-compose up -d

docker-compose exec laravel /bin/bash -c "composer install && chmod -R 777 storage/ && php artisan key:generate"

cd ..