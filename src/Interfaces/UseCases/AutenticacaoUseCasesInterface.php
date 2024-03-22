<?php

namespace Autenticacao\Interfaces\UseCases;

interface AutenticacaoUseCasesInterface
{
    public function gerarToken(string $cpf, string $senha);
    public function criarContaCognito(array $dados);
}
