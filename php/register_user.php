<?php

// Returns an error code.

require_once("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connection = get_connection();
    check_connection($connection);

    $username = $connection->real_escape_string($_POST["username"]);
    $password = $connection->real_escape_string($_POST["password"]);

    echo register_user($connection, $username, $password);

    $connection->close();
} else {
    goto_page("");
}
