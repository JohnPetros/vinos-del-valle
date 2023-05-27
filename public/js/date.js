const dates = document.querySelectorAll(".date");

function formatDate(date) {
  const currentDate = date.dataset.date;
  const formatedDate = currentDate.split("-").reverse().join("/");
  date.textContent = formatedDate;
}

dates.forEach(formatDate);
