const logoutButton = document.querySelector("button#logout");
const modal = document.querySelector("#modal-header");
const menu = document.querySelector(".menu");
const menuHamburguerButton = document.querySelector(".menu-hamburguer");
const menuCloseButton = document.querySelector(".menu-close");

function handleLogoutButtonClick() {
  modal.showModal();
}

function handleMenuHamburguerButtonClick() {
  menu.classList.add("active");
}

function handleMenuCloseButtonClick() {
  menu.classList.remove("active");
}

menuHamburguerButton.addEventListener("click", handleMenuHamburguerButtonClick);
menuCloseButton.addEventListener("click", handleMenuCloseButtonClick);
logoutButton.addEventListener("click", handleLogoutButtonClick);
