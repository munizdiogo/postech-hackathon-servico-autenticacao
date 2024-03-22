<?php
function validarCPF($cpf)
{
    // Remover caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verificar se o CPF tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verificar se todos os dígitos são iguais, o que invalida o CPF
    if (preg_match('/^(\d)\1*$/', $cpf)) {
        return false;
    }

    // Calcular o primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += $cpf[$i] * (10 - $i);
    }
    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;

    // Calcular o segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += $cpf[$i] * (11 - $i);
    }
    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;

    // Verificar se os dígitos verificadores calculados coincidem com os fornecidos
    if ($digito1 == $cpf[9] && $digito2 == $cpf[10]) {
        return true;
    }

    return false;
}
