const form = document.querySelector("form");
const inputs = document.querySelectorAll(".input");
const inputsWrappers = document.querySelectorAll(".input-wrapper");
const portugueseNames = {
  email: "e-mail",
  password: "senha",
  name: "nome",
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

  if (hasErrors()) event.preventDefault();
}

function handleInputChange({ currentTarget }) {
  const icon = currentTarget.parentNode.querySelector("i");
  if (icon && currentTarget.value) {
    icon.classList.add("active");
  } else {
    icon.classList.remove("active");
  }
}

function handleInputWrapperClick({ currentTarget }) {
  const input = currentTarget.querySelector("input");
  input.focus();
}

form.addEventListener("submit", handleSubmit);
inputs.forEach((input) => input.addEventListener("change", handleInputChange));
inputsWrappers.forEach((wrapper) =>
  wrapper.addEventListener("click", handleInputWrapperClick)
);
