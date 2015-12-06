"use strict";
(function() {
    var fadeSpeed = 250;

    function showRegisterForm() {
        $("#login-form").fadeOut(fadeSpeed, function() {
            $("#register-form").fadeToggle(fadeSpeed);
        });
    }

    function showLoginForm() {
        $("#register-form").fadeOut(function() {
            $("#login-form").fadeToggle(fadeSpeed);
        });
    }

    function hideForms() {
        $(".popup-form").fadeOut(fadeSpeed);
    }

    function clearStitchCount() {
        $.post("php/clear_stitch_count.php", function(errorCode) {
            console.log(errorCode);
        });
    }

    function updateStitchCount(deltaStitch) {
        $.post("php/update_stitch_count.php", {
            "delta_stitch": deltaStitch
        }, function(errorCode) {
            console.log(errorCode);
        });
    }

    function register() {
        var username = $("#register-username").val();
        var password = $("#register-password").val();
        $.post("php/create_user.php", {
            "username": username,
            "password": password
        }, function(errorCode) {
            console.log(errorCode);
        });
    }

    function login() {
        var username = $("#login-username").val();
        var password = $("#login-password").val();
        $.post("php/log_in_user.php", {
            "username": username,
            "password": password
        }, function(errorCode) {
            console.log(errorCode);
        });
    }

    $(document).ready(function() {
        $(document).keydown(function(event) {
            if (event.which == 32) {
                event.preventDefault();
                updateStitchCount(1);
            } else if (event.which == 8) {
                event.preventDefault();
                updateStitchCount(-1);
            } else if (event.which == 27) {
                hideForms();
            }
        });

        $("#register-link").click(function() {
            event.stopPropagation();
            showRegisterForm();
        });
        $("#login-link").click(function() {
            event.stopPropagation();
            showLoginForm();
        });

        $("#register-submit").click(function() {
            register();
        });

        $("#login-submit").click(function() {
            login();
        });
    });
})();
