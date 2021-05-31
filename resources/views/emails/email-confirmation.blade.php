<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Чтобы подтвердить ваш имеил, пожалуйста, перейдите по ссылке</h1>
    <a href="{{ route('confirm-email', ['user' => $user,'token' => $token]) }}">Нажмите здесь</a>
</body>
</html>