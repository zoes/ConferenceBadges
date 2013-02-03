<?php
// load the API Client library
include "EB/Eventbrite.php"; 
include "functions.php";
include_once('phpqrcode/qrlib.php'); 

$appdata = getAuthData('/etc/ebkeys');

global $badge_width;
global $badge_height;

$badge_width = 800;
$badge_height = $badge_width * sqrt(2); //Correct dimensions for A5


//Check the the output directory exists and quit if not
if(!is_dir($appdata["outputpath"])) {
    echo "You need to create the output directory first\n";
    exit();
} else {
    echo "\nWriting output to " . $appdata["outputpath"] . "\n\n";
}

$authentication_tokens = array('app_key'  => $appdata['appkey'],
                               'user_key' => $appdata['userkey']);

$eb_client = new Eventbrite( $authentication_tokens );

//Get the ticket types, use a local cached copy if there is one
if(file_exists($appdata['temprespfle'])) {
    $resp = unserialize(file_get_contents($appdata['temprespfle']));
}else {
    $resp = $eb_client->event_get( array('id'=>$appdata['eventkey'])  );
    $s_resp = serialize($resp);
    file_put_contents($appdata['temprespfile'], $s_resp);
}

//Divide the ticket types by day and attendee type
$tickets = get_ticket_categories($resp->event->tickets);
var_dump($tickets);


foreach ($tickets as $id => $list) {
make_badge_template($list["delegate_type"], $list["days"],  $appdata["outputpath"], $appdata["fridaysched"], $appdata["saturdaysched"], $appdata["header"], 60);
}
