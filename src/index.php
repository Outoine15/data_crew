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

function format_learner($learner_id){
    global $conn;
    $res=select_learner($conn,$learner_id);

    if (!$res || !isset($res["id"])) {
        return json_encode(["error" => "Learner not found"]);
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

        array_push($skillArray,$curentSkill);
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

        array_push($markArray,$curentMark);
    }
    $return['marks']=$markArray;

    return $return;
}

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
    echo json_encode(format_learner($learner_id));
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
    echo json_encode(format_learner($id));
    exit;
});

get("/teams/:team_id", function($param){
    global $conn;
    $team_id=$param["team_id"];
    $teamData = select_team_by_id($conn,$team_id);

    $teams= [];
    $team = [
        "id" => $teamData["id"],
        "name" => $teamData["name"],
        "coin" => $teamData["coins"],
        "learners" => [],
        "sessions" => []
    ];

    $learners = select_learners_id_by_team_id($conn,$team_id);
    foreach ($learners as $key => $learner_id) {
        array_push($team['learners'],format_learner($learner_id));
    }

    $sessions = select_sessions_by_team_id($conn,$team_id);

    foreach ($sessions as $key => $sessionData) {
        $activityData=select_activity($conn,$sessionData["activityId"]);
        $current_activity = [
            "id" => $activityData["id"],
            "name" => $activityData["nom"],
            "syllabus" => $activityData["sylabus"],
            "coin" => $activityData["coinsCost"],
            "maxTeams" => $activityData["maxTeam"]
        ];
        $periodData = select_period_by_name($conn,$sessionData["periodName"]);
        $current_period = [
            "title" => $periodData["name"],
            "startDate" => $periodData["dateStart"],
            "endDate" => $periodData["dateEnd"],
            "color" => $periodData["color"]
        ];

        $current_session = [
            "id" => $sessionData["id"],
            "trainerId" => $sessionData["trainerId"],
            "activity" => $current_activity,
            "period" => $current_period
        ];
        array_push($team['sessions'],$current_session);

    }

    array_push($teams,$team);
    echo json_encode($teams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
});

get("/states",function(){
    global $conn;
   
    return list_states($conn);

});

header("HTTP/1.0 404");