<?php

header('Content-Type: application/json; charset=utf-8');

require "./utils/RespostasJson.php";
require "./src/External/MySqlConnection.php";
require "./src/Controllers/AutenticacaoController.php";

use Autenticacao\External\MySqlConnection;
use Autenticacao\Controllers\AutenticacaoController;

$dbConnection = new MySqlConnection();
$autenticacaoController = new AutenticacaoController();

if (!empty($_GET["acao"])) {
    switch ($_GET["acao"]) {

        case 'cadastrar':
            try {

                $senha = $_POST["senha"] ?? null;

                if (empty($senha)) {
                    retornarRespostaJSON("Senha é um campo obrigatório.", 400);
                    exit;
                }

                $dadosParaCadastroBD = [
                    "nome" => $_POST["nome"] ?? null,
                    "cpf" => !empty($_POST["cpf"]) ? str_replace([".", "-"], "", $_POST["cpf"]) : null,
                    "email" => $_POST["email"] ?? null,
                    "telefone" => $_POST["telefone"] ?? null,
                    "data_nascimento" => $_POST["data_nascimento"] ?? null,
                    "horario_inicio_expediente" => $_POST["horario_inicio_expediente"] ?? null,
                    "horario_termino_expediente" => $_POST["horario_termino_expediente"] ?? null,
                    "horario_inicio_almoco" => $_POST["horario_inicio_almoco"] ?? null,
                    "horario_termino_almoco" => $_POST["horario_termino_almoco"] ?? null,
                    "status" => $_POST["status"] ?? null
                ];

                $autenticacaoController->cadastrarColaboradorNoBancoDeDados($dbConnection, $dadosParaCadastroBD);

                $dadosParaCadastroCognito = [
                    "cpf" => $dadosParaCadastroBD["cpf"],
                    "nome" => $dadosParaCadastroBD["nome"],
                    "email" => $dadosParaCadastroBD["email"],
                    "senha" =>  $senha
                ];

                $autenticacaoController->criarContaCognito($dadosParaCadastroCognito);
                retornarRespostaJSON("Colaborador cadastrado com sucesso!", 201);
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;

        case 'gerarToken':
            try {
                $cpf = !empty($_POST["cpf"]) ? str_replace([".", "-"], "", $_POST["cpf"]) : null;
                $senha = $_POST["senha"];
                $token = $autenticacaoController->gerarToken($cpf, $senha);
                echo '{"token":"' . $token . '"}';
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;

        default:
            echo '{"mensagem": "A ação informada é inválida."}';
            http_response_code(400);
    }
}
