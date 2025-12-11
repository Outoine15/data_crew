<?php
include_once "bdd.php";
include_once "utility.php";
foreach (glob("crud/*.crud.php") as $filename) {
    require_once $filename;
}
foreach (glob("REST/*.rest.php") as $filename) {
    require_once $filename;
}

$conn = db();


put("/learners/:learnerId/state", function ($param) {
// return a 405 not allowed
    global $conn;   
    $id = $param['learnerId'];
    $_PUT = read_put();
        
    if (!isset($_PUT['state_id'])) {
        return ["error" => "state_id parameter is missing"];
    }

    $newStateId = $_PUT['state_id'];
    $result = update_learner_status($conn, $id, $newStateId);

    if ($result) {
        return [
            "success" => true,
            "message" => "Learner status updated to ".$newStateId];
    } else {
        return [
            "success" => false,
            "message" => "Error updating status"];
    }
});




get("/learners/:learnerId", function ($param) {
    // TODO: not changed to check if it works like this
    $learner_id = $param['learnerId'];
    global $conn;
    $res=select_learner($conn,$learner_id);

    if (!$res || !isset($res["id"])) {
        echo json_encode(["error" => "Learner not found"]);
        exit;
    }

    $id=$res["id"];
    $firstName=$res["firstName"];
    $lastName=$res["lastName"];
    $email=$res["email"];
    $pwd=$res["password"];
    $stateId=$res["stateId"];
    $teamId=$res["teamId"];

    $resState=select_states($conn,$stateId);
    $resSkill=select_skills_by_learner_id($conn,$id);
    $resMark = select_marks_by_learner_id($conn,$id);

    $stateId=$resState["id"];
    $stateTitle=$resState["title"];
    $stateColor=$resState["color"];
    $stateIcon=$resState["icon"];
    
    $return = [
        "id" => $id,
        "firstName"=>$firstName,
        "lastName"=>$lastName,
        "email"=>$email,
        "team"=>$teamId,
        "state"=>[
            "id"=>$stateId,
            "title"=>$stateTitle,
            "color"=>$stateColor,
            "icon"=>$stateIcon,
        ],
        "skills"=>[],
        "marks"=>[]
    ];

    $skillArray=[];
    for ($i=0; $i < count($resSkill); $i++) { 

        $name=$resSkill[$i]["name"];
        $level=$resSkill[$i]["level"];
        $color=$resSkill[$i]["color"];
        $icon=$resSkill[$i]["icon"];

        $curentSkill=[
            "name"=>$name,
            "level"=>$level,
            "color"=>$color,
            "icon"=>$icon
        ];

        array_push($skillArray,json_encode($curentSkill));
    }
    $return['skills'] = $skillArray;

    $markArray=[];
    for ($i=0; $i < count($resMark); $i++) {
        $activityId= $resMark[$i]["activityId"];
        $mark= $resMark[$i]["mark"];

        $curentMark=[
            "activityId"=>$activityId,
            "mark"=>$mark
        ];

        array_push($markArray,json_encode($curentMark));
    }
    $return['marks']=$markArray;

    echo json_encode($return);
    exit;

});


put("/learners/:learnerId", function ($param) {
    global $conn;
    $id = $param['learnerId'];

    $_PUT = read_put();
    
    if (!isset($_PUT['password'])) {
        return ["error" => "Password is required"];
    }

    $newPassword = $_PUT['password'];
    $result = update_learner_password($conn, $id, $newPassword);

    if ($result) {
        return [
            "success" => true,
            "message" => "Password updated successfully for learner " . $id
        ];
    } else {
        return [
            "success" => false,
            "message" => "Error updating password"
        ];
    }
});

