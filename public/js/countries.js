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

function getCountryColor(code) {
  return countriesData.find((country) => country.code === code).color_hex;
}

function handleCountries(country) {
  const { code } = country.dataset;
  country.textContent = getCoutryName(code);

  if (country.hasAttribute("data-countrycolor")) {
    country.setAttribute(
      "data-color",
      `${country.dataset.countrycolor}:${getCountryColor(code)}`
    );
    setColor(country);
    console.log(country.dataset);
  }
}

countries.forEach(handleCountries);
