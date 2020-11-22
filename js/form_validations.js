function validateLoginForm() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (isEmptyOrBlank(username)) {
        alert("Name must be filled out!");
        return false;
    } else if(isEmptyOrBlank(password)) {
        alert("Password can't be blank!");
        return false;
    } else if(password.length<8) {
        alert("Too short of a password!");
        return false;
    } else if(username.length>20){
        alert("Too long of a username!");
        return false;
    } else {
        return true;
    }
}

function validateSignUpForm() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var repassword = document.getElementById("repassword").value;
    if(password != repassword){
        alert("Both passwords should match!")
        return false;
    } else if (isEmptyOrBlank(username)) {
        alert("Name must be filled out!");
        return false;
    } else if(isEmptyOrBlank(password)) {
        alert("Password can't be blank!");
        return false;
    }  else if(isEmptyOrBlank(repassword)) {
        alert("Password can't be blank!");
        return false;
    }  else if (password.length<8) {
        alert("Too short of a password!");
        return false;
    } else if(username.length>20){
        alert("Too long of a username!");
        return false;
    } else {
        return true;
    }
}

function validateAddAmount() {
    var amount = document.getElementById("addAmount").value;
    if(isEmptyOrBlank(amount)) {
        alert("Amount must be filled out!");
        return false;
    } else if (amount <= 0) {
        alert("Invalid amount!\nUse Subtract for reducing.");
        return false;
    } else {
        return true;
    }
}

function validateSubAmount() {
    var amount = document.getElementById("subAmount").value;
    if(isEmptyOrBlank(amount)) {
        alert("Amount must be filled out!");
        return false;
    } else if (amount <= 0) {
        alert("Invalid amount!");
        return false;
    } else {
        return true;
    }
}

function isEmptyOrBlank(string) {
    if(string == "" || string === "" || !string || /^\s*$/.test(string)) {
        return true;
    } else{
        return false;
    }
}