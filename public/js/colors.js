const colors = document.querySelectorAll("[data-color]");

function setColor(color) {
  const [prop, colorHex] = color.dataset.color.split(":");
  if (!prop || !colorHex) return;

  color.style[prop] =
    colorHex.length === 2 ? getCountryColor(colorHex) : colorHex;
}

colors.forEach(setColor);
