<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Anmeldeplattform</title>

    <link rel="stylesheet" href="frontend/css/style.css">
</head>

<body>

    <header>
        <h1>Anmeldeplattform</h1>
        <p>Unsere erste Version</p>
    </header>

    <main>
        <h2>Hello World!</h2>

        <p>Willkommen in unserer Webanwendung.</p>

        <button onclick="showMessage()">
            Test starten
        </button>

        <p id="message"></p>
        <p><?php echo "PHP funktioniert! Version: " . phpversion(); ?></p>

    </main>

    <script src="frontend/js/script.js"></script>

</body>
</html>

