<?php

/**
 * Arquivo: Sanitizer.php
 * Camada: Security
 *
 * Responsabilidade:
 *   Centraliza a sanitização de dados de entrada e saída, prevenindo XSS e injeção — ponto único para essa responsabilidade em todo o sistema.
 *
 * Permitido conter:
 *   Funções de limpeza/escape de dados.
 *
 * Proibido conter:
 *   Regra de negócio, validação de formato (isso é do Validator).
 *
 * Depende de:
 *   Nenhuma.
 *
 * Usado por:
 *   app/Validators/*.php, resources/views/**
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

namespace App\Security;

class Sanitizer
{
    // Métodos como limpar(), escaparParaHtml() serão implementados na
    // etapa de desenvolvimento.
}
