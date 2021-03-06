<?php

// Returns an error code.

require_once("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (is_logged_in()) {
        $connection = get_connection();
        check_connection($connection);

        $username = $_SESSION["username"];

        echo clear_current_stitch_count($connection, $username);

        $connection->close();
    } else {
        echo ERROR_NOT_LOGGED_IN;
    }
} else {
    goto_page("");
}
