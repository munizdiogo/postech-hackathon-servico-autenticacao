<?php

use PHPUnit\Framework\TestCase;
use Autenticacao\UseCases\AutenticacaoUseCases;

use function PHPUnit\Framework\throwException;

class AutenticacaoUseCasesTest extends TestCase
{
    private $cpf;
    private $senha;
    private $autenticacaoUseCases;
    protected function setUp(): void
    {
        $this->autenticacaoUseCases = new AutenticacaoUseCases();
        $this->cpf = "22222222222";
        $this->senha = "Hacka@123";
    }
    public function testGerarTokenComSucesso()
    {
        $resultado = $this->autenticacaoUseCases->gerarToken($this->cpf, $this->senha);
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
    }

    public function testGerarTokenComCPFNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->gerarToken('', 'Hacka@123');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCriarContaCognitoComSucesso()
    {
        $dados = ['cpf' => "22222222222", 'nome' => 'Rodrigo Carmo', 'email' => 'rodrigocarmodev@gmail.com', 'telefone' => '11988888888', 'data_nascimento' => '1994-04-10', 'horario_inicio_expediente' => '10:00', 'horario_termino_expediente' => '18:00', 'horario_inicio_almoco' => '12:00', 'horario_termino_almoco' => '13:00', 'status' => 'ativo', 'senha' => 'Hacka@123'];
        $resultado = $this->autenticacaoUseCases->criarContaCognito($dados);
        $usuarioCadastradoComSucesso = !empty($resultado["status"]) && in_array($resultado["status"], ["usuario-criado-com-sucesso", "usuario-ja-existente"]);
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
    public function testCriarContaCognitoComCPFNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('', 'Carmo', 'usuario_teste@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComNomeNaoInformado()
    {
        try {
            $dados = ['cpf' => "22222222222", 'nome' => '', 'email' => 'rodrigocarmodev@gmail.com', 'telefone' => '11988888888', 'data_nascimento' => '1994-04-10', 'horario_inicio_expediente' => '10:00', 'horario_termino_expediente' => '18:00', 'horario_inicio_almoco' => '12:00', 'horario_termino_almoco' => '13:00', 'status' => 'ativo', 'senha' => 'Hacka@123'];
            $this->autenticacaoUseCases->criarContaCognito($dados);
        } catch (Exception $e) {
            $this->assertEquals("O nome é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComEmailNaoInformado()
    {
        try {
            $dados = ['cpf' => "22222222222", 'nome' => 'Rodrigo Carmo', 'email' => '', 'telefone' => '11988888888', 'data_nascimento' => '1994-04-10', 'horario_inicio_expediente' => '10:00', 'horario_termino_expediente' => '18:00', 'horario_inicio_almoco' => '12:00', 'horario_termino_almoco' => '13:00', 'status' => 'ativo', 'senha' => 'Hacka@123'];
            $this->autenticacaoUseCases->criarContaCognito($dados);
        } catch (Exception $e) {
            $this->assertEquals("O email é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCriarContaCognitoComEmailInvalido()
    {
        try {
            $dados = ['cpf' => "22222222222", 'nome' => 'Rodrigo Carmo', 'email' => 'rodrigocarmodev', 'telefone' => '11988888888', 'data_nascimento' => '1994-04-10', 'horario_inicio_expediente' => '10:00', 'horario_termino_expediente' => '18:00', 'horario_inicio_almoco' => '12:00', 'horario_termino_almoco' => '13:00', 'status' => 'ativo', 'senha' => 'Hacka@123'];
            $this->autenticacaoUseCases->criarContaCognito($dados);
        } catch (Exception $e) {
            $this->assertEquals("O email informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
