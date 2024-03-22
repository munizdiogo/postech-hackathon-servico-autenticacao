<?php

namespace Autenticacao\Controllers;

require "./src/Interfaces/Controllers/AutenticacaoControllerInterface.php";
require "./src/UseCases/AutenticacaoUseCases.php";

use Autenticacao\Gateways\AutenticacaoGateway;
use Autenticacao\Interfaces\Controllers\AutenticacaoControllerInterface;
use Autenticacao\UseCases\AutenticacaoUseCases;

class AutenticacaoController implements AutenticacaoControllerInterface
{
    private $autenticacaoUseCases;
    public function __construct()
    {
        $this->autenticacaoUseCases = new AutenticacaoUseCases();
    }
    function gerarToken($cpf, $senha)
    {
        $token = $this->autenticacaoUseCases->gerarToken($cpf, $senha);
        return $token;
    }

    function criarContaCognito($dados)
    {
        $token = $this->autenticacaoUseCases->criarContaCognito($dados);
        return $token;
    }

    function cadastrarColaboradorNoBancoDeDados($dbConnection, $dados)
    {
        $autenticacaoGateway = new AutenticacaoGateway($dbConnection);
        $autenticacaoUseCases = new AutenticacaoUseCases();
        $resultado = $autenticacaoUseCases->cadastrarColaboradorNoBancoDeDados($autenticacaoGateway, $dados);
        return $resultado;
    }
}
