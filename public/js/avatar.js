const avatars = document.querySelectorAll(".avatar");

function setAvatar(avatar) {
  avatar.src = `${PUBLIC_PATH}/uploads/avatars/${avatar.dataset.value}`;
}

avatars.forEach(setAvatar);
