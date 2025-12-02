<?php 
include_once "utility.crud.php";

function insert_trainer($conn, $nom, $prenom, $email) {
    $sql = "INSERT INTO `Trainer` (`nom`, `prenom`, `email`) 
            VALUE ( '$nom', '$prenom', '$email' )"; 
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    return mysqli_query($conn, $sql); 
}

function select_trainer($conn, $id) {
    $sql = "SELECT * FROM `Trainer` WHERE id = $id"; 
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    return $tab[0];
}

function list_trainer($conn) {
    $sql = "SELECT * FROM `Trainer`"; 
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    return rs_to_tab(mysqli_query($conn, $sql));
}

function update_trainer($conn, $id, $nom, $prenom, $email) {
    $sql = "UPDATE `Trainer` 
            SET `nom`='$nom', `prenom`='$prenom', `email`='$email'
            WHERE id = $id"; 
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    return mysqli_query($conn, $sql); 
}

function delete_trainer($conn, $id) {
    $sql = "DELETE FROM `Trainer` WHERE id = $id"; 
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    return mysqli_query($conn, $sql);
}
?>
