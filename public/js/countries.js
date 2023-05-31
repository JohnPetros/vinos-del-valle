const countriesData = [
  { name: "Brasil", code: "BR", color_hex: "#F9C400" },
  { name: "Espanha", code: "ES", color_hex: "#C60B1E" },
  { name: "França", code: "FR", color_hex: "#0055A4" },
  { name: "Itália", code: "IT", color_hex: "#009246" },
  { name: "Estados Unidos", code: "US", color_hex: "#14149F" },
  { name: "Alemanha", code: "DE", color_hex: "#000000" },
  { name: "Japão", code: "JP", color_hex: "#E6E6E6" },
  { name: "Canadá", code: "CA", color_hex: "#FF9999" },
  { name: "Austrália", code: "AU", color_hex: "#B8C6E3" },
  { name: "Argentina", code: "AR", color_hex: "#75AADB" },
  { name: "México", code: "MX", color_hex: "#006847" },
  { name: "China", code: "CN", color_hex: "#EC7909" },
];
const countries = document.querySelectorAll(".country");

function getCoutryName(code) {
  return countriesData.find((country) => country.code === code).name;
}

function getCountryColor(color) {
  return countriesData.find((country) => country.color === color).color;
}

function handleCountries(country) {
  country.textContent = getCoutryName(country.dataset.code);

  if (country.hasAttribute("data-country-color")) {
    country.setAtribute(country.dataset.countryColor, getCountryColor());
    console.log(country);
    setColor(country);
  }
}

countries.forEach(handleCountries);
