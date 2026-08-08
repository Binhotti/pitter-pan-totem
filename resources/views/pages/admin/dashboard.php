<?php

declare(strict_types=1);

/**
 * Arquivo: dashboard.php
 * Camada: resources/views/pages/admin
 *
 * Responsabilidade:
 *   Página de administração para gerentes/admins. Mostra apenas o faturamento
 *   e métricas relevantes, com botão para acessar a lista completa de pedidos.
 *
 * Renderizada por: app/Controllers/Http\Admin\AdminDashboardController.php
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
      <h1>Dashboard Administrativo</h1>
      <p>Faturamento e métricas gerais.</p>
    </div>
    <a class="button button--secondary" href="<?= APP_BASE_PATH ?>/admin/pedidos">Ver pedidos</a>
  </header>

  <main class="admin-main">
    <section class="metrics" id="metrics">
      <article><span>Pedidos</span><strong id="metricOrders">0</strong></article>
      <article><span>Em produção</span><strong id="metricProduction">0</strong></article>
      <article><span>Prontos</span><strong id="metricReady">0</strong></article>
      <article><span>Faturamento</span><strong id="metricRevenue">R$ 0,00</strong></article>
    </section>
  </main>

  <script>
    const API_URL = `${window.APP_BASE_PATH || ''}/api/pedidos`;

    async function loadMetrics() {
      const response = await fetch(API_URL);
      const result = await response.json();
      if (!response.ok || !result.success) {
        alert(result.message || 'Não foi possível carregar as métricas.');
        return;
      }

      const orders = result.data;
      document.querySelector('#metricOrders').textContent = orders.length;
      document.querySelector('#metricProduction').textContent = orders.filter((order) => order.status === 'producao').length;
      document.querySelector('#metricReady').textContent = orders.filter((order) => order.status === 'pronto').length;
      const revenue = orders.filter((order) => !['cancelado'].includes(order.status)).reduce((sum, order) => sum + Number(order.total_price), 0);
      document.querySelector('#metricRevenue').textContent = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(revenue);
    }

    loadMetrics();
  </script>
</body>
</html>
