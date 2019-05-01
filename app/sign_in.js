//Login to Account
$(document).ready(function () {

    //Grab user input from text fields
    $("#login-btn").on("click", function (event) {
        event.preventDefault();

        if ($("#emailLogin").val() != '') {
            var userEmail = $("#emailLogin").val().trim();
        }
        if ($("#passwordLogin").val() != '') {
            var userPassword = $("#passwordLogin").val().trim();
        }

        data_LogInUser(userEmail, userPassword);
        
        window.location.href = "http://userpage.com";

    })
})