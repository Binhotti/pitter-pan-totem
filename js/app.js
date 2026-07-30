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

const screenOrder = [
  "welcome",
  "theme",
  "model",
  "customize",
  "summary",
  "finish"
];

const progressScreens = [
  "theme",
  "model",
  "customize",
  "summary"
];

let finishTimeout = null;

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
  clearFinishTimeout();

  state.currentScreen = screenName;

  elements.screens.forEach((screen) => {
    const isCurrentScreen =
      screen.dataset.screen === screenName;

    screen.classList.toggle(
      "screen--active",
      isCurrentScreen
    );
  });

  const isWelcome = screenName === "welcome";
  const isFinish = screenName === "finish";

  const progressIndex =
    progressScreens.indexOf(screenName);

  elements.backButton.classList.toggle(
    "hidden",
    isWelcome || isFinish
  );

  elements.restartButton.classList.toggle(
    "hidden",
    isWelcome
  );

  elements.progress.classList.toggle(
    "hidden",
    progressIndex === -1
  );

  if (progressIndex >= 0) {
    const currentStep = progressIndex + 1;
    const totalSteps = progressScreens.length;

    elements.progressText.textContent =
      `Etapa ${currentStep} de ${totalSteps}`;

    elements.progressBar.style.width =
      `${(currentStep / totalSteps) * 100}%`;
  }

  if (screenName === "customize") {
    syncControls();
    updatePreview(true);
  }

  if (screenName === "summary") {
    updateSummary();
  }

  if (screenName === "finish") {
    startFinishTimeout();
  }

  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
}

function startFinishTimeout() {
  finishTimeout = setTimeout(() => {
    returnToMenu();
  }, 10000);
}

function clearFinishTimeout() {
  if (finishTimeout) {
    clearTimeout(finishTimeout);
    finishTimeout = null;
  }
}

function returnToMenu() {
  clearFinishTimeout();
  resetState();
  showScreen("welcome");
}

function goBack() {
  const currentIndex =
    screenOrder.indexOf(state.currentScreen);

  if (currentIndex > 0) {
    showScreen(
      screenOrder[currentIndex - 1]
    );
  }
}

function goNext() {
  const currentIndex =
    screenOrder.indexOf(state.currentScreen);

  if (
    currentIndex <
    screenOrder.length - 1
  ) {
    showScreen(
      screenOrder[currentIndex + 1]
    );
  }
}

function createOptionCard(item, type) {
  const button =
    document.createElement("button");

  button.type = "button";
  button.className = "option-card";
  button.dataset.id = item.id;

  button.innerHTML = `
        <span class="option-card__icon">
            <i data-lucide="${item.icon}"></i>
        </span>

    <span class="option-card__text">
      <strong>${item.name}</strong>

      ${item.description
      ? `<span>${item.description}</span>`
      : ""
    }
    </span>

      <span class="option-card__check">
          <i data-lucide="check"></i>
      </span>
  `;

  button.addEventListener(
    "click",
    () => {
      if (type === "theme") {
        selectTheme(item);
      }

      if (type === "model") {
        selectModel(item);
      }
    }
  );

  return button;
}

function selectTheme(theme) {
  state.themeId = theme.id;

  selectCard(
    elements.themeGrid,
    theme.id
  );

  const nextButton =
    getNextButton("theme");

  if (nextButton) {
    nextButton.disabled = false;
  }
}

function selectModel(model) {
  state.modelId = model.id;

  if (model.id === "bubble") {
    state.balloonColorId =
      "transparente";
  } else {
    state.balloonColorId =
      getClosestModelColor(model);
  }

  selectCard(
    elements.modelGrid,
    model.id
  );

  const nextButton =
    getNextButton("model");

  if (nextButton) {
    nextButton.disabled = false;
  }

  if (elements.modelPreviewName) {
    elements.modelPreviewName.textContent =
      model.name;
  }

  syncControls();

  renderBalloon(
    elements.modelPreview,
    getCurrentConfiguration(),
    true
  );

  updatePreview(true);
}

function getClosestModelColor(model) {
  if (!model) {
    return "rosa";
  }

  const matchingColor =
    APP_DATA.balloonColors.find(
      (color) => {
        return (
          color.value.toLowerCase() ===
          model.defaultColor.toLowerCase()
        );
      }
    );

  return matchingColor?.id || "rosa";
}

function selectCard(
  container,
  selectedId
) {
  if (!container) return;

  container
    .querySelectorAll(".option-card")
    .forEach((card) => {
      const isSelected =
        card.dataset.id === selectedId;

      card.classList.toggle(
        "option-card--selected",
        isSelected
      );
    });
}

