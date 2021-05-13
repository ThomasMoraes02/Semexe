<?php

namespace Src\config;

use Exception;
use Src\controllers\Usuario;

class Route
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->setRoute($url);
    }

    public function getRoute(): string
    {
        return $this->url;
    }

    private function setRoute($request):void 
    {
        $controller = new Usuario();

        $parseUrl = parse_url(BASE_URL);
        $path = $parseUrl['path'];
        
        if(!$path) {
            throw new Exception("Rota não configurada! Defina a URL em: src\config\config.php -> BASE_URL", 1);
        }

        switch($request) {
            case "$path/":
                $controller->index();
                break;
            
            case "$path/cadastrar":
                $controller->cadastrar();
                break;
            
            case "$path/insert":
                $controller->insert();
                break;
            
            case "$path/listar":
                $controller->listar();
                break;
            
            case "$path/alterar":
                $controller->alterar();
                break;
            
            case "$path/update":
                $controller->update();
                break;
            
            case "$path/delete":
                $controller->delete();
                break;
            
            case "$path/buscar":
                $controller->buscar();
                break;
            
            default:
                $controller->error();
                break;
        }
        return;
    }
}

