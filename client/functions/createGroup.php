<?php
ob_start();
require __DIR__ . '/functions.php';
if (isset($_COOKIE['token']) && isset($_FILES['groupFile']) && isset($_POST['titleGroup']) && isset($_POST['createGroup']) ) {

    $token = $_COOKIE['token'];
    $groupTitle = $_POST['titleGroup'];
    $file = fopen($_FILES['groupFile']['tmp_name'], 'r');
    $userList = array();
    while(!feof($file)){

        $list = fgetcsv($file);
        $number = $list[0];
        $url = $baseUrl ."api/users/".$token."/contacts.resolvePhone?data[phone]=".$number;
        $response = curl($url);
        if($response->success){
            
            $id = $response->response->peer->user_id;
            $user .= $id.",";
            array_push($userList, $response->response->users[0]->first_name." ".$response->response->users[0]->last_name);

        }else {

                echo json_encode("{\"success\": false, \"error\": \"Error sending message to users list\"}");

        }

    }
    $user = rtrim($user, ",");
    $chatUrl = $baseUrl."api/users/".$token."/createChat?data[ids]=".$user."&data[title]=".$groupTitle;
    $res = curl($chatUrl);
    if($res->success){

        $out = new stdClass();
        $out->users = $userList;
        $out->title = $groupTitle;
        echo json_encode($out);
        $size = ob_get_length();
        header('Content-Type: application/json');
        header("Content-Encoding: none");
        header("Content-Length: {$size}");
        header("Connection: close");
        ob_end_flush();
        ob_flush();
        flush();
        die();

    }else {

        echo json_encode("{\"success\": false, \"error\": \"Error sending message to users list\"}");

    }
    

}else{
    http_response_code(500);
    die();
}