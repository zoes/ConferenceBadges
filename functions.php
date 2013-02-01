<?php
function make_badge_template($att_type, $days, $outpath, $friday_schedule, $saturday_schedule, $headerfile, $border) {
    //This makes all the badge templates

    //Set some colours
    global $badge_width;
    global $badge_height;

    $grey = html2rgb("#efefef");
    $gold = html2rgb("#da9e26");
    $blue = html2rgb("#60B5C9");
    $brightblue = html2rgb("#2492a2");
    $darkgold = html2rgb("#ac7916");
    $white = html2rgb("#ffffff");
    $black = html2rgb("#000000");
    $yellow = html2rgb("#fffa90");
    $red = html2rgb("#DA25BC");
    $rblue = html2rgb("#2561DA");
    $green = html2rgb("#25DA43");
    $spkr = html2rgb("#DA6125");
    $exhi = html2rgb("#808080");
    $spon = html2rgb("#25BCDA");

    //Use a very standard font - niche ones do not have good enough support for accents (eg caron).
    $font_path = 'arial';

    //The image width is wider than the badge
    $imw = $badge_width * 2 + $border * 4;
    $imh = $badge_height + $border * 2;

    //set up vertical positions for elements
    $indicator_posn = .595 * $badge_height;
    $indicatorbar_height = 0.008 * $badge_height;
    $del_strip_height = .08 * $badge_height;
    $del_stip_posn = .6 * $badge_height;
    $sched_posn = .6837 * $badge_height;
     
    //The image has back and front views of the badge - designed to be printed on an A4 sheet
    $image = imagecreate($imw, $imh);

    //Allocate colors for different badge types
    imagecolorallocate($image, $white[0], $white[1], $white[2]);
    imagecolorallocate($image, $rblue[0], $rblue[1], $rblue[2]);
    imagecolorallocate($image, $red[0], $red[1], $red[2]);
    imagecolorallocate($image, $green[0], $green[1], $green[2]);
    imagecolorallocate($image, $gold[0], $gold[1], $gold[2]);
    imagecolorallocate($image, $black[0], $black[1], $black[2]);
    imagecolorallocate($image, $yellow[0], $yellow[1], $yellow[2]);
    imagecolorallocate($image, $spkr[0], $spkr[1], $spkr[2]);
    imagecolorallocate($image, $exhi[0], $exhi[1], $exhi[2]);
    imagecolorallocate($image, $spon[0], $spon[1], $spon[2]);


     
    //Get the conference logo and copy it onto the image
    list($width, $height, $type, $attr) = getimagesize($headerfile);
    $logo=imagecreatefrompng($headerfile);
    imagecopy($image, $logo, $border, $border, 0, 0, $width, $height);
    imagecopy($image, $logo, $badge_width + $border * 3, $border, 0, 0, $width, $height);
    imagedestroy($logo);

     
    //Bar to indicate Friday, Saturday or two day tickets.
    $indicatorbar = imagecreate($badge_width, $indicatorbar_height);

    if($days == "TWO") {
        $rb = imagecolorallocate($indicatorbar, $rblue[0], $rblue[1], $rblue[2]);
    } else if ($days == "FRIDAY") {
        $rd = imagecolorallocate($indicatorbar, $red[0], $red[1], $red[2]);
    } else if ($days == "SATURDAY") {
        $rg = imagecolorallocate($indicatorbar, $green[0], $green[1], $green[2]);
    } else {
        $rgg = imagecolorallocate($indicatorbar, $gold[0], $gold[1], $gold[2]);
    }

    imagecopy($image, $indicatorbar, $border, $indicator_posn + $border, 0, 0, $badge_width, $indicatorbar_height);
    imagecopy($image, $indicatorbar, $badge_width + $border * 3 , $indicator_posn + $border, 0, 0, $badge_width, $indicatorbar_height);
    imagedestroy($indicatorbar);


    //A coloured bar the indicates the type of delegate
    $del = imagecreate($badge_width, $del_strip_height);
    if($att_type == "DELEGATE"){
        $gold_d = imagecolorallocate($del, $gold[0], $gold[1], $gold[2]);

    } else if ($att_type == "SPEAKER") {
        $spkr_d = imagecolorallocate($del, $spkr[0], $spkr[1], $spkr[2]);
         
    } else if ($att_type == "EXHIBITOR") {
        $exhi_d = imagecolorallocate($del, $exhi[0], $exhi[1], $exhi[2]);
         
    } else if ($att_type == "SPONSOR") {
        $spon_d = imagecolorallocate($del, $spon[0], $spon[1], $spon[2]);

    } else {
        echo "WARNING - UNKNOWN ATTENDEE TYPE \n";
        $wh_d = imagecolorallocate($del, $white[0], $white[1], $white[2]);
    }

    $black_d = imagecolorallocate($del, $black[0], $black[1], $black[2]);
    
    //text placement
    $size = 0.04167 * $badge_height;
    $xp = 0.312 * $badge_width;
    $yp = 0.75 * $del_strip_height;
    imagettftext($del, $size, 0, $xp, $yp, $black_d, $font_path, $att_type);

    imagecopy($image, $del, $border,  $del_stip_posn + $border, 0, 0, $badge_width, $del_strip_height);
    imagecopy($image, $del, $badge_width + $border * 3,  $del_stip_posn + $border, 0, 0, $badge_width, $del_strip_height);
    imagedestroy($del);

    //Get the schedules to append to the tickets
    list($width, $height, $type, $attr) = getimagesize($friday_schedule);
    $fschedule=imagecreatefrompng($friday_schedule);
    $sschedule=imagecreatefrompng($saturday_schedule);
    if($days == "TWO") {
        imagecopy($image, $fschedule, $border, $sched_posn + $border, 0, 0, $width, $height);
        imagecopy($image, $sschedule, $badge_width + $border * 3, $sched_posn + $border, 0, 0, $width, $height);
    } else if ($days == "FRIDAY") {
        imagecopy($image, $fschedule, $border, $sched_posn + $border, 0, 0, $width, $height);
        imagecopy($image, $fschedule, $badge_width + $border * 3, $sched_posn + $border, 0, 0, $width, $height);
    } else if ($days == "SATURDAY") {
        imagecopy($image, $sschedule, $border, $sched_posn + $border, 0, 0, $width, $height);
        imagecopy($image, $sschedule, $badge_width + $border * 3, $sched_posn + $border, 0, 0, $width, $height);
         
    } else {
        imagecopy($image, $fschedule, $border, $sched_posn + $border, 0, 0, $width, $height);
        imagecopy($image, $sschedule, $badge_width + $border * 3, $sched_posn + $border, 0, 0, $width, $height);
    }


    imagedestroy($fschedule);
    imagedestroy($sschedule);
     
    //save the image as a png and output
    $outfilename = $outpath. "/templates/" .$days. "_" .$att_type. ".png";
    imagepng($image, $outfilename);
    imagedestroy($image);
     
}



