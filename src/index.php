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

    echo json_encode($team, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
});

get("/activities", function() {
    global $conn;
    $date = "";
    $coin = "";
    $name = "";
    if(isset($_GET["date"])){
        $date = $_GET['date'];
    }
    if(isset($_GET["coin"])){
        $coin = $_GET['coin'];
    }
    if(isset($_GET["name"])){
        $name = $_GET['name'];
    }

    $limit = 100;
    $offset = 0;
    $orderBy = 'id'; 
    $orderDir = 'ASC';   

    if ($date != "") {
        $orderBy = 'dateStart'; 
        $orderDir = $date;}
    else {
            
        if ($coin != "") {
            $orderBy = 'coin';
            $orderDir = $coin;
        } 
        else {
        
            if ($name != "") {
                $orderBy = 'name';
                $orderDir = $name;
            }
        }
    }

    $rawActivities = selectionner_toutes_activites_filtrees($conn, $orderBy, $orderDir, $limit, $offset);

    $formattedActivities = [];
    foreach ($rawActivities as $act) {
        $formattedActivities[] = [
            "id" => (int)$act['id'],
            "name" => $act['nom'],
            "syllabus" => $act["sylabus"],
            "coin" => (int)$act['coinsCost'],
            "maxTeams" => (int)$act['maxTeam'],
            "period" => [
                "title" => $act['periodTitle'],
                "startDate" => $act['dateStart'],
                "endDate" => $act['dateEnd'],
                "color" => $act['color']
            ]
        ];
    }
    return $formattedActivities;
});



get("/activities/:activityId", function($param) {
    global $conn;
    $id = $param['activityId'];

    $activity = select_activity($conn, $id);
    if (!$activity) return ["error" => "Activity not found"];

    $period = select_period_by_name($conn, $activity['periodName']);
    $skill  = select_skill($conn, $activity['skillId']);
    
    // Utilise la fonction ajoutée à l'étape 1 ci-dessus
    $rawSessions = select_sessions_by_activity_id($conn, $id);
    $formattedSessions = [];

    foreach ($rawSessions as $sess) {
        $trainer = select_trainer($conn, $sess['trainerId']);
        // Utilise ta fonction room (mock)
        $room = select_room($conn, isset($sess['roomId']) ? $sess['roomId'] : 0);

        $formattedSessions[] = [
            "id" => (int)$sess['id'],
            "date" => $sess['date'],
            "trainer" => [
                "id" => (int)$trainer['id'],
                "firstName" => $trainer['firstName'],
                "lastName" => $trainer['lastName']
            ],
            "room" => [
                "building" => $room['building'],
                "number" => (int)$room['number']
            ]
        ];
    }

    $rawComments = select_comments_by_activity_id($conn, $id);
    $formattedComments = [];
    foreach ($rawComments as $com) {
        $formattedComments[] = [
            "id" => (int)$com['id'],
            "learner" => [
                "id" => (int)$com['learnerId'],
                "firstName" => $com['firstName'],
                "lastName" => $com['lastName']
            ],
            "date" => $com['date'],
            "message" => $com['message']
        ];
    }

    // moyenne notes activity:
    $markData = select_marks_by_activity_id($conn,$id);
    $moyenneDiv = count($markData);
    $moyenne = 0;
    if ($moyenneDiv>0) {
        foreach ($markData as $key => $mark) {
            $moyenne+=$mark["mark"];
        }
        $moyenne=$moyenne/$moyenneDiv;
    } else {
        $moyenne=0;
    }

    return [
        "id" => (int)$activity['id'],
        "name" => $activity['nom'],
        "syllabus" => isset($activity['syllabus']) ? $activity['syllabus'] : $activity['sylabus'],
        "maxTeams" => (int)$activity['maxTeam'],
        "coin" => (int)$activity['coinsCost'],
        "period" => [
            "title" => $period['name'],
            "startDate" => $period['dateStart'],
            "endDate" => $period['dateEnd'],
            "color" => $period['color']
        ],
        "mark" => $moyenne,
        "sessions" => $formattedSessions,
        "comments" => $formattedComments,
        "skills" => $skill ? [$skill['name']] : []
    ];
});

delete("/activities/:activityId/marks", function($param) {
    global $conn;
    $activityId = $param["activityId"];
    $_DELETE = read_put(); 
    $learnerId = $_DELETE["learner_id"];


    $result = delete_mark($conn, $learnerId, $activityId);

    if ($result) {
        return [
            "success" => true,
            "message" => "Marka ete suppr"
        ];
    } else {
        return [
            "success" => false,
            "message" => "Erreuuuuur suppr mark"
        ];
    }
});

