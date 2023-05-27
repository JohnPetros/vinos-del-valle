const colors = document.querySelectorAll("[data-color]");

function setColor(color) {
  const [prop, colorHex] = color.dataset.color.split(":");
  color.style[prop] = colorHex;
}

colors.forEach(setColor);
