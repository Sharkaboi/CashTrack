function validateLoginForm() {
    var username = document.forms["loginForm"]["username"].value;
    var password = document.forms["loginForm"]["password"].value;
    if (username = "") {
        alert("Name must be filled out");
        return false;
    }
}

function validateSignUpForm() {
    var username = document.forms["signupForm"]["username"].value;
    var password = document.forms["signupForm"]["password"].value;
    var repassword = document.forms["signupForm"]["repassword"].value;
    if(password != repassword){
        alert("Both passwords should match!")
        return false;
    } else if ( password.length <8 ) {
        alert("Too short of a password!")
        return false;
    } else {
        
    }
    alert("error");
    return true;
}