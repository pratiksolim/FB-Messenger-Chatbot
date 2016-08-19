<?php

  $accessToken = "EAAJTC6sof2wBACGf5fYprTX2fqKz5Ckr80OuHtaXOJeBZAfqHZCySZAreC1MsDMjnQKucHLV1MMKVQiRxeIgyVjvV6JGCLa6nXzM7vO8WqZC2dCKN1VzESxAi1l7ULUEFO9W3Wow1v5Ayf2qvMbEZBr5zmF1hAtRDhoAFxmStJwZDZD";

  if(isset($_REQUEST['hub_challenge'])){
    $challenge = $_REQUEST['hub_challenge'];
    $token = $_REQUEST['hub_verify_token'];
  }
  if($token == "psToken1234#"){
    echo $challenge;
  }

  $message = "height of taj";

  $input = json_decode(file_get_contents('php://input'), true);

  $userID = $input['entry'][0]['messaging'][0]['sender']['id'];

  $message = $input['entry'][0]['messaging'][0]['message']['text'];

  $reply = "I don't understand. Ask me to 'tell a joke'. ;-)";

  $encodedmsg = rawurlencode($message);

  echo $encodedmsg;

  if($message){
    //$res = json_decode(file_get_contents('http://api.icndb.com/jokes/random'), true);
    //$reply = $res['value']['joke'];
    $res = simplexml_load_file("http://api.wolframalpha.com/v2/query?appid=Y54W77-QTLUYKJL75&input=$encodedmsg");
    echo $res;
    $reply = $res->plaintext;
    echo $reply;
  }


    $url = "https://graph.facebook.com/v2.6/me/messages?access_token=$accessToken";

    $jsonData = "{
      'recipient': {
        'id': $userID
      },
      'message': {
        'text': '".addslashes($reply)."'
      }
    }";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);

    if(!empty($input['entry'][0]['messaging'][0]['message'])){
      curl_exec($ch);
      //..........
    }
 ?>
