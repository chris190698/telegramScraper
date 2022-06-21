<?php 
ob_start();
require __DIR__."/functions.php";
if (isset($_COOKIE['token']) && isset($_POST['chatID']) && isset($_POST['dataInizio']) && isset($_POST['dataFine']) && isset($_POST['wordSearch']) ) {

    $response = array();
    $token = $_COOKIE['token'];
    $chatID = isset($_POST['chatID']) ? $_POST['chatID'] : "";
    $dataInizio = $_POST['dataInizio'];
    $dataFine = $_POST['dataFine'];
    $wordSearch = $_POST['wordSearch'];
    
    $url = $baseUrl."api/users/".$token."/messages.search?data[q]=".urlencode($wordSearch)
            ."&data[min_date]=".strtotime($dataInizio)
            ."&data[max_date]=".strtotime($dataFine)
            ."&data[offset_id]=0&data[add_offset]=0&data[limit]=200&data[max_id]=0&data[min_id]=0&data[hash]=0"
            .($chatID !== "" ? "&data[peer]=".$chatID : "");
    $output = curl($url);
    if($output->success){

        $messages = $output->response->messages;
        foreach($messages as $message){

            $mex = new stdClass();
            $mex->text = $message->message;
            if($message->peer_id->user_id != null){

                $mex->id = $message->peer_id->user_id;
                $mex->name = getPeerInfo($message->peer_id->user_id)['name'];
                $mex->peerType = "peerUser";
                $mex->peerIdType = "user_id";

            }else if($message->peer_id->chat_id != null){

                $mex->id = $message->peer_id->chat_id;
                $mex->peerType = "peerChannel";
                $mex->peerIdType = "channel_id";
                foreach($output->response->chats as $chat){

                    if($chat->id == $message->peer_id->chat_id){

                        $mex->name = $chat->title;
                        
                    }

                }

            }else if($message->peer_id->channel_id != null){

                $mex->id = $message->peer_id->channel_id;
                $mex->peerType = "peerChannel";
                $mex->peerIdType = "channel_id";
                foreach($output->response->chats as $chat){

                    if($chat->id == $message->peer_id->channel_id){

                        $mex->name = $chat->title;
                        
                    }

                }

            }
            if($message->from_id->user_id != null){

                $mex->fromName = getPeerInfo($message->from_id->user_id)['name'];

            }
            $mex->date = date('d/m/Y H:i', $message->date);
            if(isset($_POST['peerType']) && isset($_POST['peerIdType'])){

                $mex->peerType = $_POST['peerType'];
                $mex->peerIdTye = $_POST['peerIdType'];
            
            }

            array_push($response, $mex);

        }

    }else {

        echo json_encode("{\"success\": false, \"error\": \"Error no chat matches\"}");

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

}else {

    http_response_code(500);
    die();

}