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
function exportAsExcel() {
    window.location="http://localhost/php/routines/export_excel.php"   
}