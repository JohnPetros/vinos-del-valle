const logoutButton = document.querySelector("button#logout");
const profileButton = document.querySelector("button#profile");
const modals = document.querySelector(".modal");
const menu = document.querySelector(".menu");
const menuHamburguerButton = document.querySelector(".menu-hamburguer");
const menuCloseButton = document.querySelector(".menu-close");

function handleLogoutButtonClick() {
  openModal("logout");
}

function handleProfileButtonClick() {
  location.href = "/dashboard/profile";
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
profileButton.addEventListener("click", handleProfileButtonClick);
