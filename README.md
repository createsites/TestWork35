# Тестовое задание для AbeloHost

## Запуск проекта

Нужно создать `.env` файл, можно просто переименовать `.env.example`.

Если заняты порты `8080` и `33066`, нужно указать другие в `.env` в переменных `APP_PORT` и `FORWARD_DB_PORT`.

Далее сборка и запуск контейнера с приложением wordpress и базой к нему. 

```shell
docker-compose up --build -d

docker exec -it abelo-app sh -c 'sh < wp_init.sh'
```
