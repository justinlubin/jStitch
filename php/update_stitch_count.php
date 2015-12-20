<?php

// Returns an error code.

require_once("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (is_logged_in()) {
        $connection = get_connection();
        check_connection($connection);

        $username = $_SESSION["username"];
        $delta_stitch = $_POST["delta_stitch"];

        $errorCode1 = update_current_stitch_count($connection, $username, $delta_stitch);
        if ($erroCode1 == SUCCESS) {
            $errorCode2 = update_total_stitch_count($connection,
                                                    $username,
                                                    $delta_stitch);
            echo $errorCode2;
        } else {
            echo $errorCode1;
        }

        $connection->close();
    } else {
        echo ERROR_NOT_LOGGED_IN;
    }
} else {
    goto_page("");
}
