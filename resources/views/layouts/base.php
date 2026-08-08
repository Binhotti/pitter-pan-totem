<?php

/**
 * Arquivo: base.php
 * Camada: resources/views/layouts
 *
 * Responsabilidade:
 *   Layout-mãe de toda página renderizada dinamicamente pelo PHP.
 *   Replica exatamente a sequência de <link>/<script> já documentada em
 *   docs/architecture.md (fontes -> vendor CSS -> css/base -> css/components
 *   -> css/layout -> css/sections -> print.css), agora gerada pelo PHP em
 *   vez de estática, apontando sempre para os arquivos reais que já
 *   existem em public/css/, public/js/ e public/vendor/ — nenhum asset é
 *   duplicado.
 *
 * Permitido conter:
 *   HTML da estrutura <head>/<body>, includes de partials (header, nav,
 *   footer), a variável $conteudo (ou equivalente) onde a página filha é
 *   injetada.
 *
 * Proibido conter:
 *   Regra de negócio, query SQL, lógica de validação.
 *
 * Depende de:
 *   public/css/**, public/js/**, public/vendor/**, resources/views/partials/*.
 *
 * Usado por:
 *   Todas as views em resources/views/pages/**.
 *
 * NOTA IMPORTANTE:
 *   Este arquivo é um esqueleto arquitetural. O HTML completo (idêntico
 *   ao <head> de public/index.html) será implementado na etapa de
 *   desenvolvimento — aqui apenas a posição e a responsabilidade do
 *   arquivo estão definidas.
 */
?>
<!-- Estrutura completa do <head> e includes de partials serão
     implementados na etapa de desenvolvimento, replicando fielmente
     public/index.html conforme docs/architecture.md. -->
