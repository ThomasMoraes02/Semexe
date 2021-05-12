<?php 

namespace Src\controllers;

use Exception;
use Src\helpers\CPF_Helper;
use Src\helpers\Global_Helper;
use Src\models\Usuario_Model;
use Throwable;

class Usuario
{
    use Global_Helper;
    use CPF_Helper;

    public function __construct()
    {
        try {
            $this->model = new Usuario_Model();
        } catch(Exception $e) {

            $data = array(
                "title" => "Home",
                "message" => $e->getMessage(),
            );
            
            $this->view($data,"home");
        }
    }

    public function index(): void
    {
        $data = array(
            "title" => "Home",
            "message" => "Welcome to the system...",
        );

        $this->view($data,"home");
    }

    public function cadastrar(): void
    {
        if(!isset($this->model)) {
            $this->redirect("home");
        }

        $data = array(
            "title" => "Cadastrar"
        );

        $this->view($data, "cadastrar");
    }

    public function insert(): void
    {
        $user = $_POST;
        
        if($this->validaCPF($user['cpf']) != TRUE) {
            $this->flashMessage("danger", "CPF no Formato Inválido!");

        } else { 
            $user['cpf'] = $this->removeFormatacaoCPF($_POST['cpf']);
            $user['telefone'] = $this->removeFormatacaoTelefone($_POST['telefone']);
            $user['cep'] = $this->removeFormatacaoCEP($_POST['cep']);
            
            $this->model->insert($user);
            $this->flashMessage("success", "Usuário cadastrado com sucesso!");
        }

        $this->redirect("cadastrar");
    }

    public function listar(): void
    {
        if (!isset($this->model)) {
            $this->redirect("home");
        }

        $usuarios = $this->model->getUsers();

        $data = array(
            "title" => "Listar",
            "usuarios" => $usuarios,
        );

        $this->view($data, "listar");
    }

    public function alterar(): void
    {
        if (!isset($this->model)) {
            $this->redirect("home");
        }

        try {
            $id = $_POST['id'];
            $usuario = $this->model->getUserById($id);
        } catch(Throwable $e) {
            $this->redirect("home");
        }

        $data = array(
            "title" => "Alterar",
            "usuario" => $usuario
        );

        $this->view($data, "alterar");
    }

    public function update(): void
    {
        $user = $_POST;

        if($this->validaCPF($user['cpf']) != TRUE) {
            $this->flashMessage("danger", "CPF no Formato Inválido!");
            $this->redirect("alterar");

        } else {
            $user['cpf'] = $this->removeFormatacaoCPF($_POST['cpf']);
            $user['telefone'] = $this->removeFormatacaoTelefone($_POST['telefone']);
            $user['cep'] = $this->removeFormatacaoCEP($_POST['cep']);

            $this->model->update($user, $user['id']);

            $this->flashMessage("success", "Usuário alterado com sucesso!");
            $this->redirect("listar");
        }
    }

    public function delete(): void 
    {
        $id = $_POST['id'];

        $this->model->delete($id);
        $this->redirect("listar");
    }

    public function buscar(): void
    {
        $filtro = $_POST['filtro'];
        $usuarios = $this->model->search($filtro);

        $data = array(
            "title" => "Listar",
            "usuarios" => $usuarios,
        );

        $this->view($data, "listar");
    }

    public function error(): void
    {
        $data = array(
            "title" => "Página não Encontrada!",
            "message" => "Ooops! Página não encontrada"
        );

        $this->view($data, "erro");
    }
}