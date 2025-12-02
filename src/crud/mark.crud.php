<?php 
include_once "utility.crud.php";

function select_marks_by_learner_id($conn,$learnerId) {
    $sql = "SELECT * FROM `Mark` WHERE learnerId = $learnerId"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    
    return $tab[0];

}
?>