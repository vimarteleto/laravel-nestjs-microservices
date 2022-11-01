# laravel-nestjs-microservices

## start containers
```bash
chmod +x start.sh
./start.hs
```

## fluxo da aplicação:
- O arquivo insomnia.json, localizado na raíz do repositório, contém as requsições para utilização no Insomnia;
- O serviço de usuários foi construído com framework Laravel;
- O serviço de empresas foi construído com framework NesJS;
- O serviço de usuários oferece uma API com fluxo de CRUD para usuários;
- O serviço de usuários oferece uma API com rota para importação de usuários em massa via arquivo .csv;
- O arquivo users.csv, localizado na raíz do repositório, pode ser utilizado para importação de usuários;
- O serviço de empresas oferece uma API com fluxo de CRUD para empresas;
- O arquivo enviado na importação de usuários é armazenado em um bucket nos serviço S3 da AWS, utilizando container localstack;
- Para criação de um bucket, consultar a rota ```POST http://localhost:8080/api/storages```, por padrão o nome deve ser ```users-bucket```;
- A importação de usuários via arquivo é feita em processo background por meio de uma fila nos serviço SQS da AWS, utilizando container localstack;
- Para criação de uma fila, consultar a rota ```POST http://localhost:8080/api/queues```, por padrão o nome deve ser ```users-queue```;
- Para criar as empresas padrão presentes no arquivo users.csv, consultar a rota ```POST http://localhost:3000/api/companies/many``` com o seguinte payload:
```json
[
	{
		"name": "Facebook S/A",
		"website": "www.Facebook.com",
		"cnpj": "14388844000166"
	},
	{
		"name": "Amazon S/A",
		"website": "www.Amazon.com",
		"cnpj": "42080110000150"
	},
	{
		"name": "Netflix S/A",
		"website": "www.Netflix.com",
		"cnpj": "82486891000100"
	},
	{
		"name": "Microsoft S/A",
		"website": "www.Microsoft.com",
		"cnpj": "91265641000123"
	},
	{
		"name": "Google S/A",
		"website": "www.google.com",
		"cnpj": "86256798000152"
	}
]
```
- Para criar um usuário, o serviço de usuários consulta no serviço de empresas se a empresa do usuário a ser cadastrado existe;
- Para migrate:fresh do serviço de usuários:
```bash
docker exec -it users bash -c "php artisan migrate:fresh"
```
- Para execução da fila de importação se usuários por arquivo:
```bash
docker exec -it users bash -c "php artisan queue:work sqs --queue=users-queue"
```
- Para execução dos testes:
```bash
docker exec -it users bash -c "php artisan test"
```

## portas:
## laravel
```
http://localhost:8080/
```
## nestjs
```
http://localhost:3000/api
```
## localstack
```
http://localhost:4566/health
```

