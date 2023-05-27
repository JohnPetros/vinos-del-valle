const colors = document.querySelectorAll("[data-color]");

console.log({ colors });

function applyColor(color) {
  const [prop, colorHex] = color.dataset.color.split(":");
  color.style[prop] = colorHex;
}

colors.forEach(applyColor);
