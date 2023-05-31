const categories = document.querySelectorAll(".category");
const selectOptions = document.querySelectorAll(".option");

function removeActive(category) {
  category.classList.remove("active");
  category.style.backgroundColor = "var(--base-1)";
}

function addActive(category) {
  categories.forEach(removeActive);
  category.classList.add("active");
  category.style.backgroundColor = category.dataset.color;
}

function getSelectParam(select) {
  const param = select.id;
  const checkedOption = select.querySelector('input[type="radio"]:checked');
  return `${param}=${checkedOption.value.trim()}`;
}

function getCategoryParam() {
  const activeCategory = [...categories].find((category) =>
    category.classList.contains("active")
  );
  if (!activeCategory) return;

  const param = activeCategory.classList[0];
  return `${param}=${activeCategory.id.trim()}`;
}

function filterData() {
  const selectParams = [...selects].map(getSelectParam);
  const categoryParam = getCategoryParam();
  const queryParams = selectParams.concat(categoryParam).join("&");

  location.href = `/dashboard/wine?${queryParams}`;
}

function handleCategoryClick({ currentTarget }) {
  addActive(currentTarget);
  filterData();
}

function handleSelectOptionClick() {
  filterData();
}

function setSelectedCategory() {
  const categoryId = new URLSearchParams(location.search).get("category");
  const targetCategory = [...categories].find(
    (category) => category.id === categoryId
  );

  if (targetCategory) {
    addActive(targetCategory);
  } else {
    addActive(categories[0]);
  }
}

categories.forEach((category) => {
  category.addEventListener("click", handleCategoryClick);
});

selectOptions.forEach((option) =>
  option.addEventListener("click", handleSelectOptionClick)
);

if (categories.length) {
  setSelectedCategory();
}
