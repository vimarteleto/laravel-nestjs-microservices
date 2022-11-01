# laravel-nestjs-microservices

# commands

## start containers
```bash
chmod +x start.sh
./start.hs
```
## migrate das tabelas do serviço de usuarios
```bash
docker exec -it users bash -c "php artisan migrate:fresh"
```

## fila de importação de usuários via arquivo csv
```bash
docker exec -it users bash -c "php artisan queue:work sqs --queue=users"
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

## testing
```
docker exec -it users bash -c "php artisan test"
```
