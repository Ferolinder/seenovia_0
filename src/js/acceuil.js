'use strict';

// Fetch some elements from the DOM
const CONNECT = document.getElementById("buttonConnect");

const USERNAME = document.getElementsByClassName("username");
const PASSWORD = document.getElementsByClassName("password");

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

  // Else submit request
  ajax_req(
    'POST',
    'php/request.php/login/', 
    is_login, 
    `username=${encodeURIComponent(USERNAME[0].value)}&&pwd=${encodeURIComponent(PASSWORD[0].value)}`
  );
  console.log("Request sent");
}


function is_login(response) {
  if (response.success && response.redirect) {
    window.location.href = response.redirect;
    return;
  }

  const errorElement = document.querySelector(".error");
  errorElement.textContent = response.message || "Login failed";
  errorElement.classList.add("visible");
  setTimeout(() => errorElement.classList.remove("visible"), 3000);
}
