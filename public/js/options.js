function getCountryOptions() {
  let options = "";
  for (let i = 0; i < countriesData.length; i++) {
    const country = countriesData[i];
    options += `
    <div class="option">
      <input type="radio" name="region_code" id='country-${country.code}' value='${country.code}' />
      <label for='country-${country.code}'>
        <img class="flag" src="https://flagsapi.com/${country.code}/flat/24.png" />
        ${country.name}
      </label>
   </div>
  `;
  }
  return options;
}

function getOptions(type) {
  switch (type) {
    case "country":
      return getCountryOptions();
  }
}

function insertOptions(select) {
  if (!select.dataset.insertoptions) return;
  
  const options = getOptions(select.id);
  const selectBox = select.querySelector(".select-box");
  selectBox.innerHTML += options;
  checkFirstOption(select);
  setSelectedItem(select);
}

selects.forEach((select) => insertOptions(select));
