<?php
require_once('classes/Roupas.php'); /* essa parte do codigo puxa as informações tipo atributos e outras coisas variaveis desse arquivo */
require_once('conexao/conexao.php');

$database = new Database();
$db = $database->getConnection();
$roupas = new Roupas($db);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        /* São as ações que cada botão vai fazer o read e para poder atualizar sem ter problema com as informações antigas */
        case 'create':
            $roupas->create($_POST);
            $rows = $roupas->read();
            break;
        case 'read':
            $rows = $roupas->read();
            break;
        case 'update':
            if (isset($_POST['id'])) {
                $roupas->update($_POST);
            }
            $rows = $roupas->read();
            break;
        case 'delete':
            $roupas->delete($_GET['id']);
            $rows = $roupas->read();
            break;

        default:
            $rows = $roupas->read();
            break;
    }
} else {
    $rows = $roupas->read();
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <style>
        form {
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            display: flex;
            margin-top: 10px
        }

        select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=text] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        a {
            display: inline-block;
            padding: 4px 8px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        a:hover {
            background-color: #0069d9;
        }

        a.delete {
            background-color: #dc3545;
        }

        a.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>

<?php
/*Procurar as informações do usuario pelo id */
    if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $result = $roupas->readOne($id);

        if (!$result) {
            echo "Registro não encontrado.";
            exit();
        }
        $marca = $result['marca'];
        $modelo = $result['modelo'];
        $tamanho = $result['tamanho'];
        $cor = $result['cor'];
        $preco = $result['preco'];

    ?>
    
    <form action="?action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <label for="marca">Marca</label>
            <input type="text" name="marca" value="<?php echo $marca ?>">

            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" value="<?php echo $modelo ?>">

            <label for="tamanho">tamanho</label>
            <input type="text" name="tamanho" value="<?php echo $tamanho ?>">

            <label for="cor">Cor</label>
            <input type="text" name="cor" value="<?php echo $cor ?>">

            <label for="preco">Preço</label>
            <input type="text" name="preco" value="<?php echo $preco ?>">

            <input type="submit" value="Atualizar" name="enviar" onclick="return confirm('Certeza que deseja atualizar?')">

        </form>

    <?php
    } else {
    ?>

    <form action="?action=create" method="POST">
        <label for="">Marca</label>
        <select name="marca">
            <option value="barneys">Barneys</option>
            <option value="Sideplay">Side Play</option>
        </select>

        <label for="">Modelo</label>
        <select name="modelo">
            <option value="calça">Calça</option>
            <option value="short">Short</option>
            <option value="blusa">Blusa</option>
            <option value="camisa">Camisa</option>
        </select>

        <label for="">Tamanho</label>
        <select name="tamanho">
            <option value="PP">PP</option>
            <option value="P">P</option>
            <option value="M">M</option>
            <option value="G">G</option>
            <option value="GG">GG</option>
            <option value="EG">EG</option>
            <option value="EEG">EEG</option>
        </select>

        <label for="">Cor</label>
        <input type="text" name="cor">

        <label for="">Preço</label>
        <input type="text" name="preco">

        <input type="submit" value="Cadastrar" name="enviar">
    </form>

    <?php
    }
    ?>

    <table>
        <tr>
            <td>Id</td>
            <td>Marca</td>
            <td>Modelo</td>
            <td>Tamanho</td>
            <td>Cor</td>
            <td>Preço</td>
            <td>Ações</td>
        </tr>

        <?php
        /* Mostra as informações de cada usuario na tabela acima os <a> são as açoes update e delete */
        if ($rows->rowCount() == 0) {
            echo "<tr>";
            echo "<td colspan='7'>Nenhum dado encontrado</td>";
            echo "</tr>";
        } else {
            while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['marca'] . "</td>";
                echo "<td>" . $row['modelo'] . "</td>";
                echo "<td>" . $row['tamanho'] . "</td>";
                echo "<td>" . $row['cor'] . "</td>";
                echo "<td>" . $row['preco'] . "</td>";
                echo "<td>";
                echo "<a href='?action=update&id=" . $row['id'] . "'>Editar</a>";
                echo "<a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que quer apagar esse registro?\")' class='delete'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>

    </table>
</body>

</html>