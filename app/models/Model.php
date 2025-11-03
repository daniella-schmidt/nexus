<?php
// Herança: Classe Model é abstrata e serve como base para outras classes de modelo.
// Justificativa: Permite reutilização de código comum entre diferentes modelos, evitando duplicação.

// Abstração: Classe abstrata que define operações comuns sem implementação específica.
// Justificativa: Força subclasses a implementarem comportamentos específicos enquanto fornece funcionalidades genéricas.

// Polimorfismo: Métodos podem ser sobrescritos pelas subclasses para comportamentos específicos.
// Justificativa: Permite que diferentes modelos implementem operações CRUD de maneiras específicas.

abstract class Model {
    // Encapsulamento: Propriedades protegidas, acessíveis apenas pela classe e suas subclasses.
    // Justificativa: Protege o estado interno enquanto permite extensão por herança.
    protected $db;
    protected $table;

    // Visibilidade de Propriedades e Métodos: Construtor público para inicialização.
    // Justificativa: Permite instanciação controlada das subclasses.
    public function __construct($db) {
        $this->db = $db;
    }

    // Visibilidade de Propriedades e Métodos: Métodos públicos para operações CRUD básicas.
    // Justificativa: Interface pública para operações de banco de dados, mantendo encapsulamento.
    public function findAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
