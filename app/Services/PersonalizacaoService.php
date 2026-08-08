<?php

/**
 * Arquivo: PersonalizacaoService.php
 * Camada: Services
 *
 * Responsabilidade:
 *   Contém a regra de negócio da personalização de balões: cálculo de preço final, regras de combinação de cor/tamanho/tipo, checagem de disponibilidade. É a camada que pode ser reaproveitada por um Controller web, uma futura API ou um script de linha de comando.
 *
 * Permitido conter:
 *   Regras de negócio, orquestração entre Repositories.
 *
 * Proibido conter:
 *   Query SQL direta (isso é do Repository), HTML, leitura de $_POST/$_GET diretamente (isso é do Controller).
 *
 * Depende de:
 *   app/Repositories/BalaoRepository.php, app/Models/Balao.php.
 *
 * Usado por:
 *   app/Controllers/Http/BalaoController.php
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

namespace App\Services;

class PersonalizacaoService
{
    // Métodos como calcularPreco(), verificarDisponibilidade()
    // serão implementados na etapa de desenvolvimento.
}
