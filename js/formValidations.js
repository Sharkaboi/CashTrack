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
    } else {
        // Still have to check username and password after hashing in php from db and set cookie & session id.
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
    }  else if (password.length <8 ) {
        alert("Too short of a password!")
        return false;
    } else {
        // verify username unique-ness in php, insert if successful after hashing along with cookie & session id.
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