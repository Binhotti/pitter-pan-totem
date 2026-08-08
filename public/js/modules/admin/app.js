const API_URL = `${window.APP_BASE_PATH || ""}/api/pedidos`;

const elements = {
  tableBody: document.querySelector("#ordersTableBody"),
  emptyState: document.querySelector("#emptyState"),
  searchInput: document.querySelector("#searchInput"),
  statusFilter: document.querySelector("#statusFilter"),
  refreshButton: document.querySelector("#refreshButton"),
  editDialog: document.querySelector("#editDialog"),
  editForm: document.querySelector("#editForm"),
  editId: document.querySelector("#editId"),
  editMessage: document.querySelector("#editMessage"),
  editQuantity: document.querySelector("#editQuantity"),
  editUnitPrice: document.querySelector("#editUnitPrice"),
  editStatus: document.querySelector("#editStatus"),
  dialogOrderCode: document.querySelector("#dialogOrderCode"),
  metricOrders: document.querySelector("#metricOrders"),
  metricProduction: document.querySelector("#metricProduction"),
  metricReady: document.querySelector("#metricReady"),
  metricRevenue: document.querySelector("#metricRevenue")
};

let orders = [];

function money(value) {
  return new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(Number(value));
}

function dateTime(value) {
  return new Intl.DateTimeFormat("pt-BR", { dateStyle: "short", timeStyle: "short" }).format(new Date(value.replace(" ", "T")));
}

function escapeHtml(value) {
  return String(value ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function statusLabel(status) {
  return ({ recebido: "Recebido", producao: "Em produção", pronto: "Pronto", finalizado: "Finalizado", cancelado: "Cancelado" })[status] || status;
}

async function loadOrders() {
  const params = new URLSearchParams();
  if (elements.searchInput.value.trim()) params.set("search", elements.searchInput.value.trim());
  if (elements.statusFilter.value) params.set("status", elements.statusFilter.value);

  const response = await fetch(`${API_URL}?${params.toString()}`);
  const result = await response.json();

  if (!response.ok || !result.success) {
    alert(result.message || "Não foi possível carregar os pedidos.");
    return;
  }

  orders = result.data;
  renderOrders();
  renderMetrics();
}

function renderOrders() {
  elements.tableBody.innerHTML = orders.map((order) => `
    <tr>
      <td><span class="order-code">${escapeHtml(order.order_code)}</span></td>
      <td><strong>${escapeHtml(order.occasion_name)}</strong><div class="muted">${escapeHtml(order.model_name)}</div></td>
      <td><strong>${escapeHtml(order.balloon_text || "Sem texto")}</strong><div class="muted">${escapeHtml(order.balloon_color_name)} · ${escapeHtml(order.text_color_name)} · ${escapeHtml(order.font_name)}</div></td>
      <td>${order.quantity}</td>
      <td><strong>${money(order.total_price)}</strong></td>
      <td><span class="status status--${escapeHtml(order.status)}">${statusLabel(order.status)}</span></td>
      <td>${dateTime(order.created_at)}</td>
      <td>
        <div class="actions">
          <button class="button button--secondary" data-edit="${order.id}">Editar</button>
          <button class="button button--danger" data-delete="${order.id}">Excluir</button>
        </div>
      </td>
    </tr>
  `).join("");

  elements.emptyState.classList.toggle("hidden", orders.length > 0);
}

function renderMetrics() {
  elements.metricOrders.textContent = orders.length;
  elements.metricProduction.textContent = orders.filter((order) => order.status === "producao").length;
  elements.metricReady.textContent = orders.filter((order) => order.status === "pronto").length;
  const revenue = orders.filter((order) => !["cancelado"].includes(order.status)).reduce((sum, order) => sum + Number(order.total_price), 0);
  elements.metricRevenue.textContent = money(revenue);
}

function openEdit(id) {
  const order = orders.find((item) => Number(item.id) === Number(id));
  if (!order) return;

  elements.editId.value = order.id;
  elements.dialogOrderCode.textContent = order.order_code;
  elements.editMessage.value = order.balloon_text;
  elements.editQuantity.value = order.quantity;
  elements.editUnitPrice.value = order.unit_price;
  elements.editStatus.value = order.status;
  elements.editDialog.showModal();
}

async function saveEdit(event) {
  event.preventDefault();

  const id = elements.editId.value;
  const response = await fetch(`${API_URL}?id=${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      message: elements.editMessage.value,
      quantity: Number(elements.editQuantity.value),
      unitPrice: Number(elements.editUnitPrice.value),
      status: elements.editStatus.value
    })
  });

  const result = await response.json();
  if (!response.ok || !result.success) {
    alert(result.message || "Não foi possível atualizar o pedido.");
    return;
  }

  elements.editDialog.close();
  await loadOrders();
}

async function deleteOrder(id) {
  if (!confirm("Deseja realmente excluir este pedido?")) return;

  const response = await fetch(`${API_URL}?id=${id}`, { method: "DELETE" });
  const result = await response.json();

  if (!response.ok || !result.success) {
    alert(result.message || "Não foi possível excluir o pedido.");
    return;
  }

  await loadOrders();
}

elements.tableBody.addEventListener("click", (event) => {
  const editButton = event.target.closest("[data-edit]");
  const deleteButton = event.target.closest("[data-delete]");
  if (editButton) openEdit(editButton.dataset.edit);
  if (deleteButton) deleteOrder(deleteButton.dataset.delete);
});

elements.editForm.addEventListener("submit", saveEdit);
elements.refreshButton.addEventListener("click", loadOrders);
elements.statusFilter.addEventListener("change", loadOrders);
let searchTimer;
elements.searchInput.addEventListener("input", () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(loadOrders, 300);
});

loadOrders();
