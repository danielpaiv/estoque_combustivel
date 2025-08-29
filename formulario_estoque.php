<?php
// Configurações do banco de dados
$servername = "localhost"; // Ou o IP do servidor
$username = "root"; // Usuário do MySQL
$password = ""; // Senha do MySQL
$dbname = "estoque_combustivel"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consultar os produtos no estoque
$sql_produtos = "SELECT id, produto FROM produtos";
$result_produtos = $conn->query($sql_produtos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Estoque</title>
  <style>
    body {font-family: Arial, sans-serif;margin: 40px;background: #f9f9f9;color: #333;}
    h1 {text-align: center;}
    form {background: #fff;padding: 20px;border-radius: 10px;box-shadow: 0 2px 5px rgba(0,0,0,0.1);max-width: 400px;margin: 0 auto 30px;}
    label {display: block;margin: 10px 0 5px;}
    input[type="text"],
    input[type="number"] {width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 5px;}
    button { margin-top: 15px; padding: 10px 15px; border: none; background: #007BFF; color: #fff; border-radius: 5px; cursor: pointer; }
    button:hover {background: #0056b3;}
    table {width: 100%;border-collapse: collapse;margin-top: 20px;}
    th, td {padding: 10px;border: 1px solid #ccc;text-align: center;}
    th {background: #007BFF;color: white;}
    .btn-excluir {background: #dc3545;color: #fff;padding: 5px 10px;border: none;border-radius: 5px;cursor: pointer;}
    .btn-excluir:hover {background: #a71d2a;}
    select {width: 50%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; background: #28a745; color: #fff; cursor: pointer;}
    select:hover { background: #218838; }
    #data_venda:hover { background: #218838; }
    #data_venda {padding: 8px; border: 1px solid #ccc; border-radius: 5px; background: #28a745; color: #fff; cursor: pointer; }


    /* Estilo padrão do select */
    .filtro-servicos {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      padding: 8px 12px;
      border: 2px solid #ccc;
      border-radius: 6px;
      background: #fff;
      color: #333;
      font-weight: 600;
      transition: background-color .2s, color .2s, border-color .2s;
    }

    /* Mudança de cor conforme a opção selecionada */
    .filtro-servicos:has(option:checked[value="GASOLINA COMUM"]) {
      background-color: #d32f2f;  /* vermelho */
      color: #fff;
      border-color: #b71c1c;
    }

    .filtro-servicos:has(option:checked[value="GASOLINA DURA MAIS"]) {
      background-color: #1565c0;  /* azul */
      color: #fff;
      border-color: #0d47a1;
    }

    .filtro-servicos:has(option:checked[value="ETANOL"]) {
      background-color: #2e7d32;  /* verde */
      color: #fff;
      border-color: #1b5e20;
    }

    .filtro-servicos:has(option:checked[value="DIESEL S10"]) {
      background-color: #424242;  /* preto claro (cinza escuro) */
      color: #fff;
      border-color: #212121;
    }

    /* (Opcional) colorir as opções no dropdown */
    .filtro-servicos option[value="GASOLINA COMUM"] { background-color: #ffcdd2; }
    .filtro-servicos option[value="GASOLINA DURA MAIS"] { background-color: #bbdefb; }
    .filtro-servicos option[value="ETANOL"] { background-color: #c8e6c9; }
    .filtro-servicos option[value="DIESEL S10"] { background-color: #e0e0e0; }

  </style>
</head>
<body>

<h1>Cadastro de Estoque</h1>

<button onclick="window.location.href='listar_estoque.php'">Listar Estoque</button>

<form  action="salvar_estoque.php"  method="POST" >
    <label for="produto">Produto:</label>
   <select  id="produto" class="filtro-servicos" name="produto" required>
        <option value="">Selecione</option>
        <?php
        if ($result_produtos && $result_produtos->num_rows > 0) {
            while($row = $result_produtos->fetch_assoc()) {
                echo "<option value='" . $row['produto'] . "'>" . $row['produto'] . "</option>";
            }
        } else {
            echo "<option value=''>Nenhum produto encontrado</option>";
        }
        ?>
    </select>

  <label for="sistema">Estoque do Sistema:</label>
  <input type="number" id="sistema" name="estoque_sistema" required>

  <label for="fisico">Estoque Físico:</label>
  <input type="number" id="fisico" name="estoque_fisico" required>

  <label for="data_venda">DATA:</label>
  <input type="date" id="data_venda" name="data_venda" required>

  <button type="submit">Cadastrar</button>
</form>

<table id="tabelaEstoque">
  <thead>
    <tr>
      <th>ID</th>
      <th>Produto</th>
      <th>Estoque do Sistema</th>
      <th>Estoque Físico</th>
      <th>Diferença</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>
  const form = document.getElementById('formEstoque');
  const tabela = document.querySelector('#tabelaEstoque tbody');
  let id = 1;

  form.addEventListener('submit', function(event) {
    event.preventDefault();

    const produto = document.getElementById('produto').value.trim();
    const sistema = parseFloat(document.getElementById('sistema').value);
    const fisico = parseFloat(document.getElementById('fisico').value);
    const diferenca = fisico - sistema;

    const row = tabela.insertRow();
    row.insertCell(0).textContent = id++;
    row.insertCell(1).textContent = produto;
    row.insertCell(2).textContent = sistema;
    row.insertCell(3).textContent = fisico;
    row.insertCell(4).textContent = diferenca;

    const cellAcoes = row.insertCell(5);
    const btnExcluir = document.createElement('button');
    btnExcluir.textContent = 'Excluir';
    btnExcluir.classList.add('btn-excluir');
    btnExcluir.onclick = () => tabela.removeChild(row);
    cellAcoes.appendChild(btnExcluir);

    form.reset();
  });


</script>

</body>
</html>