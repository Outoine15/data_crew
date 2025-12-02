<?php 
include_once "utility.crud.php";

// insert learner not needed

function select_learner($conn, $id) {
    $sql = "SELECT * FROM `Learner` WHERE id = $id";
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    return $tab[0];
}

function update_learner_password($conn, $id, $newPassword) {
    $sql = "UPDATE `Activity` SET `password`='$newPassword' WHERE id = $id";

    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    return $res; 
}

function update_learner_status($conn, $id, $newStatusId) {
    $sql = "UPDATE `Activity` SET `stateId`='$newStatusId' WHERE id = $id";

    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    return $res; 
}

?>