function getNextButton(screenName) {
  return document.querySelector(
    `[data-screen="${screenName}"] [data-action="next"]`
  );
}

function renderInitialOptions() {
  APP_DATA.themes.forEach(
    (theme) => {
      const card =
        createOptionCard(
          theme,
          "theme"
        );

      elements.themeGrid.appendChild(
        card
      );
    }
  );

  APP_DATA.models.forEach(
    (model) => {
      const card =
        createOptionCard(
          model,
          "model"
        );

      elements.modelGrid.appendChild(
        card
      );
    }
  );

  APP_DATA.fonts.forEach(
    (font) => {
      const button =
        document.createElement(
          "button"
        );

      button.type = "button";
      button.className =
        "font-button";
      button.dataset.id = font.id;
      button.style.fontFamily =
        font.family;
      button.textContent = font.name;

      button.addEventListener(
        "click",
        () => {
          state.fontId = font.id;

          syncControls();
          updatePreview(true);
        }
      );

      elements.fontOptions.appendChild(
        button
      );
    }
  );

  renderColorButtons(
    elements.balloonColorOptions,
    APP_DATA.balloonColors,
    "balloon"
  );

  renderColorButtons(
    elements.textColorOptions,
    APP_DATA.textColors,
    "text"
  );

  lucide.createIcons();
}

function renderColorButtons(
  container,
  colors,
  type
) {
  colors.forEach((color) => {
    const button =
      document.createElement("button");

    button.type = "button";
    button.className = "color-button";
    button.dataset.id = color.id;
    button.title = color.name;

    button.setAttribute(
      "aria-label",
      color.name
    );

    button.style.setProperty(
      "--swatch",
      color.value
    );

    button.addEventListener(
      "click",
      () => {
        if (type === "balloon") {
          state.balloonColorId =
            color.id;
        }

        if (type === "text") {
          state.textColorId =
            color.id;
        }

        syncControls();
        updatePreview(true);
      }
    );

    container.appendChild(button);
  });
}

function syncControls() {
  if (elements.messageInput) {
    elements.messageInput.value =
      state.message;
  }

  if (elements.characterCounter) {
    elements.characterCounter.textContent =
      `${state.message.length}/45`;
  }

  if (elements.quantityOutput) {
    elements.quantityOutput.textContent =
      state.quantity;
  }

  elements.fontOptions
    .querySelectorAll(".font-button")
    .forEach((button) => {
      button.classList.toggle(
        "font-button--selected",
        button.dataset.id ===
        state.fontId
      );
    });

  elements.balloonColorOptions
    .querySelectorAll(".color-button")
    .forEach((button) => {
      button.classList.toggle(
        "color-button--selected",
        button.dataset.id ===
        state.balloonColorId
      );
    });

  elements.textColorOptions
    .querySelectorAll(".color-button")
    .forEach((button) => {
      button.classList.toggle(
        "color-button--selected",
        button.dataset.id ===
        state.textColorId
      );
    });

  if (elements.decreaseQuantity) {
    elements.decreaseQuantity.disabled =
      state.quantity <= 1;
  }

  if (elements.increaseQuantity) {
    elements.increaseQuantity.disabled =
      state.quantity >= 20;
  }
}

function updatePreview(
  animate = false
) {
  if (!elements.balloonPreview) {
    return;
  }

  renderBalloon(
    elements.balloonPreview,
    getCurrentConfiguration(),
    animate
  );
}

function updateSummary() {
  const theme =
    APP_DATA.themes.find(
      (item) =>
        item.id === state.themeId
    );

  const model =
    APP_DATA.models.find(
      (item) =>
        item.id === state.modelId
    );

  const font =
    APP_DATA.fonts.find(
      (item) =>
        item.id === state.fontId
    );

  const balloonColor =
    APP_DATA.balloonColors.find(
      (item) =>
        item.id ===
        state.balloonColorId
    );

  const textColor =
    APP_DATA.textColors.find(
      (item) =>
        item.id ===
        state.textColorId
    );

  const total =
    (model?.basePrice || 0) *
    state.quantity;

  document.querySelector(
    "#summaryTheme"
  ).textContent =
    theme?.name || "—";

  document.querySelector(
    "#summaryModel"
  ).textContent =
    model?.name || "—";

  document.querySelector(
    "#summaryMessage"
  ).textContent =
    state.message.trim() ||
    "Sem texto";

  document.querySelector(
    "#summaryFont"
  ).textContent =
    font?.name || "—";

  document.querySelector(
    "#summaryBalloonColor"
  ).textContent =
    balloonColor?.name || "—";

  document.querySelector(
    "#summaryTextColor"
  ).textContent =
    textColor?.name || "—";

  document.querySelector(
    "#summaryQuantity"
  ).textContent =
    String(state.quantity);

  document.querySelector(
    "#summaryPrice"
  ).textContent =
    formatCurrency(total);

  renderBalloon(
    elements.summaryPreview,
    getCurrentConfiguration()
  );
}

