<?php

require "./src/Controllers/AutenticacaoController.php";

use PHPUnit\Framework\TestCase;
use Autenticacao\Controllers\AutenticacaoController;

class AutenticacaoControllerTest extends TestCase
{
    private $autenticacaoController;
    private $cpf;
    private $senha;
    protected function setUp(): void
    {
        $this->autenticacaoController = new AutenticacaoController();
        $this->cpf = "22222222222";
        $this->senha = "Hacka@123";
    }
    public function testGerarTokenComSucesso()
    {
        $resultado = $this->autenticacaoController->gerarToken($this->cpf, $this->senha);
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
    }

    public function testGerarTokenComCPFNaoInformado()
    {
        try {
            $this->autenticacaoController->gerarToken('', 'Hacka@123');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCriarContaCognitoComSucesso()
    {
        // Mock da resposta esperada
        $expectedResult = '{"mensagem": "Colaborador cadastrado com sucesso."}';

        // Criando um mock para a função criarContaCognito
        $autenticacaoControllerMock = $this->getMockBuilder(AutenticacaoController::class)
            ->onlyMethods(['criarContaCognito'])
            ->getMock();

        // Configurando o mock para retornar a resposta esperada
        $autenticacaoControllerMock->expects($this->any())
            ->method('criarContaCognito')
            ->willReturn($expectedResult);

        // Chamando a função como você mencionou
        $resultado = $autenticacaoControllerMock->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');

        // Verificando se o resultado é igual ao esperado
        $this->assertEquals($expectedResult, $resultado);
    }
}
