<?php

/**
 * Arquivo: database.php
 * Camada: Config
 *
 * Responsabilidade:
 *   Parâmetros de conexão com o MySQL, lidos das variáveis de ambiente
 *   (.env). Nenhuma credencial real vive aqui.
 *
 * Usado por:
 *   app/Bootstrap/Database.php (única classe que efetivamente abre a
 *   conexão PDO).
 *
 * Equivalente, no projeto original "pitter-pan-totem", ao antigo
 * config/database.php (que ficava fora do controle de versão e
 * expunha diretamente uma função global getPDO()). Aqui a mesma
 * responsabilidade existe, mas separada em duas peças: os DADOS de
 * configuração (este arquivo) e o COMPORTAMENTO de conexão
 * (app/Bootstrap/Database.php) — para não misturar configuração com
 * lógica, e para permitir trocar de driver/banco no futuro sem tocar
 * neste arquivo.
 */

return [
    'host'    => getenv('DB_HOST') ?: 'localhost',
    'porta'   => getenv('DB_PORT') ?: '4406',
    'banco'   => getenv('DB_DATABASE') ?: 'pitterpan_totem',
    'usuario' => getenv('DB_USERNAME') ?: 'root',
    'senha'   => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
];
