<?php
// Configurações do banco de dados
$servername = "localhost"; // Ou o IP do servidor
$username = "root"; // Usuário do MySQL
$password = ""; // Senha do MySQL
$dbname = "estoque_combustivel"; // Nome do banco de dados

// Conectar ao MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber os dados do formulário
$produto = $_POST['produto'];
$quantidade = $_POST['quantidade'];
$data_entrada = $_POST['data_entrada'];

// Preparar e executar a inserção
$sql = "INSERT INTO entradas (produto, quantidade, data_entrada)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $produto, $quantidade, $data_entrada);

if ($stmt->execute()) {
    echo "Produto cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

// Fechar conexão
$stmt->close();
$conn->close();

header("Location: entradas.php");
exit();
?>