<?php

/**
 * Arquivo: mail.php
 * Camada: Config
 *
 * Responsabilidade:
 *   Parâmetros de envio de e-mail (SMTP), usados futuramente para notificações de pedido, confirmação de personalização, etc.
 *
 * Permitido conter:
 *   Apenas pares chave-valor de configuração de e-mail.
 *
 * Proibido conter:
 *   Lógica de envio de e-mail.
 *
 * Depende de:
 *   Variáveis de ambiente definidas em .env.
 *
 * Usado por:
 *   app/Services/*.php que futuramente precisarem enviar notificações.
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural (parte da fundação do
 *   sistema Pitter Pan). Nenhuma funcionalidade, lógica de negócio,
 *   query SQL ou tela foi implementada nesta etapa — apenas a
 *   estrutura, a responsabilidade e a posição do arquivo dentro da
 *   arquitetura estão definidas aqui. A implementação virá em uma
 *   etapa de desenvolvimento futura.
 */

return [
    // 'host'  => getenv('MAIL_HOST'),
    // 'porta' => getenv('MAIL_PORT'),
    // 'de'    => getenv('MAIL_FROM'),
    // Valores reais serão definidos na etapa de desenvolvimento.
];
