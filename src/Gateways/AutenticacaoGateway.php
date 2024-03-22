<?php

namespace Autenticacao\Gateways;

require "./src/Interfaces/Gateways/AutenticacaoGatewayInterface.php";

use Autenticacao\Interfaces\DbConnection\DbConnectionInterface;
use Autenticacao\Interfaces\Gateways\AutenticacaoGatewayInterface;

class AutenticacaoGateway implements AutenticacaoGatewayInterface
{
    private $repositorioDados;

    public function __construct(DbConnectionInterface $database = null)
    {
        $this->repositorioDados = $database;
    }

    private $urlAws = "https://oy8mfwrbt6.execute-api.us-east-1.amazonaws.com";

    public function gerarToken($cpf, $senha)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->urlAws}/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
            '{
                "cpf": "' . str_replace([".", "-"], "", $cpf) . '",
                "senha": "' . $senha . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }

    public function criarContaCognito($dados)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->urlAws}/criar-conta",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                        "email": "' . $dados["email"] . '",
                        "name": "' . $dados["nome"] . '",
                        "cpf": "' . $dados["cpf"] . '",
                        "senha": "' . $dados["senha"] . '"
                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }

    public function cadastrarColaboradorNoBancoDeDados($dados)
    {
        $cpf = str_replace([".", "-"], "", $dados["cpf"]);
        $clienteJaCadastrado = $this->repositorioDados->buscarPorCpf("colaboradores", $cpf);

        if (!empty($clienteJaCadastrado)) {
            throw new \Exception("Cliente já cadastrado.", 400);
        }

        $dadosParaCadastro = [
            "nome" => $dados["nome"],
            "cpf" => $cpf,
            "email" => $dados["email"],
            "telefone" => $dados["telefone"] ?? null,
            "data_nascimento" => $dados["data_nascimento"],
            "horario_inicio_expediente" => $dados["horario_inicio_expediente"] ?? null,
            "horario_termino_expediente" => $dados["horario_termino_expediente"] ?? null,
            "horario_inicio_almoco" => $dados["horario_inicio_almoco"] ?? null,
            "horario_termino_almoco" => $dados["horario_termino_almoco"] ?? null,
            "status" => $dados["status"] ?? null
        ];

        $idColaborador = $this->repositorioDados->inserir("colaboradores", $dadosParaCadastro);
        return !empty($idColaborador);
    }
}
