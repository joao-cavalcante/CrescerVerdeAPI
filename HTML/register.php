<?php

$db = new mysqli("localhost", "root", "123456789", "login");

if ($db->connect_error) {
    die("Falha na conexão: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $db->real_escape_string($_POST['username']);
    $password = $db->real_escape_string($_POST['password']);


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }

    $stmt->close();
}
$db->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Cadastro de Usuário</h2>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="username">Nome de usuário:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>
