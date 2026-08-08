<?php

/**
 * Arquivo: Balao.php
 * Camada: Models
 *
 * Responsabilidade:
 *   Representa a entidade 'Balão' do negócio: suas propriedades (cor, tamanho, tipo de personalização, preço) e comportamentos próprios da entidade em si — nunca acesso a banco.
 *
 * Permitido conter:
 *   Propriedades da entidade, métodos que operam apenas sobre os próprios dados do objeto (ex.: calcularPrecoBase()).
 *
 * Proibido conter:
 *   Query SQL (isso é do Repository), regra de negócio complexa que envolva outras entidades (isso é do Service).
 *
 * Depende de:
 *   Nenhuma (entidade pura).
 *
 * Usado por:
 *   app/Repositories/BalaoRepository.php, app/Services/PersonalizacaoService.php
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

namespace App\Models;

class Balao
{
    // Propriedades como $cor, $tamanho, $tipoPersonalizacao, $preco
    // serão definidas na etapa de desenvolvimento.
}
