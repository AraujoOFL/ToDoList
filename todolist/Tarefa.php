<?php
class Tarefa {
    private $conn;
    private $table_name = "tarefas";

    public $id;
    public $titulo;
    public $descricao;
    public $concluida;
    public $data_criacao;
    public $data_atualizacao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar nova tarefa
    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET titulo=:titulo, descricao=:descricao, concluida=0";
        
        $stmt = $this->conn->prepare($query);
        
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descricao", $this->descricao);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Listar todas as tarefas
    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY data_criacao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Marcar como concluída
    public function marcarConcluida() {
        $query = "UPDATE " . $this->table_name . " 
                 SET concluida = NOT concluida 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Excluir tarefa
    public function excluir() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obter estatísticas
    public function getEstatisticas() {
        $estatisticas = array();
        
        // Total de tarefas
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $estatisticas['total'] = $row['total'];
        
        // Tarefas concluídas
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE concluida = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $estatisticas['concluidas'] = $row['total'];
        
        // Tarefas pendentes
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE concluida = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $estatisticas['pendentes'] = $row['total'];
        
        // Porcentagem de conclusão
        $estatisticas['percentual'] = $estatisticas['total'] > 0 ? 
            round(($estatisticas['concluidas'] / $estatisticas['total']) * 100, 2) : 0;
        
        return $estatisticas;
    }
}
?>