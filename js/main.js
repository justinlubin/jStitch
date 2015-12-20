"use strict";
(function() {
    var SPACE_BAR_KEY = 32;
    var UP_ARROW_KEY = 38;
    var DOWN_ARROW_KEY = 40;
    var ESCAPE_KEY = 27;

    var errors = {
        "0": "",
        "-1": "An unknown error occurred.",
        "1": "You are not logged in!",
        "2": "You have entered invalid credentials.",
        "3": "That username is already taken.",
        "4": "You must enter a username.",
        "5": "You must enter a password."
    }

    function showError(errorCode) {
        var message = "A truly unknown error occurred. (Error code: " + errorCode + ")";
        if (errorCode in errors) {
            message = errors[errorCode];
        }
        alert(message);
    }

    function clearStitchCount() {
        $.post("php/clear_stitch_count.php", function(errorCode) {
            errorCode = parseInt(errorCode);
            if (errorCode !== 0) {
                showError(errorCode);
            } else {
                updateLocalStitchCounts();
            }
        });
    }

    function updateStitchCount(deltaStitch) {
        $.post("php/update_stitch_count.php", {
            "delta_stitch": deltaStitch
        }, function(errorCode) {
            errorCode = parseInt(errorCode);
            if (errorCode !== 0) {
                showError(errorCode);
            } else {
                updateLocalStitchCounts();
            }
        });
    }

    function updateLocalStitchCounts() {
        $.post("php/get_stitch_counts.php", {
            "dummyVariable": 0
        }, function(response) {
            var responseArray = JSON.parse(response);
            console.log(responseArray);
            var errorCode = responseArray[0];
            if (errorCode !== 0) {
                alert("imma here with EC:" + errorCode);
                showError(errorCode);
            } else {
                var currentStitchCount = responseArray[1];
                var totalStitchCount = responseArray[2];
                $("#current-stitch-count").html(currentStitchCount);
                $("#total-stitch-count").html(totalStitchCount);
            }
        });
    }

    function register(username, password) {
        $.post("php/register_user.php", {
            "username": username,
            "password": password
        }, function(errorCode) {
            errorCode = parseInt(errorCode);
            if (errorCode !== 0) {
                showError(errorCode);
            } else {
                logIn(username, password);
            }
        });
    }

    function logIn(username, password) {
        $.post("php/log_in_user.php", {
            "username": username,
            "password": password
        }, function(errorCode) {
            errorCode = parseInt(errorCode);
            if (errorCode !== 0) {
                showError(errorCode);
            } else {
                location.reload();
            }
        });
    }

    function logOut() {
        $.post("php/log_out_user.php");
        location.reload();
    }

    $(document).ready(function() {
        var loggedIn = $(document.body).data("logged-in");
        if (loggedIn === 1) {
            updateLocalStitchCounts();
            $(document).keydown(function(event) {
                if (event.which === UP_ARROW_KEY || event.which === SPACE_BAR_KEY) {
                    updateStitchCount(1);
                } else if (event.which === DOWN_ARROW_KEY) {
                    updateStitchCount(-1);
                }
            });
            $("#log-out").click(function() {
                logOut();
            })
        } else {
            $("#register-submit").click(function() {
                var username = $("#register-username").val();
                var password = $("#register-password").val();
                register(username, password);
            });

            $("#login-submit").click(function() {
                var username = $("#login-username").val();
                var password = $("#login-password").val();
                logIn(username, password);
            });
        }
    });
})();
