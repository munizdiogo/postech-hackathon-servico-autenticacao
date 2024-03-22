<?php

namespace Autenticacao\UseCases;

require "./src/Interfaces/UseCases/AutenticacaoUseCasesInterface.php";
require "./src/Gateways/AutenticacaoGateway.php";
require "./utils/ValidarCPF.php";
require "./utils/ValidarEmail.php";

use Autenticacao\Gateways\AutenticacaoGateway;
use Autenticacao\Interfaces\UseCases\AutenticacaoUseCasesInterface;

class AutenticacaoUseCases implements AutenticacaoUseCasesInterface
{
    private $autenticacaoGateway;
    public function __construct()
    {
        $this->autenticacaoGateway = new AutenticacaoGateway();
    }
    public function gerarToken($cpf, $senha)
    {
        if (empty($cpf)) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        if (empty($senha)) {
            throw new \Exception("A senha é obrigatório.", 400);
        }

        $resultado = $this->autenticacaoGateway->gerarToken($cpf, $senha);
        return $resultado;
    }

    public function cadastrarColaboradorNoBancoDeDados(AutenticacaoGateway $autenticacaoGateway, $dados)
    {
        if (empty($dados["nome"])) {
            throw new \Exception("O nome é obrigatório.", 400);
        }

        if (empty($dados["cpf"])) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        if (empty($dados["email"])) {
            throw new \Exception("O email é obrigatório.", 400);
        }

        if (empty($dados["data_nascimento"])) {
            throw new \Exception("A data de nascimento é obrigatório.", 400);
        }

        $cadastrarColaboradorNoBancoDeDados = $autenticacaoGateway->cadastrarColaboradorNoBancoDeDados($dados);

        if (!$cadastrarColaboradorNoBancoDeDados) {
            throw new \Exception("Ocorreu um erro ao cadastrar os dados do novo colaborador no banco de dados.", 500);
        }
    }

    public function criarContaCognito($dados)
    {
        if (empty($dados["cpf"])) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        if (empty($dados["nome"])) {
            throw new \Exception("O nome é obrigatório.", 400);
        }

        if (empty($dados["email"])) {
            throw new \Exception("O email é obrigatório.", 400);
        }

        if (empty($dados["senha"])) {
            throw new \Exception("A senha é obrigatório.", 400);
        }

        // $cpfValido = validarCPF($dados["cpf"]);

        // if (!$cpfValido) {
        //     throw new \Exception("O CPF informado é inválido.", 400);
        // }

        $emailValido = validarEmail($dados["email"]);

        if (!$emailValido) {
            throw new \Exception("O email informado é inválido.", 400);
        }

        $resultado = $this->autenticacaoGateway->criarContaCognito($dados);
        $resultadoArray = json_decode($resultado, true);

        if (!empty($resultadoArray["status"]) && in_array($resultadoArray["status"], ["usuario-criado-com-sucesso", "usuario-ja-existente"])) {
            return $resultadoArray;
        } else {
            throw new \Exception("Ocorreu um erro ao criar conta no Cognito.", 400);
        }
    }
}
