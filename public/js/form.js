const form = document.querySelector("form");
const inputs = document.querySelectorAll(".input");
const inputsWrappers = document.querySelectorAll(".input-wrapper");
const selects = document.querySelectorAll(".select");
const selectsButtons = document.querySelectorAll(".select-button");
const inputColors = document.querySelectorAll(".input-color");
const buttons = document.querySelectorAll("form .button");
const portugueseNames = {
  email: "e-mail",
  password: "senha",
  name: "nome",
  winery: "vinícula",
  harvest_date: "data de colheita",
  bottling_date: "data de envase",
};

function getErrorMessage(text) {
  const errorMessage = document.createElement("p");
  errorMessage.classList.add("error");
  errorMessage.textContent = text;
  return errorMessage;
}

function showErrorMessage(input, text) {
  const inputWrapper = input.parentNode;
  const errorMessage = getErrorMessage(text);
  inputWrapper.insertAdjacentElement("afterend", errorMessage);
}

function validateEmptyField(input) {
  if (!input.value) {
    const errorEmptyMessageText = `Campo ${
      portugueseNames[input.name]
    } não pode estar vazio.`;
    showErrorMessage(input, errorEmptyMessageText);
  }
}

function validateEmail(input) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (input.value && !emailRegex.test(input.value)) {
    const errorEmailMessageText = `Este e-mail não é válido.`;
    showErrorMessage(input, errorEmailMessageText);
  }
}

function validatePassword(input) {
  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s])[A-Za-z\d\W\S]{6,}$/g;

  if (input.value && !passwordRegex.test(input.value)) {
    const errorEmailMessageText = `A senha deve conter pelo menos 6 caracteres, tendo no mínimo: uma maiúscula, uma minúscula e um caracter especial.`;
    showErrorMessage(input, errorEmailMessageText);
  }
}

function removeAllErrors() {
  const errors = form.querySelectorAll(".error");
  errors.forEach((error) => error.remove());
}

function hasErrors() {
  return !!form.querySelector(".error");
}

function validateInput(input) {
  validateEmptyField(input);

  switch (input.type) {
    case "email":
      validateEmail(input);
      break;
    case "password":
      validatePassword(input);
      break;
  }
}

function handleSubmit(event) {
  if (hasErrors()) removeAllErrors();

  inputs.forEach(validateInput);

  if (hasErrors()) {
    event.preventDefault();
    return;
  }

  if (!(event instanceof Event)) event.submit();
}

function activeIcons(icon) {
  icon.classList.add("active");
}

function desactiveIcons(icon) {
  icon.classList.remove("active");
}

function handleInputChange({ currentTarget }) {
  const inputWrapper = currentTarget.parentNode;
  const icons = inputWrapper.querySelectorAll("i");
  if (icons && currentTarget.value) {
    icons.forEach(activeIcons);
    inputWrapper.classList.add("active");
  } else {
    icons.forEach(desactiveIcons);
    inputWrapper.classList.remove("active");
  }
}

function handleInputWrapperClick({ currentTarget }) {
  const input = currentTarget.querySelector("input");
  input.focus();
}

function handlePasswordEyeClick({ currentTarget }) {
  const iconEye = currentTarget.querySelector("i");
  const isClosed = iconEye.classList[1] === "ph-eye-closed";
  iconEye.className = `ph-fill ph-eye${isClosed ? "" : "-closed"}`;

  const input = currentTarget.parentNode.querySelector("input");
  input.type = isClosed ? "password" : "text";
}

function openSelectBox(selectButton) {
  const selectBox = selectButton.parentNode.querySelector(".select-box");
  const isActive = selectBox.classList.contains("active");
  selectBox.classList[isActive ? "remove" : "add"]("active");
}

function activeOption(option) {
  const options = option.parentNode.querySelectorAll(".option");
  options.forEach((option) => option.classList.remove("active"));
  option.classList.add("active");
}

function checkOption(option, select) {
  const radio = option.querySelector('input[type="radio"]');
  const label = option.querySelector("label");
  radio.click();

  const selectedItem = select.querySelector(".selected-item");
  selectedItem.dataset.value = radio.value;
  selectedItem.innerHTML = label.innerHTML;

  activeOption(label.parentNode);
}

function handleSelectClick({ currentTarget }) {
  const selectButton = currentTarget;
  openSelectBox(selectButton);

  const options = currentTarget.parentNode.querySelectorAll(".option");
  options.forEach((option) =>
    option.addEventListener("click", ({ currentTarget }) =>
      checkOption(currentTarget, selectButton)
    )
  );
}

function setInputsWrappers(wrapper) {
  const passwordEye = wrapper.querySelector("#password-eye");
  if (passwordEye) {
    passwordEye.addEventListener("click", handlePasswordEyeClick);
  }

  wrapper.addEventListener("click", handleInputWrapperClick);
}

function checkFirstOption(select) {
  const firstOption = select.querySelector(".option");
  if (firstOption) checkOption(firstOption, select);
}

function hideSelectBox(select) {
  const selectBox = select.querySelector(".select-box");
  selectBox.classList.remove("active");
}

function containElement(element, select) {
  return select.contains(element);
}

function handleBodyClick({ target }) {
  const selects = document.querySelectorAll(".select");
  const canHideSelectBox = ![...selects].some((select) =>
    containElement(target, select)
  );

  if (canHideSelectBox) {
    selects.forEach(hideSelectBox);
  }
}

function setSelectedItem(select) {
  const selectItem = select.querySelector(".selected-item").dataset.selected;
  const targetOption = selectItem.includes("}")
    ? null
    : select.querySelector(`#${select.id}-${selectItem}`)?.parentNode;

  if (targetOption) {
    checkOption(targetOption, select);
  } else {
    checkFirstOption(select);
  }
}

function setColor(InputColor) {
  const input = InputColor.querySelector("input");
  const label = InputColor.querySelector("label");

  label.textContent = input.value;
  label.style.color = input.value;
}

function handleInputColorChange({ currentTarget }) {
  setColor(currentTarget);
}

function handleButtonClick({ currentTarget }) {
  const type = currentTarget.id;

  if (type === "add" || type === "edit") {
    form.action = currentTarget.value;
    handleSubmit(form);
  } else if (type === "delete") {
    openModal("delete");
  }
}

form.addEventListener("submit", handleSubmit);
inputs.forEach((input) => input.addEventListener("change", handleInputChange));
inputsWrappers.forEach(setInputsWrappers);
selects.forEach(checkFirstOption);
selectsButtons.forEach((select) => {
  select.addEventListener("click", handleSelectClick);
  setSelectedItem(select.parentNode);
});
inputColors.forEach((inputColor) => {
  setColor(inputColor);
  inputColor.addEventListener("change", handleInputColorChange);
});
buttons.forEach((button) =>
  button.addEventListener("click", handleButtonClick)
);
document.addEventListener("click", handleBodyClick);
