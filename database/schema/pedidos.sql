-- Arquivo: pedidos.sql
-- Camada: database/schema
--
-- Responsabilidade:
--   Estrutura real da tabela `pedidos`, migrada da tabela `orders` do
--   projeto original "pitter-pan-totem" (usada por api/orders.php),
--   renomeada para seguir a convenção do sistema (tabelas em
--   português, snake_case, plural — ver docs/padroes-backend.md).
--   Os nomes das COLUNAS foram mantidos em inglês, exatamente como no
--   projeto original, para não exigir alteração de nenhuma query já
--   escrita em app/Repositories/PedidoRepository.php.
--
-- Usado por: app/Repositories/PedidoRepository.php
--
-- NOTA: Este CREATE TABLE reflete fielmente os campos já usados pelo
-- código migrado. Ainda não foi executado em nenhum banco por esta
-- entrega — cabe à etapa de implantação rodar esta migration.

CREATE TABLE IF NOT EXISTS pedidos (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_code         VARCHAR(10)     NULL,
    occasion_id        VARCHAR(60)     NOT NULL,
    occasion_name      VARCHAR(120)    NOT NULL,
    model_id           VARCHAR(60)     NOT NULL,
    model_name         VARCHAR(120)    NOT NULL,
    balloon_text       VARCHAR(45)     NULL,
    font_id            VARCHAR(60)     NOT NULL,
    font_name          VARCHAR(120)    NOT NULL,
    balloon_color_id   VARCHAR(60)     NOT NULL,
    balloon_color_name VARCHAR(120)    NOT NULL,
    text_color_id      VARCHAR(60)     NOT NULL,
    text_color_name    VARCHAR(120)    NOT NULL,
    quantity           INT UNSIGNED    NOT NULL,
    unit_price         DECIMAL(10,2)   NOT NULL,
    total_price        DECIMAL(10,2)   NOT NULL,
    status              ENUM('recebido','producao','pronto','finalizado','cancelado')
                         NOT NULL DEFAULT 'recebido',
    created_at         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uk_pedidos_order_code (order_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
