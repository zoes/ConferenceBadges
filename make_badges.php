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
    $summary .=  "ID: " .$id. ", " .$list["first_name"]. ", " . $list["last_name"]. ", " . $tickets[$id]. ", " . $list["barcode"]. ", " .$tickets[$list["ticket_type"]]. "\n";
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
        $fn = $list["first_name"];
    }
    if(strlen($list["last_name"]) > $lnl) {
        $lnl = strlen($list["last_name"]);
        $lnid = $id;
        $ln = $list["last_name"];
    }
    if(strlen($list["job_title"]) > $rol) {
        $rol = strlen($list["job_title"]);
        $roid = $id;
        $ro = $list["job_title"];
    }
    if(strlen($list["company"]) > $col) {
        $col = strlen($list["company"]);
        $coid = $id;
        $co = $list["company"];
    }

}
echo "Longest first name $fn ($fnl) on badge id $fnid\n";
echo "Longest last name $ln ($lnl) on badge id $lnid\n";
echo "Longest role  name $ro ($rol) on badge id $roid\n";
echo "Longest company name $co ($col) on badge id $coid\n";


//Print names and QR codes on badges

//Create all barcode files
//The size of these needs to be relative to the badge size but still an integer. 8 is right for 800 width.

$size = floor($badge_width / 100);
echo "Total attendees ". count($attendee_info) . "\n";
foreach($attendee_info as $id => $list) {
    
    //Long first name
    
    if($id=="172053468"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", $size, 2);
    }
    //Long last name
    if($id=="172807032"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", $size, 2);
    }
    //Long role name
    if($id=="178200170"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", $size, 2);
    }
    //long company name
    if($id=="152807137"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", $size, 2);
    }

    if($id=="166728340"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", $size, 2);
    }
    if($id=="168433690"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        QRcode::png($list["barcode"], $filename, "L", $size, 2);
    }
    /*
     $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
     QRcode::png($list["barcode"], $filename, "L", $size, 2);
     */
}
//Count badges of each type

foreach($tickets as $tt => $tn) {
    $name = $tn["days"] . "_" . $tn["delegate_type"];
    $badge_count[$name] = 0;
    $dels[$name]= array();
}
foreach($attendee_info as $id => $list) {
    //For testing - just make sample badges
   
    if($id=="166728340"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
        $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"] );
    }
    if($id=="168433690"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
        $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"]);
    }
    //Long first name
    if($id=="172053468"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
        $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"]);
    }
    //Long last name
    if($id=="172807032"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
        $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"]);
    }
    //Long role name
    if($id=="178200170"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
        $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"]);
    }
    //long company name
    if($id=="152807137"){
        $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
        make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
        $filename, $appdata["header"], $tickets[$list["ticket_type"]], $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"]);
    }
    /*
    $filename = $appdata["outputpath"]. "/qrcodes/".$id.".png";
    $tt = $tickets[$list["ticket_type"]];
    make_badge($list["first_name"], $list["last_name"], $list["company"], $list["job_title"], 
    $filename, $appdata["header"], $tt, $appdata["outputpath"], $appdata["border-left"], $appdata["border-top"]);
    
    $name = $tt["days"] . "_" . $tt["delegate_type"];
    $badge_count[$name]++;
    $fname = $list["first_name"] . "_" . $list["last_name"];
    array_push($dels[$name], $fname);
    */
}

//Debug! var_dump($badge_count);

//Debug! print_r(array_count_values($dels["TWO_DELEGATE"]));


?>
