<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Выбор адреса доставки</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<main class="container">
    <h1>Выбор адреса доставки</h1>

    <label for="address-input" class="label">Введите адрес</label>
    <div class="input-row">
        <input id="address-input" type="text" autocomplete="off" placeholder="Например: Москва, Тверская, 1">
        <button id="select-button" type="button">Выбрать</button>
    </div>

    <ul id="suggestions" class="suggestions"></ul>

    <section class="selected-block">
        <h2>Выбранный адрес:</h2>
        <p id="selected-address" class="selected-address">Адрес не выбран</p>
    </section>
</main>

<script src="/assets/app.js"></script>
</body>
</html>
