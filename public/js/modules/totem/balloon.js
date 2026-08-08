function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function splitMessageIntoLines(message, maxChars = 17) {
  const normalized = message.trim() || "Seu texto aqui";
  const words = normalized.split(/\s+/);
  const lines = [];
  let currentLine = "";

  words.forEach((word) => {
    const candidate = currentLine ? `${currentLine} ${word}` : word;

    if (candidate.length <= maxChars) {
      currentLine = candidate;
      return;
    }

    if (currentLine) {
      lines.push(currentLine);
    }

    currentLine = word;
  });

  if (currentLine) {
    lines.push(currentLine);
  }

  return lines.slice(0, 3);
}

function getShapeMarkup(shape, fill, opacity) {
  const common = `fill="${fill}" fill-opacity="${opacity}" stroke="rgba(32,43,114,.28)" stroke-width="4"`;

  if (shape === "heart") {
    return `
      <path ${common}
        d="M300 500
           C250 450 88 350 88 198
           C88 88 216 42 300 137
           C384 42 512 88 512 198
           C512 350 350 450 300 500Z" />
    `;
  }

  if (shape === "star") {
    return `
      <path ${common}
        d="M300 55
           L356 208
           L520 214
           L390 314
           L435 475
           L300 380
           L165 475
           L210 314
           L80 214
           L244 208Z" />
    `;
  }

  if (shape === "round") {
    return `<ellipse ${common} cx="300" cy="270" rx="216" ry="232" />`;
  }

  return `<circle ${common} cx="300" cy="270" r="225" />`;
}

function getStringMarkup(shape) {
  const startY = shape === "heart" ? 486 : shape === "star" ? 438 : 494;

  return `
    <path d="M300 ${startY} C288 528 318 552 300 592"
      fill="none" stroke="#8b8fa3" stroke-width="4" stroke-linecap="round"/>
    <path d="M289 ${startY + 2} L300 ${startY + 20} L311 ${startY + 2}Z"
      fill="#8b8fa3"/>
  `;
}

function getHighlightMarkup(shape) {
  if (shape === "star") {
    return `
      <path d="M250 126 L271 184" stroke="rgba(255,255,255,.62)"
        stroke-width="15" stroke-linecap="round"/>
    `;
  }

  if (shape === "heart") {
    return `
      <path d="M165 155 C135 195 145 244 176 272"
        fill="none" stroke="rgba(255,255,255,.56)"
        stroke-width="18" stroke-linecap="round"/>
    `;
  }

  return `
    <path d="M175 125 C128 168 112 225 121 282"
      fill="none" stroke="rgba(255,255,255,.62)"
      stroke-width="20" stroke-linecap="round"/>
  `;
}

function renderBalloon(container, configuration, animate = false) {
  if (!container) return;

  const model = APP_DATA.models.find((item) => item.id === configuration.modelId) || APP_DATA.models[0];
  const font = APP_DATA.fonts.find((item) => item.id === configuration.fontId) || APP_DATA.fonts[0];
  const balloonColor = APP_DATA.balloonColors.find((item) => item.id === configuration.balloonColorId);
  const textColor = APP_DATA.textColors.find((item) => item.id === configuration.textColorId) || APP_DATA.textColors[1];

  const fill = balloonColor?.value || model.defaultColor;
  const opacity = balloonColor?.opacity ?? model.opacity;
  const lines = splitMessageIntoLines(configuration.message);
  const lineHeight = 48;
  const textStartY = 270 - ((lines.length - 1) * lineHeight) / 2;

  const textMarkup = lines.map((line, index) => `
    <text
      x="300"
      y="${textStartY + index * lineHeight}"
      text-anchor="middle"
      dominant-baseline="middle"
      fill="${textColor.value}"
      font-family="${escapeHtml(font.family)}"
      font-size="${lines.length === 1 ? 42 : 36}"
      font-weight="700"
      paint-order="stroke"
      stroke="rgba(255,255,255,.16)"
      stroke-width="1.5"
    >${escapeHtml(line)}</text>
  `).join("");

  container.innerHTML = `
    <svg class="balloon-svg${animate ? " is-changing" : ""}"
      viewBox="0 0 600 620"
      role="img"
      aria-label="Prévia do balão personalizado">
      <defs>
        <filter id="softGlow">
          <feGaussianBlur stdDeviation="8" result="blur"/>
          <feMerge>
            <feMergeNode in="blur"/>
            <feMergeNode in="SourceGraphic"/>
          </feMerge>
        </filter>
      </defs>

      ${getStringMarkup(model.shape)}

      <g filter="url(#softGlow)">
        ${getShapeMarkup(model.shape, fill, opacity)}
        ${getHighlightMarkup(model.shape)}
      </g>

      <g>${textMarkup}</g>
    </svg>
  `;
}
