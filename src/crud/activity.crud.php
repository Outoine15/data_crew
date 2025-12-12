<?php 
include_once "utility.crud.php";

function insert_activity($conn, $nom, $syllabus, $maxTeam, $coinsCost, $skillId, $periodName, $commentId, $teamId) {
    $sql = "INSERT INTO `Activity` (`nom`, `sylabus`, `maxTeam`, `coinsCost`, `skillId`, `periodName`, `commentId`, `teamId`) 
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
                `sylabus`='$syllabus', 
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

function selectionner_toutes_activites_filtrees($connexion, $tri = null, $direction = 'ASC', $limite = null, $debut = 0) {
    $sql = "SELECT
                A.id, A.nom, A.sylabus, A.maxTeam, A.coinsCost, A.teamId,
                P.name as periodTitle, P.dateStart, P.dateEnd, P.color
            FROM `Activity` A
            LEFT JOIN `Period` P ON A.periodName = P.name";


    $criteresTriAutorises = [
        'date' => 'P.startDate',
        'cout' => 'A.coinsCost',
        'nom' => 'A.nom'
    ];

    if ($tri && array_key_exists($tri, $criteresTriAutorises)) {
        $colonne = $criteresTriAutorises[$tri];
        $ordre = (strtoupper($direction) === 'DESC') ? 'DESC' : 'ASC';
        $sql .= " ORDER BY $colonne $ordre";
    }

    if ($limite !== null) {
        $limite = (int)$limite;
        $debut = (int)$debut;
        $sql .= " LIMIT $limite OFFSET $debut";
    }

    global $debeug;
    if (isset($debeug) && $debeug) {
        echo $sql . "<br>";
    }


    $resultat = mysqli_query($connexion, $sql);

    
    return rs_to_tab($resultat);
}

?>