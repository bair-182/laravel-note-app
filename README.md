### 1. `docker compose build`

### 2. `docker composer up -d`

### 3. Войти в контейнер app:
1. `docker compose exec app bash`

2. `php artisan migrate`

3. `php artisan db:seed`

4. `php artisan passport:client --personal`

### 4. Запуск служб очереди и RabbitMQ:

1. `php artisan queue:work`
2. Если ошибка:
- `php artisan rabbitmq:queue-declare default`
- `php artisan queue:restart`
- `php artisan rabbitmq:consume`
### 5. Swagger.
http://localhost:8000/api/documentation

### 6. Импорт файл для Postman в папке проекта:
`Soft-engin-test.postman_collection.json`
