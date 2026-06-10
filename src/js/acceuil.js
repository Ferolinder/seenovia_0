// DOM ELEMENT 
const CONNECT = document.getElementById("buttonConnect");
const TOGGLE_PASSWORD = document.getElementById("togglePassword");

const USERNAME = document.getElementsByClassName("username");
const PASSWORD = document.getElementsByClassName("password");


// FUNCTION

// Toggle password visibility using images
if (TOGGLE_PASSWORD) {
  TOGGLE_PASSWORD.addEventListener('click', (e) => {
    e.preventDefault();
    const passwordInput = PASSWORD[0];
    const icon = TOGGLE_PASSWORD.querySelector('.toggle-icon');
    if (!passwordInput) return;
    if (passwordInput.type === 'password') {
      try {
        passwordInput.type = '';
      } catch (err) {
        passwordInput.type = 'text';
      }
      if (icon) icon.src = 'pictures/eye.png';
    } else {
      passwordInput.type = 'password';
      if (icon) icon.src = 'pictures/hidden.png';
    }
  });
}

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
  if (data === 0 || data === 'e0') {
    alert('erreur dans la connection, vérifiez votre mail et mot de passe');
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

  
}

function openPageAgri(data) {
  window.location.href = `agri.php?user=${encodeURIComponent(data)}`;
}

function openPageSeenovia() {
  window.location.href = `datas.php`;
}

function copyText(text) {
  navigator.clipboard.writeText(text)
    .then(() => {
      console.log("Texte copié :", text);
    })
    .catch(err => {
      console.error("Erreur de copie :", err);
    });
}