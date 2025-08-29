<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estoque_combustivel";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Falha na conexão: " . $conn->connect_error); }

$id = intval($_GET['id']);

// Atualizar dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = $_POST['produto'];
    $estoque_sistema = $_POST['estoque_sistema'];
    $estoque_fisico = $_POST['estoque_fisico'];

    $stmt = $conn->prepare("UPDATE estoque SET produto=?, estoque_sistema=?, estoque_fisico=? WHERE id=?");
    $stmt->bind_param("siii", $produto, $estoque_sistema, $estoque_fisico, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: listar_estoque.php");
    exit;
}

// Buscar dados atuais
$result = $conn->query("SELECT * FROM estoque WHERE id=$id");
$produto = $estoque_sistema = $estoque_fisico = "";
if ($row = $result->fetch_assoc()) {
    $produto = $row['produto'];
    $estoque_sistema = $row['estoque_sistema'];
    $estoque_fisico = $row['estoque_fisico'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Produto</title>
<style>
body { font-family: Arial, sans-serif; margin: 40px; }
form { max-width: 400px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 10px; }
label { display: block; margin-top: 10px; }
input { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
button { margin-top: 15px; padding: 10px 15px; border: none; background: #007BFF; color: #fff; border-radius: 5px; cursor: pointer; }
button:hover { background: #0056b3; }
</style>
</head>
<body>

<h1>Editar Produto</h1>

<form method="POST">
  <label>Produto:</label>
  <input type="text" name="produto" value="<?= htmlspecialchars($produto) ?>" required>

  <label>Estoque do Sistema:</label>
  <input type="number" name="estoque_sistema" value="<?= $estoque_sistema ?>" required>

  <label>Estoque Físico:</label>
  <input type="number" name="estoque_fisico" value="<?= $estoque_fisico ?>" required>

  <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>