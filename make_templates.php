<?php
// load the API Client library
include "EB/Eventbrite.php"; 
include "functions.php";
include_once('phpqrcode/qrlib.php'); 

$appdata = getAuthData('/etc/ebkeys');

global $badge_width;
global $badge_height;

$badge_width = 1116;
$badge_height = 1624;


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
if(file_exists($appdata['temprespfile'])) {
    $resp = unserialize(file_get_contents($appdata['temprespfile']));
}else {
    $resp = $eb_client->event_get( array('id'=>$appdata['eventkey'])  );
    $s_resp = serialize($resp);
    file_put_contents($appdata['temprespfile'], $s_resp);
}

//Divide the ticket types by day and attendee type
$tickets = get_ticket_categories($resp->event->tickets);
//var_dump($tickets);
//make a dummy tickets file
/*
$tickets = array(0 => array("delegate_type" => "DELEGATE", "days"=>"TWO"),
                 1 => array("delegate_type" => "DELEGATE", "days"=>"FRIDAY"),
                 2 => array("delegate_type" => "DELEGATE", "days"=>"SATURDAY"),
                 3 => array("delegate_type" => "SPEAKER", "days"=>"TWO"),
                 4 => array("delegate_type" => "SPONSOR", "days"=>"FRIDAY"),
                 5 => array("delegate_type" => "SPONSOR", "days"=>"SATURDAY"),
                 6 => array("delegate_type" => "SPONSOR", "days"=>"TWO"),
                 7 => array("delegate_type" => "VOLUNTEER", "days"=>"TWO"),
                 8 => array("delegate_type" => "VOLUNTEER", "days"=>"FRIDAY"),
                 9 => array("delegate_type" => "VOLUNTEER", "days"=>"SATURDAY"),
                 10 => array("delegate_type" => "ORGANISER", "days"=>"TWO"),
                 11=> array("delegate_type" => "KEYNOTE", "days"=>"TWO"),
                 12=> array("delegate_type" => "EXHIBITOR", "days"=>"TWO"),
                 13=> array("delegate_type" => "EXHIBITOR", "days"=>"ONE")
                 );
*/
foreach ($tickets as $id => $list) {
make_badge_template($list["delegate_type"], $list["days"],  $appdata["outputpath"], 
                    $appdata["fridaysched"], $appdata["saturdaysched"], $appdata["header"], 
                    $appdata["border-left"], $appdata["border-top"], $appdata["badge-base"]);
}
