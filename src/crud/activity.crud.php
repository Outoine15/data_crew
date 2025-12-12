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

function select_all_activities_filtered($conn, $orderBy = null, $orderDir = 'ASC', $limit = null, $offset = 0) {
    // 1. On prépare la requête de base avec une JOINTURE pour avoir les infos de la Période
    // On suppose que la table s'appelle 'Period' et que la clé de jointure est 'name'
    $sql = "SELECT
                A.id, A.nom, A.sylabus, A.maxTeam, A.coinsCost, A.teamId,
                P.name as periodTitle, P.dateStart, P.dateEnd, P.color
            FROM `Activity` A
            LEFT JOIN `Period` P ON A.periodName = P.name";

    // 2. Gestion du Tri (ORDER BY)
    // On sécurise pour éviter les injections SQL (whitelist)
    $allowedSorts = ['date' => 'P.dateStart', 'coin' => 'A.coinsCost', 'name' => 'A.nom'];

    if ($orderBy && array_key_exists($orderBy, $allowedSorts)) {
        // Si l'utilisateur a demandé un tri valide
        $column = $allowedSorts[$orderBy];
        // On force la direction à ASC ou DESC
        $direction = ($orderDir === 'DESC') ? 'DESC' : 'ASC';
        $sql .= " ORDER BY $column $direction";
    }

    // 3. Gestion de la Pagination (LIMIT / OFFSET)
    if ($limit !== null) {
        $limit = (int)$limit; // Sécurité : on force en entier
        $offset = (int)$offset;
        $sql .= " LIMIT $limit OFFSET $offset";
    }

    global $debeug;
    if ($debeug) echo $sql . "<br>";

    $res = mysqli_query($conn, $sql);
    return rs_to_tab($res);
}

?>