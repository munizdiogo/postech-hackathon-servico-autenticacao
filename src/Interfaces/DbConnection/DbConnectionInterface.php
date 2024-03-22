<?php

namespace Autenticacao\Interfaces\DbConnection;

interface DbConnectionInterface
{
    public function conectar();
    public function inserir(string $nomeTabela, array $parametros);
    public function excluir(string $nomeTabela, array $parametros);
    public function buscarPorCpf(string $nomeTabela, string $cpf): array;
}
