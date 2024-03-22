<?php

namespace Autenticacao\Interfaces\Controllers;

interface AutenticacaoControllerInterface
{
    public function gerarToken(string $cpf, string $senha);
    public function criarContaCognito($dados);
}