function attendee_list_get_info( $attendees ) {
    $attendee_info = array();
    if( isset($attendees->attendees) ){
        foreach( $attendees->attendees as $attendee ){
            $id = $attendee->attendee->id;
            $barcode =  $attendee->attendee->barcodes[0]->barcode->id;
            $fname = $attendee->attendee->first_name;
            $lname = $attendee->attendee->last_name;
            $ticket_type = $attendee->attendee->ticket_id;
            $company = $attendee->attendee->company;
            $job_title = $attendee->attendee->job_title;
            $attendee_info[$id] = array("first_name"=>$fname, "last_name"=>$lname, "ticket_type"=>$ticket_type, "barcode"=>$barcode, "job_title" => $job_title, "company" => $company);
        }
    }else{
        //No attendees
    }
    return $attendee_info;

}

function make_badge($firstname, $lastname, $company, $job_title, $qrfile, $headerfile, $ticket_type, $outpath, $border) {
    //Size determined by header image
    global $badge_width;
    global $badge_height;
    //Set some colours


    $grey = html2rgb("#efefef");
    $gold = html2rgb("#da9e26");
    $blue = html2rgb("#60B5C9");
    $brightblue = html2rgb("#2492a2");
    $darkgold = html2rgb("#ac7916");
    $white = html2rgb("#ffffff");
    $black = html2rgb("#000000");
    $red = html2rgb("#DA25BC");
    $rblue = html2rgb("#2561DA");
    $green = html2rgb("#25DA43");

    //Get a badge template
    $template = $outpath. "/templates/" .$ticket_type["days"]. "_" . $ticket_type["delegate_type"]. ".png";
    $badge_template = imagecreatefrompng($template);
    $bbb = imagecolorallocate($badge_template, $brightblue[0], $brightblue[1], $brightblue[2]);
     
    //Set heights and positions of elements
    $del_details_height = 0.275 * $badge_height;
    $inset = 0.07 * $badge_width;
    $del_details_posn = .31 * $badge_height;

    $font_path = 'arial';


    $delegate_details = imagecreate($badge_width, $del_details_height);
    $wh = imagecolorallocate($delegate_details, $white[0], $white[1], $white[2]);
    $bb = imagecolorallocate($delegate_details, $black[0], $black[1], $black[2]);
    $bbb = imagecolorallocate($delegate_details, $brightblue[0], $brightblue[1], $brightblue[2]);
    //Write the name on the badge.

    //$size = .0547 * $badge_height;
    $size = .04 * $badge_height;
    $y = .242 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bb, $font_path, $firstname);

    $y = .4704 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bb, $font_path, $lastname);

    //$size = .02708 * $badge_height;
    $size = .019 * $badge_height;
    $y = .6585 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bbb, $font_path, $company);
    $y = .7930 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bbb, $font_path, $job_title);



    //get the QR code and copy it onto the image
    list($width, $height, $type, $attr) = getimagesize($qrfile);
    $qr=imagecreatefrompng($qrfile);
    $y = 0.208 * $del_details_height;
    $x = .6850 * $badge_width;
    imagecopy($delegate_details, $qr, $x, $y, 0, 0, $width, $height);



    imagecopy($badge_template, $delegate_details, $border, $del_details_posn + $border, 0, 0, $badge_width, $del_details_height);
    imagecopy($badge_template, $delegate_details, $badge_width + $border * 3, $del_details_posn + $border, 0, 0, $badge_width, $del_details_height);

    //save the image as a png and output
    $outfilename = $outpath. "/badges/" .$firstname. "_" .$lastname. ".png";
    imagepng($badge_template, $outfilename);



    //Clear up memory used by images
    imagedestroy($badge_template);
    imagedestroy($qr);
    imagedestroy($delegate_details);

     
}
/*
 * Read the user id, password etc from a file.
 */
