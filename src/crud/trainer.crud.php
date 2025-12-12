<?php 
include_once "utility.crud.php";

function select_trainer($conn, $id) {
    $sql = "SELECT * FROM `Trainer` WHERE id = $id"; 
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    $res = mysqli_query($conn, $sql);
    $tab = rs_to_tab($res);
    return $tab[0];
}

?>

