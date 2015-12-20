<?php

session_start();

define("SUCCESS", 0);

define("ERROR_UNKNOWN", -1);

define("ERROR_NOT_LOGGED_IN", 1);
define("ERROR_INVALID_CREDENTIALS", 2);
define("ERROR_USER_ALREADY_EXISTS", 3);
define("ERROR_INVALID_USERNAME", 4);
define("ERROR_INVALID_PASSWORD", 5);

function get_connection() {
    $production = getenv("JSTITCH_PRODUCTION");
    if ($production) {
        $server = $_SERVER["SERVER_ADDR"];
        $username = getenv("JSTITCH_USERNAME");
        $password = getenv("JSTITCH_PASSWORD");
        $db = getenv("JSTITCH_DB");
    } else {
        $server = "127.0.0.1";
        $username = "root";
        $password = "";
        $db = "jstitch";
    }
    return new mysqli($server, $username, $password, $db);
}

function check_connection($connection) {
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
}

// Uses BCRYPT password hashing
function register_user($connection, $username, $password) {
    if ($username == "") {
        return ERROR_INVALID_USERNAME;
    } else if ($password == "") {
        return ERROR_INVALID_PASSWORD;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO tb_user
            (pk_username, hash)
            VALUES
            ('$username', '$hash')";
    $connection->query($sql);
    if ($connection->errno == 0) {
        return SUCCESS;
    } else if ($connection->errno == 1062) {
        return ERROR_USER_ALREADY_EXISTS;
    } else {
        return ERROR_UNKNOWN;
    }
}

function log_in_user($connection, $username, $password) {
    $sql = "SELECT hash FROM tb_user
            WHERE BINARY pk_username = '$username'";
    if ($result = $connection->query($sql)) {
        $row = $result->fetch_assoc();
        $hash = $row["hash"];
        if (password_verify($password, $hash)) {
            $_SESSION["username"] = $username;
            return SUCCESS;
        } else {
            unset($_SESSION["username"]);
            return ERROR_INVALID_CREDENTIALS;
        }
    } else {
        return ERROR_UNKNOWN;
    }
}

function log_out_user() {
    unset($_SESSION["username"]);
}

function is_logged_in() {
    return !empty($_SESSION["username"]);
}

function update_current_stitch_count($connection, $username, $deltaStitch) {
    $sql = "UPDATE tb_user
            SET current_stitch_count =
                IF (current_stitch_count + $deltaStitch < 0,
                    0,
                    current_stitch_count + $deltaStitch
                )
            WHERE BINARY pk_username = '$username'";
    $connection->query($sql);
    if ($connection->errno == 0) {
        return SUCCESS;
    } else {
        return ERROR_UNKNOWN;
    }
}

function update_total_stitch_count($connection, $username, $deltaStitch) {
    $sql = "UPDATE tb_user
            SET total_stitch_count =
                IF (current_stitch_count + $deltaStitch < 0,
                    total_stitch_count,
                    total_stitch_count + $deltaStitch
                )
            WHERE BINARY pk_username = '$username'";
    $connection->query($sql);
    if ($connection->errno == 0) {
        return SUCCESS;
    } else {
        return ERROR_UNKNOWN;
    }
}

function clear_current_stitch_count($connection, $username) {
    $sql = "UPDATE tb_user
            SET current_stitch_count = 0
            WHERE BINARY pk_username = '$username'";
    $connection->query($sql);
    if ($connection->errno == 0) {
        return SUCCESS;
    } else {
        return ERROR_UNKNOWN;
    }
}

function get_stitch_counts($connection, $username) {
    $sql = "SELECT current_stitch_count, total_stitch_count FROM tb_user
            WHERE BINARY pk_username = '$username'";
    $results = $connection->query($sql);
    if ($results->num_rows > 0) {
        $row = $results->fetch_assoc();
        $currentStitchCount = $row["current_stitch_count"];
        $totalStitchCount = $row["total_stitch_count"];
        return [SUCCESS, $currentStitchCount, $totalStitchCount];
    } else {
        return [ERROR_UNKNOWN, -1, -1];
    }
}

// $page is an absolute path from the root.
function goto_page($page) {
    $host = $_SERVER["HTTP_HOST"];
    header("Location: http://$host/$page");
    die();
}
