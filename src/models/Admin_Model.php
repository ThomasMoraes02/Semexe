<?php 

namespace Src\models;

use PDO;

class Admin_Model
{
    public function __construct()
    {
        $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";",DB_USER, DB_PASS);
        $this->db = $db;
    }

    public function getAdmins()
    {
        $sql = "SELECT * FROM tb_admin";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAdm($admin)
    {
        extract($admin);

        $sql = "INSERT INTO tb_admin (nome_admin, email_admin, senha_admin) VALUES (:nome, :email_admin, :senha_admin)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha);

        $stmt->execute();
    }

}