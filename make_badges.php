<?php
// load the API Client library
include "EB/Eventbrite.php";
include "functions.php";
include_once('phpqrcode/qrlib.php');

//Read in configurable data
$appdata = getAuthData('/etc/ebkeys');

global $badge_width;
global $badge_height;

//Same width as the header and schedule images
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

//Get the information about people who have tickets for the event

try{

    if(file_exists($appdata['tempfile'])) {
        $attendees = unserialize(file_get_contents($appdata['tempfile']));
    } else {
        $attendees = $eb_client->event_list_attendees( array('id'=>$appdata['eventkey'], 'show_full_barcodes'=>'true') );
        $s_attendees = serialize($attendees);
        file_put_contents($appdata['tempfile'], $s_attendees);
    }
} catch ( Exception $e ) {

    var_dump($e);
    $attendees = array();
}

//Get all the information we want out of the eventbrite object

$attendee_info = attendee_list_get_info($attendees);

$summary="";
foreach($attendee_info as $id => $list) {
    $summary .=  "ID: " .$id. ", " .$list["first_name"]. ", " . $list["last_name"]. ", " . $tickets[$id]. ", " . $list["barcode"]. "\n";
}
file_put_contents($appdata["outputpath"]."/summary.txt", $summary);

//Check the max lenths
$fnl = 0;
$lnl = 0;
$rol = 0;
$col = 0;

foreach($attendee_info as $id => $list) {
    if(strlen($list["first_name"]) > $fnl) {
        $fnl = strlen($list["first_name"]);
        $fnid = $id;
    }
    if(strlen($list["last_name"]) > $lnl) {
        $lnl = strlen($list["last_name"]);
        $lnid = $id;
    }
    if(strlen($list["job_title"]) > $rol) {
        $rol = strlen($list["job_title"]);
        $roid = $id;
    }
    if(strlen($list["company"]) > $col) {
        $col = strlen($list["company"]);
        $coid = $id;
    }

}
echo "Longest first name $fnl on badge id $fnid\n";
echo "Longest last name $lnl on badge id $lnid\n";
echo "Longest role  name $rol on badge id $roid\n";
echo "Longest company name $col on badge id $coid\n";


//Print names and QR codes on badges

//Create all barcode files
//The size of these needs to be relative to the badge size but still an integer. 8 is right for 800 width.

$size = floor($badge_width / 100);

foreach($attendee_info as $id => $list) {
    //Long first name
    if($id=="172053468"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", 8, 2);
    }
    //Long last name
    if($id=="172807032"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", 8, 2);
    }
    //Long role name
    if($id=="170810956"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", 8, 2);
    }
    //long company name
    if($id=="152807137"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", 8, 2);
    }

    if($id=="166728340"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", 8, 2);
    }
    if($id=="168433690"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", 8, 2);
    }
}

foreach($attendee_info as $id => $list) {
    //For testing - just make two badges
    if($id=="166728340"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], 60);
    }
    if($id=="168433690"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], 60);
    }
    //Long first name
    if($id=="172053468"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], 60);
    }
    //Long last name
    if($id=="172807032"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], 60);
    }
    //Long role name
    if($id=="170810956"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], 60);
    }
    //long company name
    if($id=="152807137"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], 60);
    }
}


?>
