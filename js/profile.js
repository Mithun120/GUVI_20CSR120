const Storage = {};
//SECTION-START: QUERRY SELECTORS

const DisplayUsername = document.getElementById("username");

const currentUserSelector = document.getElementById("current-user-name");
const UserGivenName = document.getElementById("user-given-name");
const UserPhoneNumber = document.getElementById("user-phone-number");
const UserAge = document.getElementById("user-age");
const UserEmail = document.getElementById("user-email");
const updateButton = document.querySelector(".update-button");
//SECTION-END: QUERRY SELECTORS

//SECTION-START: AJAX CALL

const UpdateUserDetails = (data) => {
  $.ajax({
    url: "/php/update.php",
    method: "POST",
    data: data,
    success: (response) => {
      console.log("Response:", response);
      Storage.newUser = false;
    },
    error: (jqXHR, textStatus, errorThrown) => {
      console.error("Error:", textStatus, errorThrown);
    },
  });
};
const GetUserDetails = (data) => {
  $.ajax({
    url: "/php/profile.php",
    method: "GET",
    data: data,
    success: (response) => {
      console.log("here");
      console.log(response);

      const { status } = response;
      if (!status) {
        setterValue(null, null, null, null);
        Storage.newUser = true;
        Storage.username = localStorage.getItem("username");
        console.log("storage");
        console.log(Storage);
      } else {
        const { username, UserGivenName, UserPhoneNumber, UserAge, UserEmail } =
          response.userDetails;
        console.log(response.userDetails);
        console.log({
          username,
          UserGivenName,
          UserPhoneNumber,
          UserAge,
          UserEmail,
        });
        setterValue(UserGivenName, UserPhoneNumber, UserAge, UserEmail);
        Storage.newUser = false;
        Storage.username = localStorage.getItem("username");
      }
      updateUI();
      return;
      Storage.newUser = newUser;
      Storage.username = username;
      if (
        newUser == undefined &&
        username == undefined &&
        userDetails == undefined
      ) {
        Storage.newUser = true;
        Storage.username = localStorage.getItem("username");
      }
      if (!newUser) {
        Storage.userDetails = response.userDetails;
      }
      console.log(Storage);
      updateUI();
    },
    error: (jqXHR, textStatus, errorThrown) => {
      console.error("Error:", textStatus, errorThrown);
    },
  });
};

//SECTION-START: AJAX CALL

//SECTION-START: FUNCTION
const setterValue = (name, number, age, email) => {
  // const data = .value;
  console.log("setter");
  console.log(name);
  console.log(number);
  console.log(age);
  console.log(email);

  UserGivenName.value = name ? name : "";
  UserPhoneNumber.value = number ? parseInt(number) : 0;
  UserAge.value = age ? parseInt(age) : 0;
  UserEmail.value = email ? email : "";
};
const getterValue = () => {
  // const data = .value;
  console.log("getter");
  const user = {};
  user.username = Storage.username;
  user.UserGivenName = UserGivenName.value;
  user.UserPhoneNumber = UserPhoneNumber.value;
  user.UserAge = UserAge.value;
  user.UserEmail = UserEmail.value;
  return user;
};

const updateUI = () => {
  console.log(currentUserSelector);
  currentUserSelector.innerHTML = Storage.username;
};
//SECTION-START: FUNCTION
//SECTION-START: EVENT LISITONERS
window.addEventListener("load", () => {
  const isLogin = Boolean(localStorage.getItem("isLogin"));
  console.log(isLogin);
  if (isLogin) {
    const data = localStorage.getItem("session_id");
    console.log(data);
    GetUserDetails({ redisID: data });
  } else {
    // alert("login to continue");
    // window.location.href = "/login.html";
  }
});
updateButton.addEventListener("click", (e) => {
  e.preventDefault();
  console.log(Storage);

  console.log("update");
  console.log({
    details: getterValue(),
    isSaved: Storage.newUser,
    username: Storage.username,
  });
  UpdateUserDetails({
    details: getterValue(),
    isSaved: Storage.newUser,
    username: Storage.username,
  });
});
updateUI();
//SECTION-END: EVENT LISITONERS
