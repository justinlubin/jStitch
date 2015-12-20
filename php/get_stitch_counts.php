<?php

// Returns [errorCode, currentStitchCount, totalStitchCount]. If errorCode is
// not 0, then the values of currentStitchCount and totalStitchCount are
// undefined behavior.

require_once("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (is_logged_in()) {
        $connection = get_connection();
        check_connection($connection);

        $username = $_SESSION["username"];

        echo json_encode(get_stitch_counts($connection, $username));

        $connection->close();
    } else {
        echo json_encode([ERROR_NOT_LOGGED_IN, -1, -1]);
    }
} else {
    goto_page("");
}
