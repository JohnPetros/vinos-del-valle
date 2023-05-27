const countriesData = [
  { name: "Brasil", code: "BR" },
  { name: "Espanha", code: "ES" },
  { name: "França", code: "FR" },
  { name: "Itália", code: "IT" },
  { name: "Estados Unidos", code: "US" },
  { name: "Alemanha", code: "DE" },
  { name: "Japão", code: "JP" },
  { name: "Canadá", code: "CA" },
  { name: "Austrália", code: "AU" },
  { name: "Argentina", code: "AR" },
  { name: "México", code: "MX" },
  { name: "China", code: "CN" },
];
const countries = document.querySelectorAll(".country");

function getCoutryName(code) {
  return countriesData.find((country) => country.code === code).name;
}

function addCountryName(country) {
  country.textContent = getCoutryName(country.dataset.code);
}

countries.forEach(addCountryName);
