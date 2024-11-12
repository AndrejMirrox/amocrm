<html>
    <head>
        <title>AmoCRM TEST</title>
        <script src="script.js?<?=rand()?>" defer></script>
    </head>
    <body>
    <form id="leadForm">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="price">Цена:</label>
        <input type="number" id="price" name="price" required>

        <button type="submit">Отправить</button>
    </form>
    </body>
<style>
    #leadForm {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
</style>
</html>