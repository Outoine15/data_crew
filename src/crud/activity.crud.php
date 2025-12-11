<?php 
include_once "utility.crud.php";

function insert_activity($conn, $nom, $syllabus, $maxTeam, $coinsCost, $skillId, $periodName, $commentId, $teamId) {
    $sql = "INSERT INTO `Activity` (`nom`, `syllabus`, `maxTeam`, `coinsCost`, `skillId`, `periodName`, `commentId`, `teamId`) 
            VALUE ( '$nom', '$syllabus', $maxTeam, $coinsCost, $skillId, '$periodName', $commentId, $teamId )"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    if ($res) {
        $result = ["success" => true, "id" => mysqli_insert_id($conn)];
    } else {
        $result = ["error" => mysqli_error($conn)];
    }
    return $result; 
}


function select_activity($conn, $id) {
    $sql = "SELECT * FROM `Activity` WHERE id = $id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    $tab = rs_to_tab($res);
    return $tab[0];
}


function list_activity($conn) {
    $sql = "SELECT * FROM `Activity`"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 


    return rs_to_tab($res);
}



function update_activity($conn, $id, $nom, $syllabus, $maxTeam, $coinsCost, $skillId, $periodName, $commentId, $teamId) {
    $sql = "UPDATE `Activity` 
            SET `nom`='$nom', 
                `syllabus`='$syllabus', 
                `maxTeam`=$maxTeam, 
                `coinsCost`=$coinsCost, 
                `skillId`=$skillId, 
                `periodName`='$periodName', 
                `commentId`=$commentId, 
                `teamId`=$teamId  
            WHERE id = $id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    if ($res) {
        $result = ["success" => true];
    } else {
        $result = ["error" => mysqli_error($conn)];
    }
    return $result; 
}


function delete_activity($conn, $id) {
    $sql = "DELETE FROM `Activity` WHERE id = $id"; 
    
    global $debeug;
    if ($debeug) echo $sql . "<br>"; 
    
    $res = mysqli_query($conn, $sql); 
    if ($res) {
        $result = ["success" => true];
    } else {
        $result = ["error" => mysqli_error($conn)];
    }
    return $result;
}

?>