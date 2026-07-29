const state = {
  currentScreen: "welcome",
  themeId: null,
  modelId: null,
  message: "",
  fontId: "elegante",
  balloonColorId: "transparente",
  textColorId: "preto",
  quantity: 1
};

const screenOrder = ["welcome", "theme", "model", "customize", "summary", "finish"];
const progressScreens = ["theme", "model", "customize", "summary"];

const elements = {
  screens: [...document.querySelectorAll(".screen")],
  backButton: document.querySelector("#backButton"),
  restartButton: document.querySelector("#restartButton"),
  progress: document.querySelector("#progress"),
  progressText: document.querySelector("#progressText"),
  progressBar: document.querySelector("#progressBar"),
  startButton: document.querySelector("#startButton"),
  themeGrid: document.querySelector("#themeGrid"),
  modelGrid: document.querySelector("#modelGrid"),
  modelPreview: document.querySelector("#modelPreview"),
  modelPreviewName: document.querySelector("#modelPreviewName"),
  balloonPreview: document.querySelector("#balloonPreview"),
  summaryPreview: document.querySelector("#summaryPreview"),
  messageInput: document.querySelector("#messageInput"),
  characterCounter: document.querySelector("#characterCounter"),
  fontOptions: document.querySelector("#fontOptions"),
  balloonColorOptions: document.querySelector("#balloonColorOptions"),
  textColorOptions: document.querySelector("#textColorOptions"),
  quantityOutput: document.querySelector("#quantityOutput"),
  decreaseQuantity: document.querySelector("#decreaseQuantity"),
  increaseQuantity: document.querySelector("#increaseQuantity"),
  resetCustomization: document.querySelector("#resetCustomization"),
  confirmOrderButton: document.querySelector("#confirmOrderButton"),
  newOrderButton: document.querySelector("#newOrderButton")
};

function getCurrentConfiguration() {
  return {
    modelId: state.modelId || APP_DATA.models[0].id,
    message: state.message,
    fontId: state.fontId,
    balloonColorId: state.balloonColorId,
    textColorId: state.textColorId
  };
}

function showScreen(screenName) {
  state.currentScreen = screenName;

  elements.screens.forEach((screen) => {
    screen.classList.toggle("screen--active", screen.dataset.screen === screenName);
  });

  const isWelcome = screenName === "welcome";
  const isFinish = screenName === "finish";
  const progressIndex = progressScreens.indexOf(screenName);

  elements.backButton.classList.toggle("hidden", isWelcome || isFinish);
  elements.restartButton.classList.toggle("hidden", isWelcome);
  elements.progress.classList.toggle("hidden", progressIndex === -1);

  if (progressIndex >= 0) {
    const currentStep = progressIndex + 1;
    elements.progressText.textContent = `Etapa ${currentStep} de ${progressScreens.length}`;
    elements.progressBar.style.width = `${(currentStep / progressScreens.length) * 100}%`;
  }

  if (screenName === "summary") {
    updateSummary();
  }

  window.scrollTo({ top: 0, behavior: "smooth" });
}

function goBack() {
  const index = screenOrder.indexOf(state.currentScreen);

  if (index > 0) {
    showScreen(screenOrder[index - 1]);
  }
}

function goNext() {
  const index = screenOrder.indexOf(state.currentScreen);

  if (index < screenOrder.length - 1) {
    showScreen(screenOrder[index + 1]);
  }
}

function createOptionCard(item, type) {
  const button = document.createElement("button");
  button.type = "button";
  button.className = "option-card";
  button.dataset.id = item.id;
  button.innerHTML = `
    <span class="option-card__icon">${item.icon}</span>
    <span class="option-card__text">
      <strong>${item.name}</strong>
      <span>${item.description}</span>
    </span>
    <span class="option-card__check">✓</span>
  `;

  button.addEventListener("click", () => {
    if (type === "theme") {
      state.themeId = item.id;
      selectCard(elements.themeGrid, item.id);
      getNextButton("theme").disabled = false;
    }

    if (type === "model") {
      state.modelId = item.id;
      state.balloonColorId = item.id === "bubble" ? "transparente" : getClosestModelColor(item);
      selectCard(elements.modelGrid, item.id);
      getNextButton("model").disabled = false;
      elements.modelPreviewName.textContent = item.name;
      renderBalloon(elements.modelPreview, getCurrentConfiguration(), true);
      syncControls();
    }
  });

  return button;
}

function getClosestModelColor(model) {
  const match = APP_DATA.balloonColors.find((color) => color.value.toLowerCase() === model.defaultColor.toLowerCase());
  return match?.id || "rosa";
}

function selectCard(container, id) {
  container.querySelectorAll(".option-card").forEach((card) => {
    card.classList.toggle("option-card--selected", card.dataset.id === id);
  });
}

function getNextButton(screenName) {
  return document.querySelector(`[data-screen="${screenName}"] [data-action="next"]`);
}

function renderInitialOptions() {
  APP_DATA.themes.forEach((theme) => {
    elements.themeGrid.appendChild(createOptionCard(theme, "theme"));
  });

  APP_DATA.models.forEach((model) => {
    elements.modelGrid.appendChild(createOptionCard(model, "model"));
  });

  APP_DATA.fonts.forEach((font) => {
    const button = document.createElement("button");
    button.type = "button";
    button.className = "font-button";
    button.dataset.id = font.id;
    button.style.fontFamily = font.family;
    button.textContent = font.name;

    button.addEventListener("click", () => {
      state.fontId = font.id;
      syncControls();
      updatePreview(true);
    });

    elements.fontOptions.appendChild(button);
  });

  renderColorButtons(elements.balloonColorOptions, APP_DATA.balloonColors, "balloon");
  renderColorButtons(elements.textColorOptions, APP_DATA.textColors, "text");
}

