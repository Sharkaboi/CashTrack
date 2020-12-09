function confirmDeleteAccount() {
    var choice = confirm("Do you want to confirm?")
    if(choice) {
        window.location="http://localhost/php/routines/delete_account.php"   
    }
}
function confirmResetData() {
    var choice = confirm("Do you want to confirm?")
    if(choice) {
        window.location="http://localhost/php/routines/delete_data.php"   
    }
}