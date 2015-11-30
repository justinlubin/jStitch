<?php

// Returns a MySQL error code.

require_once("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connection = get_connection();
    check_connection($connection);

    $username = $connection->real_escape_string($_POST["username"]);
    $password = $connection->real_escape_string($_POST["password"]);

    log_in_user($connection, $username, $password);
    echo $connection->errno;

    $connection->close();
} else {
    goto_page("");
}
