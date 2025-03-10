$(document).ready(function () {


    $('#toggle').click(function (e) {
        $('#login-form, #signup-form, #signUp, #logIn, #errorMessage').toggle();
    });

    $('#go-back').click(function (e) {
        window.history.back();
    });
});