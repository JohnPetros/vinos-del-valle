const logoutButton = document.querySelector("button#logout");
const modals = document.querySelector(".modal");
const menu = document.querySelector(".menu");
const menuHamburguerButton = document.querySelector(".menu-hamburguer");
const menuCloseButton = document.querySelector(".menu-close");

function handleLogoutButtonClick() {
  openModal('logout');
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
