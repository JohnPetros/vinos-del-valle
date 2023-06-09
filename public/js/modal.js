const confirmButtons = document.querySelectorAll(".modal .button.confirm");
const cancelButtons = document.querySelectorAll(".modal .button.cancel");

function openModal(id) {
  const modal = document.querySelector(`.modal#${id}`);
  modal.showModal();
}

function handleConfirmButton({ currentTarget }) {
  const { action } = currentTarget.dataset;
  location.href = action;
}

function handleCancelButton({ currentTarget }) {
  const modal = currentTarget.parentNode.parentNode;
  modal.close();
}

confirmButtons.forEach((button) =>
  button.addEventListener("click", handleConfirmButton)
);
cancelButtons.forEach((button) =>
  button.addEventListener("click", handleCancelButton)
);
