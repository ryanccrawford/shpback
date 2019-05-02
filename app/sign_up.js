//Create user account (Sign Up)
$(document).ready(function () {

    //Grab user input
    $("#signupBtn").on("click", function (event) {
        event.preventDefault();
        //Check if email is valid email format, name isn't blank, and zip code is 5 digits
        if ($("#email").val() != '') {
            var email = $("#email").val().trim();
        }
        if ($("#password").val() != '') {
            var password = $("#password").val().trim();
        }
        if ($("#zipCode").val() != '') {
            var zip = $("#zipCode").val().trim();
        }

        //Validate email
        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        if (validateEmail(email)) {

        //Push email to data
        data_AddUser(email, password, zip);
        
        }
    })
})


