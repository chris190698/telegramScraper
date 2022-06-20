<?php
    ob_start();
    require __DIR__ . '/functions.php';
    if (isset($_COOKIE['token']) && (isset($_POST['contacts']) || isset($_FILES['userCSV'])) && isset($_POST['sendMessage']) && isset($_POST['message']) ) {

        $token = $_COOKIE['token'];
        $contacts = isset($_POST['contacts']) ? json_decode($_POST['contacts']) : "";
        $userCSV = isset($_FILES['userCSV']) ? $_FILES['userCSV'] : "";
        $message = $_POST['message'];
        $contactList = array();
        $out = array();

        if($userCSV !== ""){

            $file = fopen($_FILES['userCSV']['tmp_name'], 'r');
            $userList = array();
            while(!feof($file)){
                
                $us= fgetcsv($file);
                $number = $us[0];
                $url = $baseUrl ."api/users/".$token."/contacts.resolvePhone?data[phone]=".$number;
                $response = curl($url);
                if($response->success){

                    $usTmp = new stdClass();
                    $usTmp->user_id = $response->response->peer->user_id;
                    if($contacts !== ""){

                        $count = 0;
                        foreach($contacts as $contact){
            
                            if($contact->user_id == $usTmp->user_id){

                                $count++;

                            }
            
                        }
                        if($count == 0){

                            $usTmp->name = $response->response->users[0]->first_name." ".$response->response->users[0]->last_name;
                            array_push($contactList, $usTmp);

                        }
            
                    }
                    

                }

            }

        }
        $contactList = array_merge($contactList, $contacts);
        foreach($contactList as $contact){

            $data = "data[peer]=".$contact->user_id;
            $data .= "&data[message]=".urlencode($message);
            $url = $baseUrl . "api/users/" . $token . "/messages.sendMessage?" .$data;
            $output = curl($url);
            if($output->success){
                
                $res = new stdClass();
                $res->message = $message;
                $res->contacts = $contact;
                array_push($out, $res);
            
            }else{

                echo json_encode("{\"success\": false, \"error\": \"Error sending message to users list\"}");

            }

        }
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
    } else {

        http_response_code(500);
        die();

    }