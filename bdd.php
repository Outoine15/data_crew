<?php

#To fill
$username = "info_gr9";
$password = "ON9";

function db()
{
    global $username, $password;
    $bdd = mysqli_connect("localhost", $username, $password, $username) or die("Connection failed: " . mysqli_connect_error());
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    return $bdd;
}
