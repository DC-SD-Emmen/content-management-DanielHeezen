$(document).ready(function () {


    $('#toggle').click(function (e) {
        $('#login-form, #signup-form, #signUp, #logIn').toggle();
        $('#errorMessage').toggle();
    });
});