function getAuthData($authdata) {

    $input = file($authdata);
    foreach($input as $line) {
        if((substr($line, 0, 1) != '#') && (trim($line) != "") ){
            list($key, $value)  = explode(':',trim($line));
            $data[$key] = $value;
        }
    }
    return $data;
}

function html2rgb($color)
{
    if ($color[0] == '#')
    $color = substr($color, 1);

    if (strlen($color) == 6)
    list($r, $g, $b) = array($color[0].$color[1],
    $color[2].$color[3],
    $color[4].$color[5]);
    elseif (strlen($color) == 3)
    list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
    return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}
function get_ticket_categories($ticket_types) {
    $tickets = array();

    foreach( $ticket_types as $ticket) {

        if(preg_match("/wo day/", $ticket->ticket->name)) {
            if(preg_match("/onsor/", $ticket->ticket->name)) {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "SPONSOR", "days" => "TWO");

            } else {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "DELEGATE", "days" =>"TWO");
            }

        } else if (preg_match("/riday/", $ticket->ticket->name)) {
            if(preg_match("/onsor/", $ticket->ticket->name)) {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "SPONSOR","days" =>"FRIDAY");
            } else {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "DELEGATE", "days" =>"FRIDAY");
            }

        } else if (preg_match("/aturday/", $ticket->ticket->name)) {
            if(preg_match("/onsor/", $ticket->ticket->name)) {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "SPONSOR", "days" =>"SATURDAY");
            } else {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "DELEGATE", "days" =>"SATURDAY");
            }
        } else if (preg_match("/xhibit/", $ticket->ticket->name)) {
            if(preg_match("/one/", $ticket->ticket->name)) {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "EXHIBITOR", "days" =>"ONE");
            } else {
                $tickets[$ticket->ticket->id] = array("delegate_type" => "EXHIBITOR", "days" =>"TWO");
            }
        } else if (preg_match("/SPKR/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "SPEAKER", "days" =>"TWO");
        }else {
            echo "Ticket type has no day" . $ticket->ticket->name . "\n";
        }
    }

    if(count($tickets) != count($ticket_types)) {
        echo "\nWARNING: Failed to identify one or more ticket types \n";
    }
    return $tickets;

}