function formatCurrency(value) {
  return new Intl.NumberFormat(
    "pt-BR",
    {
      style: "currency",
      currency: "BRL"
    }
  ).format(value);
}

function resetState() {
  state.currentScreen = "welcome";
  state.themeId = null;
  state.modelId = null;
  state.message = "";
  state.fontId = "elegante";
  state.balloonColorId =
    "transparente";
  state.textColorId = "preto";
  state.quantity = 1;

  selectCard(
    elements.themeGrid,
    ""
  );

  selectCard(
    elements.modelGrid,
    ""
  );

  const themeNextButton =
    getNextButton("theme");

  const modelNextButton =
    getNextButton("model");

  if (themeNextButton) {
    themeNextButton.disabled = true;
  }

  if (modelNextButton) {
    modelNextButton.disabled = true;
  }

  if (elements.modelPreview) {
    elements.modelPreview.innerHTML =
      "";
  }

  if (elements.modelPreviewName) {
    elements.modelPreviewName.textContent =
      "Escolha um modelo";
  }

  syncControls();
  updatePreview();
}

function resetCustomization() {
  const model =
    APP_DATA.models.find(
      (item) =>
        item.id === state.modelId
    );

  state.message = "";
  state.fontId = "elegante";
  state.textColorId = "preto";
  state.quantity = 1;

  if (model?.id === "bubble") {
    state.balloonColorId =
      "transparente";
  } else {
    state.balloonColorId =
      getClosestModelColor(
        model || APP_DATA.models[0]
      );
  }

  syncControls();
  updatePreview(true);
}

function generateOrderCode() {
  const number =
    Math.floor(
      Math.random() * 900
    ) + 100;

  return `A${number}`;
}

function confirmOrder() {
  const model =
    APP_DATA.models.find(
      (item) =>
        item.id === state.modelId
    );

  const total =
    (model?.basePrice || 0) *
    state.quantity;

  document.querySelector(
    "#orderCode"
  ).textContent =
    generateOrderCode();

  document.querySelector(
    "#finishQuantity"
  ).textContent =
    `${state.quantity} ${state.quantity === 1
      ? "balão"
      : "balões"
    }`;

  document.querySelector(
    "#finishPrice"
  ).textContent =
    formatCurrency(total);

  showScreen("finish");
}

function bindEvents() {
  elements.startButton.addEventListener(
    "click",
    () => {
      showScreen("theme");
    }
  );

  elements.backButton.addEventListener(
    "click",
    goBack
  );

  elements.restartButton.addEventListener(
    "click",
    () => {
      returnToMenu();
    }
  );

  document
    .querySelectorAll(
      '[data-action="back"]'
    )
    .forEach((button) => {
      button.addEventListener(
        "click",
        goBack
      );
    });

  document
    .querySelectorAll(
      '[data-action="next"]'
    )
    .forEach((button) => {
      button.addEventListener(
        "click",
        goNext
      );
    });

  elements.messageInput.addEventListener(
    "input",
    (event) => {
      state.message =
        event.target.value;

      elements.characterCounter.textContent =
        `${state.message.length}/45`;

      updatePreview();
    }
  );

  elements.decreaseQuantity.addEventListener(
    "click",
    () => {
      state.quantity = Math.max(
        1,
        state.quantity - 1
      );

      syncControls();
    }
  );

  elements.increaseQuantity.addEventListener(
    "click",
    () => {
      state.quantity = Math.min(
        20,
        state.quantity + 1
      );

      syncControls();
    }
  );

  elements.resetCustomization.addEventListener(
    "click",
    resetCustomization
  );

  elements.confirmOrderButton.addEventListener(
    "click",
    confirmOrder
  );

  elements.newOrderButton.addEventListener(
    "click",
    returnToMenu
  );
}

function initializeApp() {
  renderInitialOptions();
  bindEvents();
  syncControls();
  updatePreview();
  showScreen("welcome");
}

initializeApp();