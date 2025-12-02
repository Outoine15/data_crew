<?php 
include_once "utility.crud.php";

function select_team_by_id($conn, $id) {
    $sql = "SELECT * FROM `Team` WHERE id = $id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    
    return $tab[0];
}

function select_learners_by_team_id($conn,$teamId){
    $sql = "SELECT * FROM `Learner` WHERE teamId= $teamId";

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    $res = mysqli_query($conn,$sql);
    return rs_to_tab($res);
}

function select_sessions_by_team_id($conn,$teamId){
    $sql = "SELECT * FROM `TeamSession` WHERE teamId= $teamId";

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    $res = mysqli_query($conn,$sql);
    return rs_to_tab($res);
}
?>