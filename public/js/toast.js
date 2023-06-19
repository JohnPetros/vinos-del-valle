const toast = document.querySelector(".toast");

function hideToast() {
  toast.classList.remove("active");
  setTimeout(() => toast.classList.add("hidden"), 500);
}

function showToast() {
  toast.classList.add("active");
  setTimeout(hideToast, 3000);
}

if (toast) {
  const toastButton = toast.querySelector("button");
  toastButton.addEventListener("click", hideToast);
  showToast();
}
