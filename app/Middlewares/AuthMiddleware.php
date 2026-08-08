<?php

/**
 * Arquivo: AuthMiddleware.php
 * Camada: Middlewares
 *
 * Responsabilidade:
 *   Intercepta a requisição antes do Controller para checar se o usuário está autenticado, redirecionando ou bloqueando quando necessário.
 *
 * Permitido conter:
 *   Checagem de sessão/token, redirecionamento.
 *
 * Proibido conter:
 *   Regra de negócio do domínio, acesso a banco fora de checagem de sessão.
 *
 * Depende de:
 *   app/Security/TokenManager.php
 *
 * Usado por:
 *   routes/web.php, routes/api.php (aplicado às rotas que exigem login)
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

namespace App\Middlewares;

class AuthMiddleware
{
    // Método handle() será implementado na etapa de desenvolvimento.
}
