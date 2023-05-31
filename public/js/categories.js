const categoriesContainer = document.querySelector(".categories");

function getCountryCategories() {
  let categories = "";

  for (let i = 0; i < countriesData.length; i++) {
    categories += `
    <li>
      <button
        id='${countriesData[i].code}'
        class="category"
        data-color='${countriesData[i].color_hex}'
      >
        ${countriesData[i].name}
      </button>
  </li>`;
  }
  return categories;
}

function handleCategoriesId(id) {
  switch (id) {
    case "countries":
      return getCountryCategories();
  }
}

function addCategories() {
  const { id } = categoriesContainer;
  const categories = handleCategoriesId(id);
  categoriesContainer.innerHTML += categories;
}

addCategories();
