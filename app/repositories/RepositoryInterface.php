<?php
// Interfaces: Define contrato para repositórios, garantindo implementação consistente.
// Justificativa: Permite polimorfismo e padronização de métodos CRUD entre diferentes repositórios.

interface RepositoryInterface {
    public function findById($id);
    public function findAll();
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}
?>
