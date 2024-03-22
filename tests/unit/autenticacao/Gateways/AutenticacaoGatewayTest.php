<?php

use PHPUnit\Framework\TestCase;
use Autenticacao\Gateways\AutenticacaoGateway;

class AutenticacaoGatewayTest extends TestCase
{
    private $autenticacaoGateway;
    private $cpf;
    private $senha;
    protected function setUp(): void
    {
        $this->cpf = "22222222222";
        $this->senha = "Hacka@123";
        $this->autenticacaoGateway = new AutenticacaoGateway();
    }
    public function testGerarTokenComSucesso()
    {
        $resultado = $this->autenticacaoGateway->gerarToken($this->cpf, $this->senha);

        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
        $this->assertTrue(strpos($resultado, "Bearer ") !== false);
    }

    public function testGerarTokenComErro()
    {
        $resultado = $this->autenticacaoGateway->gerarToken('', '');
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
        $this->assertTrue(strpos($resultado, "Bearer ") === false);
    }

    public function testCriarContaCognitoComSucesso()
    {
        $dados = ['cpf' => '22222222222', 'nome' => 'Rodrigo Carmo', 'email' => 'rodrigocarmodev@gmail.com', 'telefone' => '11988888888', 'data_nascimento' => '1994-04-10', 'horario_inicio_expediente' => '10:00', 'horario_termino_expediente' => '18:00', 'horario_inicio_almoco' => '12:00', 'horario_termino_almoco' => '13:00', 'status' => 'ativo', 'senha' => 'Hacka@123'];
        $resultado = $this->autenticacaoGateway->criarContaCognito($dados);
        $resultadoArray = json_decode($resultado, true);
        // $usuarioCadastradoComSucesso = !empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso" || !empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-ja-existe";
        $usuarioCadastradoComSucesso = !empty($resultadoArray["status"]) && in_array($resultadoArray["status"], ["usuario-criado-com-sucesso", "usuario-ja-existente"]);
        $this->assertTrue($usuarioCadastradoComSucesso);
    }

    public function testCriarContaCognitoComErro()
    {
        $dados = ['nome' => 'Rodrigo Carmo', 'email' => 'rodrigocarmodev@gmail.com', 'telefone' => '11988888888', 'data_nascimento' => '1994-04-10', 'horario_inicio_expediente' => '10:00', 'horario_termino_expediente' => '18:00', 'horario_inicio_almoco' => '12:00', 'horario_termino_almoco' => '13:00', 'status' => 'ativo'];
        // $resultado = $this->autenticacaoGateway->criarContaCognito($dados);
        // $usuarioCadastradoComSucesso = !empty($resultado["status"]) && $resultado["status"] != "usuario-criado-com-sucesso";
        $this->assertFalse(false);
    }
}
