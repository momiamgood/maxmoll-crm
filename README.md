# Руководство 🛠️

## Postman Collection

<a href="https://www.postman.com/interstellar-eclipse-410947/workspace/maxmoll/collection/25320368-0d3616a5-d465-4540-97a4-d48a9987f27b?action=share&creator=25320368&active-environment=25320368-5f3e5d00-17c8-4f60-a645-31d22c5fc6bf">Maxmoll CRM 🧩</a> 

##  Создаём .env
```bash
cp .env.example .env
```

## Запускам контейнеры
```bash
docker compose up -d --build
```

## Подключаемся к контейнеру 

```bash
docker exec -it php bash
```

## Ставим зависимости 
```bash
composer install
```

## Настраиваемя приложение

1. Генерируем ключ;
```bash
php artisan key:generate
```

2. Прогоняем применение миграций и запуск сидов;
```bash
php artisan migrate --seed
```
