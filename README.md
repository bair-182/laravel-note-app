1. docker compose build
2. docker composer up -d
3. Войти в контейнер app: docker compose exec app bash
    1. войти в папку app: cd app
    2. php artisan migrate
    3. php artisan db:seed
    4. php artisan passport:client --personal
4. Запуск служб очереди и RabbitMQ:
    1. php artisan queue:work
    2. Если ошибка:
        1. php artisan rabbitmq:queue-declare default
        2. php artisan queue:restart
        3. php artisan rabbitmq:consume
5. Импорт файл для Postman в папке проекта: Soft-engin-test.postman_collection.json
