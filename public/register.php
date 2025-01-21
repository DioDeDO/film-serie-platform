<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css'); ?>">
</head>
<body>
    <div class="container">
        <h1>Registrazione</h1>
        <form id="registerForm">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Registrati</button>
        </form>

        <!-- Elemento per mostrare messaggi dinamici -->
        <p id="feedback" style="text-align: center; color: red;"></p>
    </div>

    <script src="js/register.js?v=<?= filemtime('js/register.js'); ?>"></script>
</body>
</html>
