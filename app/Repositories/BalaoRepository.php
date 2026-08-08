<?php

/**
 * Arquivo: BalaoRepository.php
 * Camada: Repositories
 *
 * Responsabilidade:
 *   Única classe autorizada a executar queries SQL relacionadas a balões (buscar, salvar, atualizar, listar). Traduz linhas do MySQL em objetos Balao e vice-versa.
 *
 * Permitido conter:
 *   Queries SQL (preparadas, nunca concatenadas), mapeamento linha->Model.
 *
 * Proibido conter:
 *   Regra de negócio (isso é do Service), validação de entrada de usuário (isso é do Validator).
 *
 * Depende de:
 *   app/Models/Balao.php, app/Config/database.php.
 *
 * Usado por:
 *   app/Services/PersonalizacaoService.php
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

namespace App\Repositories;

class BalaoRepository
{
    // Métodos como buscarPorId(), salvar(), listarTodos() serão
    // implementados na etapa de desenvolvimento, sempre com queries
    // preparadas (nunca SQL concatenado com entrada do usuário).
}
