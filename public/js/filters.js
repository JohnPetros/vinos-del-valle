const categoriesButtons = document.querySelectorAll(".category");

function removeActive(category) {
  category.classList.remove("active");
  category.style.backgroundColor = "var(--base-1)";
}

function addActive(category) {
  category.classList.add("active");
  category.style.backgroundColor = category.dataset.color;
}

function handleCategoryButton({ currentTarget }) {
  categoriesButtons.forEach(removeActive);
  addActive(currentTarget);
}

categoriesButtons.forEach((button) =>
  button.addEventListener("click", handleCategoryButton)
);

addActive(categoriesButtons[0]);
