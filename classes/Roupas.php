<?php
include_once('conexao/conexao.php');

$db = new Database();

class Roupas{
    private $conn;
    private $table_name = "roupas"; 

    public function __construct($db){
        $this->conn = $db;
    }
    /* Função pega as informações digitadas no formulario e coloca as informações no banco de dados */
    public function create($postValues){
        $marca = $postValues['marca'];
        $modelo = $postValues['modelo'];
        $tamanho = $postValues['tamanho'];
        $cor = $postValues['cor'];
        $preco = $postValues['preco'];


        $query = "INSERT INTO ". $this->table_name . " (marca, modelo, tamanho, cor, preco) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->bindParam(3,$tamanho);
        $stmt->bindParam(4,$cor);
        $stmt->bindParam(5,$preco);

        $rows = $this->read();
        if($stmt->execute()){
            print "<script>alert('Cadastro Ok!')</script>";
            print "<script> location.href='?action=read'; </script>";
            return true;
        }else{
            return false;
        }
    }
    /* essa função le as informações do banco de dados */
    public function read(){
        $query = "SELECT * FROM ". $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    /*atualiza as informações do banco de dados */
    public function update($postValues){
        
        $id = $postValues['id'];
        $marca = $postValues['marca'];
        $modelo = $postValues['modelo'];
        $tamanho = $postValues['tamanho'];
        $cor = $postValues['cor'];
        $preco = $postValues['preco'];

        if(empty($id) || empty($marca) || empty($modelo) || empty($tamanho) || empty($cor) || empty($preco)){
            return false;
        }
        
        $query = "UPDATE ". $this->table_name . " SET marca = ?, modelo = ?, tamanho = ?, cor = ?, preco = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->bindParam(3,$tamanho);
        $stmt->bindParam(4,$cor);
        $stmt->bindParam(5,$preco);
        $stmt->bindParam(6,$id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    /* puxa as informações atualizadas e atualiza no banco*/
        public function readOne($id){
            $query = "SELECT * FROM ". $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 

        }
        /* deleta as informações no banco de dados do id*/
        public function delete($id){
            $query = "DELETE FROM ". $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1,$id);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

    }
?>

