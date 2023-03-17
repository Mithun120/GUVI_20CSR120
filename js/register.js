// SECTION-START: DOCUMENT SELECTORS
const username = document.getElementById("username");
const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm-password");

const loginButtton = document.querySelector(".Login-button");

const errorMessage = document.querySelector(".err");

//SECTION-END: DOCUMENT QUERRY SELECTORS

// errorMessage.style.display = "none";
// SECTION-START: FUNCTIONS

const validateUserName = (value) => {
  if (value == "") return false;
  return true;
};
const validatePassword = (value) => {
  if (value == "") return false;
  return true;
};
// SECTION-END: FUNCTIONS

//SECTION-START: AJAX CALL

const registerUser = (username, password) => {
  var data = {
    username,
    password,
  };
  $.ajax({
    url: "/php/register.php",
    method: "POST",
    data: data,
    success: function (response) {
      // Handle the response from the server
      console.log("Response:", response);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Handle any errors that occurred during the request
      console.error("Error:", textStatus, errorThrown);
    },
  });
};

//SECTION-END: AJAX CALL

//SECTION-START: EVENT LISITONERS

loginButtton.addEventListener("click", (e) => {
  console.log("buttonclick");
  const usernameValue = username.value;
  const passwordValue = password.value;
  const confirmPasswordValue = confirmPassword.value;
  console.log(validateUserName(usernameValue));
  console.log(validatePassword(passwordValue));
  registerUser(usernameValue, passwordValue);
  e.preventDefault();
});
//SECTION-END: EVENT LISITONERS
