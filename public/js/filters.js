const categories = document.querySelectorAll(".category");
const selectOptions = document.querySelectorAll(".option");
const selectedItems = document.querySelectorAll(".selected-item");

function removeActive(category) {
  category.classList.remove("active");
  category.style.backgroundColor = "var(--base-1)";
}

function addActive(category) {
  categories.forEach(removeActive);
  category.classList.add("active");
  category.style.backgroundColor = category.dataset.color;
}

function filterData() {
  const selectParams = [...selectedItems].map(getSelectParam);
  const categoryParam = getCategoryParam();

  const queryParams = selectParams.concat(categoryParam).join("&");
  location.href = `/dashboard/wine?${queryParams}`;
}

function handleCategoryClick({ currentTarget }) {
  addActive(currentTarget);
  filterData();
}

function getSelectParam(selectedItem) {
  const param = selectedItem.id;
  return `${param}=${selectedItem.dataset.value.trim()}`;
}

function getCategoryParam() {
  const activeCategory = [...categories].find((category) =>
    category.classList.contains("active")
  );
  if (!activeCategory) return;

  const param = activeCategory.classList[1];
  return `${param}=${activeCategory.id.trim()}`;
}

function handleSelectOptionClick() {
  filterData();
}

categories.forEach((button) => {
  button.addEventListener("click", handleCategoryClick);
  button.addEventListener("mouseover", ({ currentTarget }) =>
    addActive(currentTarget)
  );
  button.addEventListener("mouseout", ({ currentTarget }) =>
    removeActive(currentTarget)
  );
});

selectOptions.forEach((option) =>
  option.addEventListener("click", handleSelectOptionClick)
);