post("/learners",function(){
    global $conn;
    $user_pwd = $_POST['password'];
    $user_mail= $_POST['mail'];
    $res=connect_learner($conn,$user_mail,$user_pwd);
    $id=$res["id"];
    $firstName=$res["firstName"];
    $lastName=$res["lastName"];
    $email=$res["email"];
    $pwd=$res["password"];
    $stateId=$res["stateId"];
    $teamId=$res["teamId"];

    $resState=select_states($conn,$stateId);
    $resSkill=select_skills_by_learner_id($conn,$id);
    $resMark = select_marks_by_learner_id($conn,$id);

    $stateId=$resState["id"];
    $stateTitle=$resState["title"];
    $stateColor=$resState["color"];
    $stateIcon=$resState["icon"];
    
    $return = [
        "id" => $id,
        "firstName"=>$firstName,
        "lastName"=>$lastName,
        "email"=>$email,
        "team"=>$teamId,
        "state"=>[
            "id"=>$stateId,
            "title"=>$stateTitle,
            "color"=>$stateColor,
            "icon"=>$stateIcon,
        ],
        "skills"=>[],
        "marks"=>[]
    ];

    $skillArray=[];
    for ($i=0; $i < count($resSkill); $i++) { 

        $name=$resSkill[$i]["name"];
        $level=$resSkill[$i]["level"];
        $color=$resSkill[$i]["color"];
        $icon=$resSkill[$i]["icon"];

        $curentSkill=[
            "name"=>$name,
            "level"=>$level,
            "color"=>$color,
            "icon"=>$icon
        ];

        array_push($skillArray,json_encode($curentSkill));
    }
    $return['skills'] = $skillArray;

    $markArray=[];
    for ($i=0; $i < count($resMark); $i++) {
        $activityId= $resMark[$i]["activityId"];
        $mark= $resMark[$i]["mark"];

        $curentMark=[
            "activityId"=>$activityId,
            "mark"=>$mark
        ];

        array_push($markArray,json_encode($curentMark));
    }
    $return['marks']=$markArray;

    echo json_encode($return);
    exit;
});

post("/learners",function(){
    global $conn;
    $user_pwd = $_POST['password'];
    $user_mail= $_POST['mail'];
    $res = connect_learner($conn,$user_mail,$user_pwd);

    if (!$res || !isset($res["id"])) {
        echo json_encode(["error" => "Learner not found"]);
        exit;
    }

    $id = $res["id"];
    $firstName = $res["firstName"];
    $lastName = $res["lastName"];
    $email = $res["email"];
    $pwd = $res["password"];
    $stateId = $res["stateId"];
    $teamId = $res["teamId"];

    $resState = select_states($conn,$stateId);
    if (!$resState) $resState = [];

    $stateId = $resState["id"];
    $stateTitle = $resState["title"];
    $stateColor = $resState["color"];
    $stateIcon = $resState["icon"];

    $resSkill = select_skills_by_learner_id($conn,$id);
    if (!is_array($resSkill)) $resSkill = [];

    $resMark = select_marks_by_learner_id($conn,$id);
    if (!is_array($resMark)) $resMark = [];

    $return = [
        "id" => $id,
        "firstName"=>$firstName,
        "lastName"=>$lastName,
        "email"=>$email,
        "team"=>$teamId,
        "state"=>[
            "id"=>$stateId,
            "title"=>$stateTitle,
            "color"=>$stateColor,
            "icon"=>$stateIcon,
        ],
        "skills"=>[],
        "marks"=>[]
    ];

    $skillArray = [];
    for ($i=0; $i < count($resSkill); $i++) {
        $name = isset($resSkill[$i]["name"]) ? $resSkill[$i]["name"] : "";
        $level = isset($resSkill[$i]["level"]) ? $resSkill[$i]["level"] : "";
        $color = isset($resSkill[$i]["color"]) ? $resSkill[$i]["color"] : "";
        $icon = isset($resSkill[$i]["icon"]) ? $resSkill[$i]["icon"] : "";

        $curentSkill = [
            "name"=>$name,
            "level"=>$level,
            "color"=>$color,
            "icon"=>$icon
        ];

        array_push($skillArray,json_encode($curentSkill));
    }
    $return['skills'] = $skillArray;

    $markArray = [];
    for ($i=0; $i < count($resMark); $i++) {
        $activityId = isset($resMark[$i]["activityId"]) ? $resMark[$i]["activityId"] : null;
        $mark = isset($resMark[$i]["mark"]) ? $resMark[$i]["mark"] : null;

        $curentMark = [
            "activityId"=>$activityId,
            "mark"=>$mark
        ];

        array_push($markArray,json_encode($curentMark));
    }
    $return['marks']=$markArray;

    echo json_encode($return);
    exit;
});


get("/states",function(){
    global $conn;
   
    return list_states($conn);

});

header("HTTP/1.0 404");


