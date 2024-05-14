<?php
// Inicia uma nova sessão ou resume a sessão existente
session_start();

// Conecta ao banco de dados MySQL
$db = new mysqli("localhost", "root", "123456789", "login");
if ($db->connect_error) {
    die("Falha na conexão: " . $db->connect_error);
}

// Inicializa a variável de erro para armazenar mensagens de erro
$error = '';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepara e limpa os dados de entrada para evitar SQL injection
    $username = $db->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Prepara uma consulta SQL para buscar o usuário
    $stmt = $db->prepare("SELECT password FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o usuário existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verifica se a senha inserida está correta
        if (password_verify($password, $hashed_password)) {
            // Define as variáveis de sessão
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            // Redireciona para a página de boas-vindas
            header("Location: welcome.php");
            exit;
        } else {
            // Caso a senha não corresponda
            $error = "Usuário ou senha incorretos!";
        }
    } else {
        // Caso o usuário não seja encontrado
        $error = "Usuário ou senha incorretos!";
    }

    // Fecha o statement
    $stmt->close();
}

// Fecha a conexão com o banco
$db->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/Imagens/EarthPNG.png" sizes="16x16" type="image/png">
  <title>Plataforma de Cursos de Sustentabilidade Infantil</title>
  <link rel="stylesheet" href="../CSS/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-brand">
                        <a href="/index.html">
                        <img src="../Imagens/logo.png" alt="Logo" style="width: 220px;">
                </div>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="/HTML/cursos.html">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/HTML/certificados.html">Certificados</a></li>
                    <li class="nav-item"><a class="nav-link" href="/HTML/login.html">Login</a></li>
                </ul>                
            </div>
        </nav>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form" enctype="multipart/form-data">
            <h2>Login</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Entrar</button>

            <button type="button" onclick="window.location='register.php';">Registrar</button>
        </form>        
</form>   
    <footer>
        <p>&copy; 2023 Plataforma de Cursos de Sustentabilidade Infantil</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

</body>
</html>