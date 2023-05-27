const logoutButton = document.querySelector("button#logout");
const modal = document.querySelector("#modal-header");

function handleLogoutButtonClick() {
  modal.showModal();
}

logoutButton.addEventListener("click", handleLogoutButtonClick);
console.log(logoutButton);

