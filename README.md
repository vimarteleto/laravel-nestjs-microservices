# laravel-nestjs-microservices

## commands
```bash

sudo chmod 777 start.sh

./start.sh

```

ou

```bash

cd company-nestjs-service && docker-compose up -d && cd ..

cd user-laravel-service && docker-compose up -d

docker-compose exec laravel /bin/bash -c "composer install && chmod -R 777 storage/ && php artisan key:generate" && cd ..

```

## localstack
```
http://localhost:4566/health
```

## laravel
```
http://localhost:8080/
```

## nestjs
```
http://localhost:3000/
```


php artisan migrate
