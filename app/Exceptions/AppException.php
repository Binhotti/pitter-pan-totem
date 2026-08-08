<?php

/**
 * Arquivo: AppException.php
 * Camada: Exceptions
 *
 * Responsabilidade:
 *   Exceção-base do sistema. Toda exceção customizada do projeto deve estender esta classe, permitindo tratamento centralizado.
 *
 * Permitido conter:
 *   Apenas a definição da classe base.
 *
 * Proibido conter:
 *   Lógica de negócio.
 *
 * Depende de:
 *   Extende \\Exception (nativa do PHP).
 *
 * Usado por:
 *   Qualquer camada que lance exceções.
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

class AppException extends \Exception
{
    // Estrutura-base; particularidades por tipo de erro ficam nas
    // subclasses (ex.: NotFoundException).
}
