'use strict';

// Fetch some elements from the DOM
const BUTTON_CONNECT = document.getElementById("connexion");
const BUTTON_REGISTER = document.getElementById("redirection");
const CROSS_1 = document.getElementById("cross");
const CROSS_2 = document.getElementById("cross2");
const CROSS_3 = document.getElementById("cross3");
const CROSS_4 = document.getElementById("cross4");
const MODAL = document.getElementById("modal")
const MODAL_CONNECT = document.getElementById("connexion-modal");
const MODAL_REGISTER = document.getElementById("inscription-modal");
const VIEW = document.getElementsByClassName("view");
const MODAL_GRAPH = document.getElementById("modalgraph");
const MODAL_MODIFY = document.getElementById("modalmodify");
const INFO = document.getElementById("info")
const BUTTON_MODIFY = document.getElementById("buttonModify");

// Fetch some more
const DISCONNECT = document.getElementById("isConnect");
const REGISTER = document.getElementById("buttonRegister");
const CONNECT = document.getElementById("buttonConnect");
const USERNAME = document.getElementsByClassName("username");
const PASSWORD = document.getElementsByClassName("password");
const ERROR = document.getElementsByClassName("error");



/**
 * Hide all modals
 *
 */
function all_none() {
  MODAL.style.display = "none"
  MODAL_CONNECT.style.display = "none";
  MODAL_REGISTER.style.display = "none";
  MODAL_GRAPH.style.display = "none";
  MODAL_MODIFY.style.display = "none";
}


// Leave the modal when window is clicked
window.onclick = (e) => {
  if (e.target == MODAL)
    all_none();
};


// Clicking on a cross hide everything
CROSS_1.onclick = all_none;
CROSS_2.onclick = all_none;
CROSS_3.onclick = all_none;
CROSS_4.onclick = all_none;


// Display connection modal
BUTTON_CONNECT.onclick =  () => {
  MODAL.style.display = "block";
  MODAL_CONNECT.style.display = "flex";
  USERNAME[0].value ='';
  PASSWORD[0].value ='';
  ERROR[1].innerHTML="";
}


// Display register modal
BUTTON_REGISTER.onclick = () => {
  MODAL_CONNECT.style.display = "none";
  MODAL_REGISTER.style.display = "flex";
  ERROR[2].innerHTML="";
}


// When clicking on disconnect
DISCONNECT.onclick = () => { ajax_req('POST','php/request.php/logout/',is_disconnect); }


// When clicking on connect
CONNECT.onclick = () => {

  // Check all text fields are filled
  if(USERNAME[0].value === "" || PASSWORD[0].value === "")
    ERROR[1].innerHTML = "Please complete all the text field"

  // Else submit request
  else
    ajax_req('POST','php/request.php/login/', is_login, `username=${USERNAME[0].value}&&pwd=${PASSWORD[0].value}`)
}


// When clicking on register
REGISTER.onclick = () => {

  // Check password are same
  if(PASSWORD[1].value != PASSWORD[2].value)
    ERROR[2].innerHTML ="Please confirm your password";

  // Check everything is filled
  else if(USERNAME[1].value === "" && PASSWORD[1].value === "" && PASSWORD[2].value === "")
    ERROR[2].innerHTML ="Please complete all the text field";

  // Send request
  else
    ajax_req('POST','php/request.php/register', is_register, `username=${USERNAME[1].value}&&pwd=${PASSWORD[1].value}`)
}


// Callback on login request
function is_login(data){

  if (data) {
    MODAL.style.display = "none"
    MODAL_CONNECT.style.display = "none";
    MODAL_REGISTER.style.display = "none";
    DISCONNECT.style.display = "block";
    BUTTON_CONNECT.style.display = "none";
    ajax_req('GET','php/request.php/dives', display_public_card);
  }

  else
    ERROR[1].innerHTML = "An error in your identifiers";
}


// Callback on disconnect
function is_disconnect(_){
  DISCONNECT.style.display = "none";
  BUTTON_CONNECT.style.display = "block";
}



// Callback on register
function is_register(data){

  if (data) {
    MODAL.style.display = "block"
    MODAL_CONNECT.style.display = "flex";
    MODAL_REGISTER.style.display = "none";
    ERROR[1].innerHTML = "";
  }

  else
    ERROR[2].innerHTML = "Your username is already used"
}
