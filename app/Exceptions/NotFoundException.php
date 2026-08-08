<?php

/**
 * Arquivo: NotFoundException.php
 * Camada: Exceptions
 *
 * Responsabilidade:
 *   Exceção específica para 'registro não encontrado' (ex.: pedido inexistente).
 *
 * Permitido conter:
 *   Apenas a definição da classe.
 *
 * Proibido conter:
 *   Lógica de negócio.
 *
 * Depende de:
 *   app/Exceptions/AppException.php.
 *
 * Usado por:
 *   app/Repositories/*.php, app/Services/*.php
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

namespace App\Exceptions;

class NotFoundException extends AppException
{
    // Estrutura-base; sem lógica adicional nesta etapa.
}
