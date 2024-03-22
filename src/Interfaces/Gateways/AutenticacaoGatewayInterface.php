<?php

namespace Autenticacao\Interfaces\Gateways;


interface AutenticacaoGatewayInterface
{
    public function gerarToken(string $cpf, string $senha);
    public function criarContaCognito(array $dados);
}
