<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmation d'email</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div style="text-align: center; padding: 20px;">
        <h3>Bonjour, {{ $name }}</h3>
        <h1 style="font-size: 2em; margin: 20px 0;">{{ $code }}</h1>
        <h4>Utilisez le code suivant pour confirmer votre email ({{ $email }}) </h4>
    </div>
</body>

</html>
