<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estoque_combustivel";

// Conectar ao MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consultar todos os produtos
$sql = "SELECT * FROM estoque ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estoque</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; color: #333; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #007BFF; color: white; }
        .btn { padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-editar { text-decoration: none; background: #ffc107; color: #000; } 
        .btn-excluir { text-decoration: none; background: #dc3545; color: #fff; }
        .btn-editar:hover {  background: #e0a800; }
        .btn-excluir:hover {  background: #a71d2a; }
        button { margin-bottom: 20px; padding: 10px 15px; border: none; background: #28a745; color: #fff; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<h1>Lista de Estoque</h1>

<button onclick="window.location.href='formulario_estoque.php'">Cadastrar Novo Produto</button>

<label for="dataFiltro">Filtrar por Data:</label>
<input type="date" id="dataFiltro" oninput="filtrarData()">

 <label for="filtroNome">Filtrar por serviços:</label>
 <input type="text" id="filtroNome" onkeyup="filtrarPorNome()">

<table id="clientesTabela">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Estoque do Sistema</th>
            <th>Estoque Físico</th>
            <th>Diferença</th>
            <th>Data da Venda</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['produto']) ?></td>
                    <td><?= $row['estoque_sistema'] ?></td>
                    <td><?= $row['estoque_fisico'] ?></td>
                    <td><?= $row['diferenca'] ?></td>
                    <td><?= $row['data_venda'] ?></td>
                    <td>
                        <a href="editar_estoque.php?id=<?= $row['id'] ?>" class="btn btn-editar">Editar</a>
                        <a href="excluir_estoque.php?id=<?= $row['id'] ?>" class="btn btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este item?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<script>
        function filtrarData() {
            const input = document.getElementById('dataFiltro');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('clientesTabela');
            const tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[5]; // coluna "Data"
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
        function filtrarPorNome() {
            const input = document.getElementById('filtroNome');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('clientesTabela');
            const tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[1]; // coluna "Nome"
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
</script>
</body>
</html>

<?php
$conn->close();
?>