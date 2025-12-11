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

function select_skill($conn, $id) {
    $sql = "SELECT * FROM `Skill` WHERE id = $id";

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    $res = mysqli_query($conn, $sql);
    $tab = rs_to_tab($res);
    
    return isset($tab[0]) ? $tab[0] : null;
}

?>