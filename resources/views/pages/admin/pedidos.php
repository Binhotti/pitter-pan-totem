<?php

declare(strict_types=1);

/**
 * Arquivo: pedidos.php
 * Camada: resources/views/pages/admin
 *
 * Responsabilidade:
 *   Painel administrativo de pedidos do totem — lista, filtra, edita
 *   status/quantidade/preço e exclui pedidos. HTML original do
 *   admin/index.php do projeto "pitter-pan-totem", MIGRADO sem
 *   alteração de estrutura/classes/IDs — apenas caminhos de assets e o
 *   link "Abrir totem" foram atualizados para as novas rotas.
 *
 * Toda a interatividade (buscar, editar, excluir) é feita em
 *   /js/modules/admin/app.js, que consome /api/pedidos
 *   (app/Controllers/Api/PedidoApiController.php).
 *
 * Segurança:
 *   Esta página deve ser protegida por app/Middlewares/AuthMiddleware
 *   quando a autenticação for implementada (ver routes/web.php) — hoje
 *   ainda não há login no sistema, então a rota está aberta.
 *
 * Renderizada por: app/Controllers/Http/Admin/PedidoAdminController.php
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pitter Pan | Administração</title>
  <script>
    window.APP_BASE_PATH = <?= json_encode(APP_BASE_PATH, JSON_UNESCAPED_SLASHES) ?>;
  </script>
  <link rel="stylesheet" href="<?= APP_BASE_PATH ?>/css/modules/admin/style.css">
</head>
<body>
  <header class="admin-header">
    <div>
      <p class="eyebrow">Pitter Pan Festas</p>
      <h1>Pedidos do Totem</h1>
    </div>
    <a class="button button--secondary" href="<?= APP_BASE_PATH ?>/totem">Abrir totem</a>
  </header>

  <main class="admin-main">
    <section class="metrics" id="metrics">
      <article><span>Pedidos</span><strong id="metricOrders">0</strong></article>
      <article><span>Em produção</span><strong id="metricProduction">0</strong></article>
      <article><span>Prontos</span><strong id="metricReady">0</strong></article>
      <article><span>Faturamento</span><strong id="metricRevenue">R$ 0,00</strong></article>
    </section>

    <section class="toolbar">
      <input id="searchInput" type="search" placeholder="Buscar por senha, ocasião, modelo ou texto">
      <select id="statusFilter">
        <option value="">Todos os status</option>
        <option value="recebido">Recebido</option>
        <option value="producao">Em produção</option>
        <option value="pronto">Pronto</option>
        <option value="finalizado">Finalizado</option>
        <option value="cancelado">Cancelado</option>
      </select>
      <button class="button" id="refreshButton" type="button">Atualizar</button>
    </section>

    <section class="table-card">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Senha</th>
              <th>Pedido</th>
              <th>Personalização</th>
              <th>Qtd.</th>
              <th>Total</th>
              <th>Status</th>
              <th>Criado em</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="ordersTableBody"></tbody>
        </table>
      </div>
      <p class="empty-state hidden" id="emptyState">Nenhum pedido encontrado.</p>
    </section>
  </main>

  <dialog id="editDialog">
    <form method="dialog" id="editForm">
      <div class="dialog-heading">
        <div>
          <p class="eyebrow">Editar pedido</p>
          <h2 id="dialogOrderCode">Pedido</h2>
        </div>
        <button class="close-button" value="cancel" aria-label="Fechar">×</button>
      </div>

      <input type="hidden" id="editId">

      <label>
        Texto do balão
        <input id="editMessage" maxlength="45">
      </label>

      <label>
        Quantidade
        <input id="editQuantity" type="number" min="1" max="20" required>
      </label>

      <label>
        Preço unitário
        <input id="editUnitPrice" type="number" min="0" step="0.01" required>
      </label>

      <label>
        Status
        <select id="editStatus" required>
          <option value="recebido">Recebido</option>
          <option value="producao">Em produção</option>
          <option value="pronto">Pronto</option>
          <option value="finalizado">Finalizado</option>
          <option value="cancelado">Cancelado</option>
        </select>
      </label>

      <div class="dialog-actions">
        <button class="button button--secondary" value="cancel">Cancelar</button>
        <button class="button" id="saveEditButton" type="submit">Salvar alterações</button>
      </div>
    </form>
  </dialog>

  <script src="<?= APP_BASE_PATH ?>/js/modules/admin/app.js"></script>
</body>
</html>
