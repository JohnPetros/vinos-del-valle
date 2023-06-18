const cardsContainer = document.querySelector("#cards-container");
const cards = cardsContainer.querySelectorAll("[class$='-card']");
const paginationButtonsContainer = document.querySelector(
  ".pagination-buttons"
);

function desactivePaginationButton(button) {
  button.classList.remove("active");
}

function activePaginationButton(button) {
  button.classList.add("active");
}

function sliceCards(cards, startIndex) {
  cardsContainer.innerHTML = "";

  for (let i = startIndex; i < startIndex + 9; i++) {
    if (cards[i]) cardsContainer.appendChild(cards[i]);
  }
}

function applyPagination(pageNumber) {
  const paginationButtons =
    paginationButtonsContainer.querySelectorAll(".button");
  paginationButtons.forEach(desactivePaginationButton);

  const targetPaginationButton = paginationButtonsContainer.querySelector(
    `#page-${pageNumber}`
  );
  activePaginationButton(targetPaginationButton);

  sliceCards(cards, 9 * (pageNumber - 1));
}

function handlePaginationButtonClick({ currentTarget }) {
  applyPagination(currentTarget.textContent);
}

function addPaginationButtons(cards) {
  const pagesAmount = Math.ceil(cards.length / 9);

  for (let i = 1; i < pagesAmount + 1; i++) {
    const paginationButton = document.createElement("button");
    paginationButton.classList.add("page", "button");
    paginationButton.setAttribute("id", `page-${i}`);
    paginationButton.textContent = i;
    paginationButton.addEventListener("click", handlePaginationButtonClick);

    paginationButtonsContainer.appendChild(paginationButton);
  }
}

function setPagination() {
  if (cards.length > 9) {
    addPaginationButtons(cards);
    applyPagination(1);
  }
}

setTimeout(setPagination, 80) // bug fix
