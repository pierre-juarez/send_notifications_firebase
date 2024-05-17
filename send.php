

<?php

function sendNotificationPush($token, $title, $message) {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $serverKey = 'SERVER_KEY'; // Reemplaza esto con tu clave del servidor de Firebase

    $notification = [
        'title' => $title,
        'body' => $message
    ];

    $data = [
        'notification' => $notification,
        'to' => $token,
    ];

    $options = [
        'http' => [
            'header' => "Authorization: key=$serverKey\r\n" . "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return ($result);
}


// Data que viene de la API de getUniqueID's
$objectsData = [
  [
      "uniqueID" => "!23242424",
      "token" => "token1"
  ],
  [
      "uniqueID" => "!55555555",
      "token" => "token2"
  ]
];
$titleNotification = 'Título de la notificación';
$messageNotification = 'Este es el mensaje de la notificación.';

function sendNotifications($objectArray, $title, $message){
  $countSuccess = 0;
  $countFailed = 0;
  $arrayFail = [];

  if(count($objectArray) > 0){
    foreach ($objectArray as $object) {
      if(sendNotificationPush($object['token'], $title, $message)){
        $countSuccess++;
      }else{
        $countFailed++;
        array_push($arrayFail, $object);
      }

    }
  }
  return array("exit" => $countSuccess, "failed" => $countFailed, "detailFail" => $arrayFail);
}


// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = sendNotifications($objectsData, $titleNotification, $messageNotification);
    echo json_encode($response); 
}


?>

