<?php

if (isset($_COOKIE['token'])) {

    $token = $_COOKIE['token'];
    $contactList = array();
    $url = $baseUrl . "api/users/" . $token . "/getContacts";
    $output = curl($url);
    
    if($output->success){

        foreach($output->response->contacts as $contact){
            
                    
            if($contact->mutual){

                $contactInfo = new stdClass();
                $contactInfo->user_id = $contact->user_id;
                $contactInfo->name = getPeerInfo($contact->user_id)['name'];
                array_push($contactList, $contactInfo);

            }

        }

    }       

}
    

