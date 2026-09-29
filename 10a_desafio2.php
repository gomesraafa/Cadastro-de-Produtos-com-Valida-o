<?php

$mensagem = "";

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "exercicio";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $preco = $_POST["preco"] ?? "";

    if ($nome === "") {
        $mensagem = "Erro: O nome do produto não pode estar vazio.";
    } elseif (!is_numeric($preco) || $preco <= 0) {
        $mensagem = "Erro: O preço deve ser um número positivo.";
    } else {

        $preco = (float) $preco;

        $sql = "INSERT INTO produtos (nome, preco) VALUES (?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sd", $nome, $preco);

        if ($stmt->execute()) {
            $mensagem = "Produto cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar o produto.";
        }

        $stmt->close();
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
</head>
<body>

    <h1>Cadastro de Produto</h1>

    <?php if ($mensagem !== ""): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required>

        <br><br>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" min="0.01" required>

        <br><br>

        <button type="submit">Cadastrar Produto</button>

    </form>

</body>
</html>