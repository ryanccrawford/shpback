//Create user account (Sign Up)
$(document).ready(function () {
    //Grab user input
    $("#new-user-btn").on("click", function (event) {
        event.preventDefault();
        //Check if email is valid email format, name isn't blank, and zip code is 5 digits
        if ($("#user-email-input").val() != '') {
            var userEmail = $("#user-email-input").val().trim();
        }
        if ($("#password-input").val() != '') {
            var userPassword = $("#password-input").val().trim();
        }
        if ($("#zipCode-input").val() != '') {
            var userZipCode = $("#zipCode-input").val().trim();
        }

        function validateEmail(userEmail) {
            var re = /\S+@\S+\.\S+/;
            return re.test(userEmail);
        }

        if (validateEmail(userEmail)) {

        data_AddUser(userEmail, userPassword, userZipCode);
        
        }
    })
})


