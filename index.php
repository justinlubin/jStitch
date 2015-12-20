<?php

require_once("php/api.php");

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8" />
        <title>jStitch</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700,Inconsolata' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="js/main.js"></script>
    </head>

<?php

if (is_logged_in()) {
    require("includes/logged_in.php");
} else {
    require("includes/not_logged_in.php");
}

?>

</html>
