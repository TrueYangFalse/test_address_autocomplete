# Тестовое: форма выбора адреса (PHP + PostgreSQL)

## Что внутри

- `public/index.php` - страница с полем ввода
- `public/api/addresses.php` - API для подсказок (GET ?q=...)
- `src/Database.php` - подключение к PostgreSQL
- `src/AddressRepository.php` - работа с адресами в БД
- `sql/schema.sql` - создание таблицы
- `sql/demo_seed.sql` - демо-данные для быстрого старта
- `tools/import_fias_csv.php` - импорт из CSV (ФИАС)
- `tools/import_kladr_csv.php` - импорт из CSV (КЛАДР)

## Быстрая проверка

```bash
# Создаём БД
createdb food_service

# Накатываем схему
psql -d food_service -f sql/schema.sql

# Заливаем демо-данные (5 адресов для теста)
psql -d food_service -f sql/demo_seed.sql

# Запускаем сервер
php -S localhost:8000 -t public

```

## Можно импортировать 164487 адресов из kladr.csv

```bash
# Просто импортируем данные из kladr.csv из корня
php tools/import_kladr_csv.php --file=kladr.csv  
