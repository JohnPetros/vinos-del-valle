const toast = document.querySelector(".toast");
const toastButton = toast.querySelector("button");

function hideToast() {
  toast.classList.remove("active");
}

function showToast() {
  toast.classList.add("active");

  setTimeout(hideToast, 3000);
}

toastButton.addEventListener("click", hideToast);

showToast();
