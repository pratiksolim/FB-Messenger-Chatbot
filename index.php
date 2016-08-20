<?php

  $accessToken = "EAAJTC6sof2wBACGf5fYprTX2fqKz5Ckr80OuHtaXOJeBZAfqHZCySZAreC1MsDMjnQKucHLV1MMKVQiRxeIgyVjvV6JGCLa6nXzM7vO8WqZC2dCKN1VzESxAi1l7ULUEFO9W3Wow1v5Ayf2qvMbEZBr5zmF1hAtRDhoAFxmStJwZDZD";

  if(isset($_REQUEST['hub_challenge'])){
    $challenge = $_REQUEST['hub_challenge'];
    $token = $_REQUEST['hub_verify_token'];
  }
  if($token == "psToken1234#"){
    echo $challenge;
  }

  $url = "https://graph.facebook.com/v2.6/me/messages?access_token=$accessToken";

  $jsonData = "{
    'setting_type':'greeting',
    'greeting':{
    'text':'Hi, I am Arceus. You can ask me any general query & I will answer in minimal time.'
    }
  }";

  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

  curl_exec($ch);

  $input = json_decode(file_get_contents('php://input'), true);

  $userID = $input['entry'][0]['messaging'][0]['sender']['id'];

  $message = $input['entry'][0]['messaging'][0]['message']['text'];

  $encodedmsg = rawurlencode($message);

  $finalmsg = str_replace("?","%3F",$encodedmsg);

  function get($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }

  if($message){
    $apiurl = "http://api.wolframalpha.com/v2/query?input=".$finalmsg."&appid=Y54W77-QTLUYKJL75&format=plaintext&excludepodid=Image:PeopleData&podtitle=Result";
    $res = file_get_contents($apiurl);
    $reply = get($res,'<plaintext>','</plaintext>');
  }

  if(!$reply){
    $reply = "I am unable to get any information regarding this query. Sorry for inconvenience.";
  }

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

    if(!empty($input['entry'][0]['messaging'][0]['message'])){
      curl_exec($ch);
    }
 ?>
