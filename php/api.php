<?php

// Uses BCRYPT password hashing
function add_user($connection, $username, $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO tb_user
            (pk_username, hash)
            VALUES
            ('$username', '$hash')";
    return $connection->query($sql);
}

function log_in_user($connection, $username, $password) {
    $sql = "SELECT hash FROM tb_user
            WHERE BINARY pk_username = '$username'";
    if ($result = $connection->query($sql)) {
        $row = $result->fetch_assoc();
        $hash = $row["hash"];
        if (password_verify($password, $hash)) {
            $_SESSION["username"] = $username;
        } else {
            unset($_SESSION["username"]);
        }
    }
}

function is_logged_in() {
    return !empty($_SESSION["username"]);
}

function update_current_stitch_count($connection, $username, $deltaStitch) {
    $sql = "UPDATE tb_user
            SET current_stitch_count = current_stitch_count + $deltaStitch
            WHERE BINARY pk_username = '$username'";
    return $connection->query($sql);
}

function update_total_stitch_count($connection, $username, $deltaStitch) {
    $sql = "UPDATE tb_user
            SET total_stitch_count = total_stitch_count + $deltaStitch
            WHERE BINARY pk_username = '$username'";
    return $connection->query($sql);
}

function clear_current_stitch_count($connection, $username) {
    $sql = "UPDATE tb_user
            SET current_stitch_count = 0
            WHERE BINARY pk_username = '$username'";
    return $connection->query($sql);
}

// $page is an absolute path from the root.
function goto_page($page) {
    $host = $_SERVER["HTTP_HOST"];
    header("Location: http://$host/$page");
    die();
}