function renderColorButtons(container, colors, type) {
  colors.forEach((color) => {
    const button = document.createElement("button");
    button.type = "button";
    button.className = "color-button";
    button.dataset.id = color.id;
    button.title = color.name;
    button.setAttribute("aria-label", color.name);
    button.style.setProperty("--swatch", color.value);

    button.addEventListener("click", () => {
      if (type === "balloon") state.balloonColorId = color.id;
      if (type === "text") state.textColorId = color.id;

      syncControls();
      updatePreview(true);
    });

    container.appendChild(button);
  });
}

function syncControls() {
  elements.messageInput.value = state.message;
  elements.characterCounter.textContent = `${state.message.length}/45`;
  elements.quantityOutput.textContent = state.quantity;

  elements.fontOptions.querySelectorAll(".font-button").forEach((button) => {
    button.classList.toggle("font-button--selected", button.dataset.id === state.fontId);
  });

  elements.balloonColorOptions.querySelectorAll(".color-button").forEach((button) => {
    button.classList.toggle("color-button--selected", button.dataset.id === state.balloonColorId);
  });

  elements.textColorOptions.querySelectorAll(".color-button").forEach((button) => {
    button.classList.toggle("color-button--selected", button.dataset.id === state.textColorId);
  });

  elements.decreaseQuantity.disabled = state.quantity <= 1;
}

function updatePreview(animate = false) {
  renderBalloon(elements.balloonPreview, getCurrentConfiguration(), animate);
}

function updateSummary() {
  const theme = APP_DATA.themes.find((item) => item.id === state.themeId);
  const model = APP_DATA.models.find((item) => item.id === state.modelId);
  const font = APP_DATA.fonts.find((item) => item.id === state.fontId);
  const balloonColor = APP_DATA.balloonColors.find((item) => item.id === state.balloonColorId);
  const textColor = APP_DATA.textColors.find((item) => item.id === state.textColorId);
  const total = (model?.basePrice || 0) * state.quantity;

  document.querySelector("#summaryTheme").textContent = theme?.name || "—";
  document.querySelector("#summaryModel").textContent = model?.name || "—";
  document.querySelector("#summaryMessage").textContent = state.message.trim() || "Sem texto";
  document.querySelector("#summaryFont").textContent = font?.name || "—";
  document.querySelector("#summaryBalloonColor").textContent = balloonColor?.name || "—";
  document.querySelector("#summaryTextColor").textContent = textColor?.name || "—";
  document.querySelector("#summaryQuantity").textContent = String(state.quantity);
  document.querySelector("#summaryPrice").textContent = formatCurrency(total);

  renderBalloon(elements.summaryPreview, getCurrentConfiguration());
}

function formatCurrency(value) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL"
  }).format(value);
}

function resetState() {
  state.currentScreen = "welcome";
  state.themeId = null;
  state.modelId = null;
  state.message = "";
  state.fontId = "elegante";
  state.balloonColorId = "transparente";
  state.textColorId = "preto";
  state.quantity = 1;

  selectCard(elements.themeGrid, "");
  selectCard(elements.modelGrid, "");
  getNextButton("theme").disabled = true;
  getNextButton("model").disabled = true;
  elements.modelPreview.innerHTML = "";
  elements.modelPreviewName.textContent = "Escolha um modelo";

  syncControls();
  updatePreview();
}

function resetCustomization() {
  const model = APP_DATA.models.find((item) => item.id === state.modelId);

  state.message = "";
  state.fontId = "elegante";
  state.balloonColorId = model?.id === "bubble" ? "transparente" : getClosestModelColor(model || APP_DATA.models[0]);
  state.textColorId = "preto";
  state.quantity = 1;

  syncControls();
  updatePreview(true);
}

function generateOrderCode() {
  const number = Math.floor(Math.random() * 900) + 100;
  return `A${number}`;
}

function confirmOrder() {
  const model = APP_DATA.models.find((item) => item.id === state.modelId);
  const total = (model?.basePrice || 0) * state.quantity;

  document.querySelector("#orderCode").textContent = generateOrderCode();
  document.querySelector("#finishQuantity").textContent =
    `${state.quantity} ${state.quantity === 1 ? "balão" : "balões"}`;
  document.querySelector("#finishPrice").textContent = formatCurrency(total);

  showScreen("finish");
}

function bindEvents() {
  elements.startButton.addEventListener("click", () => showScreen("theme"));
  elements.backButton.addEventListener("click", goBack);

  elements.restartButton.addEventListener("click", () => {
    resetState();
    showScreen("welcome");
  });

  document.querySelectorAll('[data-action="back"]').forEach((button) => {
    button.addEventListener("click", goBack);
  });

  document.querySelectorAll('[data-action="next"]').forEach((button) => {
    button.addEventListener("click", goNext);
  });

  elements.messageInput.addEventListener("input", (event) => {
    state.message = event.target.value;
    elements.characterCounter.textContent = `${state.message.length}/45`;
    updatePreview();
  });

  elements.decreaseQuantity.addEventListener("click", () => {
    state.quantity = Math.max(1, state.quantity - 1);
    syncControls();
  });

  elements.increaseQuantity.addEventListener("click", () => {
    state.quantity = Math.min(20, state.quantity + 1);
    syncControls();
  });

  elements.resetCustomization.addEventListener("click", resetCustomization);
  elements.confirmOrderButton.addEventListener("click", confirmOrder);

  elements.newOrderButton.addEventListener("click", () => {
    resetState();
    showScreen("theme");
  });
}

function initializeApp() {
  renderInitialOptions();
  bindEvents();
  syncControls();
  updatePreview();
  showScreen("welcome");
}

initializeApp();
