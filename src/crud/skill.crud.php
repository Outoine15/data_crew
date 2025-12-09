<?php 
include_once "utility.crud.php";

function select_skills_by_learner_id($conn,$learnerId) {
    $sql = "SELECT * FROM `Skill` WHERE learnerId = $learnerId"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    
    return $tab;

}
?>