post("/activities/:activityId/marks", function($param) {
    global $conn;
    $activityId = $param['activityId'];

    if (!isset($_POST['learner_id']) || !isset($_POST['mark'])) {
        return ["error" => "Missing learner_id or mark"];
    }

    $learnerId = $_POST['learner_id'];
    $mark = $_POST['mark'];

    $result = set_activity_mark($conn, $activityId, $learnerId, $mark);

    if ($result) {
        return ["success" => true, "message" => "Mark set successfully"];
    } else {
        return ["success" => false, "message" => "Error saving mark"];
    }
});


delete("/activities/:activityId/marks", function($param) {
    global $conn;
    $activityId = $param["activityId"];
    $_DELETE = read_put(); 
    $learnerId = $_DELETE["learner_id"];


    $result = delete_mark($conn, $learnerId, $activityId);

    if ($result) {
        return [
            "success" => true,
            "message" => "Marka ete suppr"
        ];
    } else {
        return [
            "success" => false,
            "message" => "Erreuuuuur suppr mark"
        ];
    }
});

post("/activities/:activityId/comments", function($param) {
    global $conn;
    $activityId = $param["activityId"];
    $learnerId = $_POST["learner_id"];
    $message   = $_POST["message"];
    $result = add_comment($conn, $learnerId, $activityId, $message);

    if ($result) {
        return [
            "success" => true,
            "message" => "Commentaire a ezte ajoute "
        ];
    } else {
        return [
            "success" => false,
            "message" => "Erreuur"
        ];
    }
});

delete("/activities/:activityId/comments/:commentId", function($param) {
    global $conn;

    //$activityId = $param["activityId"];
    $commentId  = $param["commentId"];
    $_DELETE = read_put();
    $result = delete_comment($conn, $commentId);

    if ($result) {
        return [
            "success" => true,
            "message" => "Comment suppr "
        ];
    } else {
        return [
            "success" => false,
            "message" => "Erreur suppr comment"
        ];
    }
});

get("/activities/:activityId/sessions/:sessionId", function($param) {
    global $conn;
    $activityId = $param['activityId'];
    $sessionId = $param['sessionId'];
    $res = select_session_by_activity_id_and_id($conn, $sessionId, $activityId); 
    $tab = rs_to_tab($res);
    $session = $tab[0];
    $trainer = select_trainer($conn, $session['trainerId']); 
    $room = select_room($conn, isset($session['roomId']) ? $session['roomId'] : 0); //

    return [
        "id"=> $session['id'],
        "date"=> $session['date'],
        "trainer"=> [
            "id" => (int)$trainer['id'],
            "firstName" => $trainer['firstName'],
            "lastName" => $trainer['lastName']
        ],
        "room" => [
            "building" => $room['building'],
            "number" => (int)$room['number']
        ]
    ];
});

post("/activities/:activityId/sessions/:sessionId", function($param) {
    global $conn;
    $sessionId = $param['sessionId'];
    $teamId = $_POST['team_id'];

    $result = abo_session($conn, $sessionId, $teamId);

    if ($result) {
        return ["success" => true, "message" => "Team abo a la session"];
    } else {
        return ["success" => false, "message" => "Erreur "];
    }
});

delete("/activities/:activityId/sessions/:sessionId", function($param) {
    global $conn;
    $sessionId = $param['sessionId'];
    $teamId = $param['team_id'];

    $result = desabo_session($conn, $sessionId, $teamId);

    if ($result) {
        return ["success" => true, "message" => "Team désabo de la session"];
    } else {
        return ["success" => false, "message" => "Erreur"];
    }
});


get("/states",function(){
    global $conn;
   
    $result = [];
    $states = list_states($conn);
    foreach ($states as $key => $state) {
        array_push($result,[
            "id" => $state["id"],
            "title" => $state["title"],
            "color" => $state["color"],
            "icon" => $state["icon"]
        ]);
    }
    echo json_encode($result);
    exit;
});


get("/trainers/:trainerId",function($param){
    global $conn;
    $trainerId = $param["trainerId"];
    $trainerData = select_trainer($conn,$trainerId);

    $return = [
        "id" => $trainerData["id"],
        "firstName" => $trainerData["firstName"],
        "lastName" => $trainerData["lastName"],
        "mail" => $trainerData["email"],
        "roles" => [],
        "sessions" => []
    ];

    $roleData = select_role_by_trainer_id($conn,$trainerId);
    foreach ($roleData as $key => $role) {
        array_push($return["roles"],$role["name"]);
    }

    $sessions = select_sessions_by_trainer_id($conn,$trainerId);
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
            "id" => (int)$sessionData["id"],
            "date" => $sessionData["date"],
            "room" => [
                "building" => $sessionData["building"],
                "number" => (int)$sessionData["roomNumber"]
            ],
            "activity" => [
                "id" => (int)$activityData["id"],
                "name" => $activityData["nom"]
            ]
        ];
        array_push($return['sessions'],$current_session);

    }
    echo json_encode($return);
    exit;
});


header("HTTP/1.0 404");