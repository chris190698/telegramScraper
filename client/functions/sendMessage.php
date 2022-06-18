<?php
    ob_start();
    require __DIR__ . '/functions.php';
    if (isset($_COOKIE['token']) && isset($_POST['contacts']) && isset($_POST['sendMessage']) && isset($_POST['message']) ) {

        $token = $_COOKIE['token'];
        $contacts = $_POST['contacts'];
        $message = $_POST['message'];
        $response = array();

        foreach ($contacts as $contact) {

            $data = "data[peer]=".$contact['user_id'];
            $data .= "&data[message]=".urlencode($message);
            $url = $baseUrl . "api/users/" . $token . "/messages.sendMessage?" .$data;
            $output = curl($url);
            if($output->success){
                
                $res = new stdClass();
                $res->message = $message;
                $res->contacts = $contact;
                array_push($response, $res);
            
            }else{

                echo json_encode("{\"success\": false, \"error\": \"Error sending message to users list\"}");

            }

        }
        echo json_encode($response);
        $size = ob_get_length();
        header('Content-Type: application/json');
        header("Content-Encoding: none");
        header("Content-Length: {$size}");
        header("Connection: close");
        ob_end_flush();
        ob_flush();
        flush();
        die();
    } else {
        http_response_code(500);
        die();
    }