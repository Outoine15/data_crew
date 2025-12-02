<?php 
include_once "utility.crud.php";

function list_states($conn) {
    $sql = "SELECT * FROM `State`"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 


    return rs_to_tab($res);
}

function select_states($conn,$id) {
    $sql = "SELECT * FROM `State` WHERE id=$id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 


    return rs_to_tab($res);
}

?>