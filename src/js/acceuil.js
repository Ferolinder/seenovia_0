// DOM ELEMENT 
const CONNECT = document.getElementById("buttonConnect");

const USERNAME = document.getElementsByClassName("username");
const PASSWORD = document.getElementsByClassName("password");


// FUNCTION

CONNECT.onclick = () => {
  let invalid = false;

  [USERNAME[0], PASSWORD[0]].forEach(input => {
    const fieldWrapper = input.closest(".input");
    if (input.value.trim() === "") {
      invalid = true;
      fieldWrapper.classList.add("invalid");
      setTimeout(() => fieldWrapper.classList.remove("invalid"), 300);
    }
  });

  if (invalid) {
    return;
  }

  ajax_req(
    'GET',
    'php/request.php/account/',
    reactConnect,
    '&mail=' + USERNAME[0].value + '&mdp=' + PASSWORD[0].value
  );
}

function reactConnect(data) {
  if (data === 0 || data === '0') {
    showTemporaryModal('erreur dans la connection, vérifiez votre mail et mot de passe', 1000);
    return;
  }

  if (data === true || data === 'true') {
    openPageSeenovia();
    return;
  }

  const numericId = Number(data);
  if (!Number.isNaN(numericId) && numericId > 1) {
    openPageAgri(numericId);
    return;
  }

  showTemporaryModal('erreur dans la connection, vérifiez votre mail et mot de passe', 1000);
}

function openPageAgri(data) {
  window.location.href = `agri.php?user=${encodeURIComponent(data)}`;
}

function openPageSeenovia() {
  window.location.href = 'seenovia.php';
}

function showTemporaryModal(message, duration) {
  const modal = document.createElement('div');
  modal.classList.add('temporary-modal');
  modal.textContent = message;
  document.body.appendChild(modal);

  setTimeout(() => {
    modal.remove();
  }, duration);
}

function printf(data) {
  // ajax_req(
  //   'GET',
  //   'php/request.php/test/',
  //   test,
  //   '&tab=' + 'crops'
  // );

  console.log("Response received:", data);
}