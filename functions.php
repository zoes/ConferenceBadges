<?php
function make_badge_template($att_type, $days, $outpath, $friday_schedule, $saturday_schedule, $headerfile, $border_left, $border_top, $badge_base) {
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
    //$red = html2rgb("#FF0000");
    $rblue = html2rgb("#2561DA");
    $green = html2rgb("#25DA43");
    //$green = html2rgb("#00FF00");
    $del_text = html2rgb("#2492a2");
    
    // Delegates are gold
    $spkr = html2rgb("#DA6125");  //comp 249EDA
    $spkr_text = html2rgb("#60B5C9");
    $exhi = html2rgb("#808080");
    $spon = html2rgb("#25BCDA"); //comp DA4224
    $spon_text = html2rgb("#DA4224");
    
    //$keyn = html2rgb("#9D24DA"); //Purple
    $keyn = html2rgb("#561A0E"); //
    $keyn_text = html2rgb("#D94123");
    $volu = html2rgb("#0E4B57"); //Darker blue  //57190D
    $volu_text = html2rgb("#4ECAE2");
    $dire = html2rgb("#24DA9D"); //Dark aqua //comp DA2461
    //$dire_text = html2rgb("#0E563E");
    $dire_text = html2rgb("#DA2461"); //comp
    
    /*
    $spar = html2rgb("#561A0E"); // Dark red (probably too dark for black text)
    */

    //Use a very standard font - niche ones do not have good enough support for accents (eg caron).
    $font_path = 'arial_bold';
    //$font_path = 'steelfish_rg-webfont';
    

    //The image width is wider than the badge
    $imw = $badge_width + $border_left * 2;
    $imh = $badge_height + $border_top * 2;

    //set up vertical positions for elements
    $indicator_posn = .6 * $badge_height;
    $indicatorbar_height = 0.008 * $badge_height;
    $del_strip_height = .08 * $badge_height;
    $del_strip_posn = .605 * $badge_height;
    $sched_posn = .6837 * $badge_height;
     
    //The image has back and front views of the badge - designed to be printed on an A4 sheet
    $base_front = imagecreatefrompng($badge_base);
    $base_back = imagecreatefrompng($badge_base);

    //Allocate colors for different badge types
    imagecolorallocate($base_front, $white[0], $white[1], $white[2]);
    imagecolorallocate($base_front, $rblue[0], $rblue[1], $rblue[2]);
    imagecolorallocate($base_front, $red[0], $red[1], $red[2]);
    imagecolorallocate($base_front, $green[0], $green[1], $green[2]);
    imagecolorallocate($base_front, $gold[0], $gold[1], $gold[2]);
    imagecolorallocate($base_front, $black[0], $black[1], $black[2]);
    imagecolorallocate($base_front, $yellow[0], $yellow[1], $yellow[2]);
    imagecolorallocate($base_front, $spkr[0], $spkr[1], $spkr[2]);
    imagecolorallocate($base_front, $exhi[0], $exhi[1], $exhi[2]);
    imagecolorallocate($base_front, $spon[0], $spon[1], $spon[2]);
    imagecolorallocate($base_front, $volu[0], $volu[1], $volu[2]);
    imagecolorallocate($base_front, $keyn[0], $keyn[1], $keyn[2]);
    imagecolorallocate($base_front, $dire[0], $dire[1], $dire[2]);
    imagecolorallocate($base_front, $dire_text[0], $dire_text[1], $dire_text[2]);
    imagecolorallocate($base_front, $del_text[0], $del_text[1], $del_text[2]);
    imagecolorallocate($base_front, $spkr_text[0], $spkr_text[1], $spkr_text[2]);
    imagecolorallocate($base_front, $keyn_text[0], $keyn_text[1], $keyn_text[2]);
    imagecolorallocate($base_front, $spon_text[0], $spon_text[1], $spon_text[2]);
    
    
    imagecolorallocate($base_back, $white[0], $white[1], $white[2]);
    imagecolorallocate($base_back, $rblue[0], $rblue[1], $rblue[2]);
    imagecolorallocate($base_back, $red[0], $red[1], $red[2]);
    imagecolorallocate($base_back, $green[0], $green[1], $green[2]);
    imagecolorallocate($base_back, $gold[0], $gold[1], $gold[2]);
    imagecolorallocate($base_back, $black[0], $black[1], $black[2]);
    imagecolorallocate($base_back, $yellow[0], $yellow[1], $yellow[2]);
    imagecolorallocate($base_back, $spkr[0], $spkr[1], $spkr[2]);
    imagecolorallocate($base_back, $exhi[0], $exhi[1], $exhi[2]);
    imagecolorallocate($base_back, $spon[0], $spon[1], $spon[2]);
    imagecolorallocate($base_back, $volu[0], $volu[1], $volu[2]);
    imagecolorallocate($base_back, $keyn[0], $keyn[1], $keyn[2]);
    imagecolorallocate($base_back, $dire[0], $dire[1], $dire[2]);
    imagecolorallocate($base_back, $dire_text[0], $dire_text[1], $dire_text[2]);
    imagecolorallocate($base_back, $dire_text[0], $dire_text[1], $dire_text[2]);
    imagecolorallocate($base_back, $del_text[0], $del_text[1], $del_text[2]);
    imagecolorallocate($base_back, $spkr_text[0], $spkr_text[1], $spkr_text[2]);
    imagecolorallocate($base_back, $keyn_text[0], $keyn_text[1], $keyn_text[2]);
    imagecolorallocate($base_back, $spon_text[0], $spon_text[1], $spon_text[2]);


     
    //Get the conference logo and copy it onto the image
    list($width, $height, $type, $attr) = getimagesize($headerfile);
    $logo=imagecreatefrompng($headerfile);
    imagecopy($base_front, $logo, $border_left, $border_top, 0, 0, $width, $height);
    imagecopy($base_back, $logo, $border_left, $border_top, 0, 0, $width, $height);
    imagedestroy($logo);

     
    //Bar to indicate Friday, Saturday or two day tickets.
    
    if($att_type == "DELEGATE") {
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

    imagecopy($base_front, $indicatorbar, $border_left, $indicator_posn + $border_top, 0, 0, $badge_width, $indicatorbar_height);
    imagecopy($base_back, $indicatorbar, $border_left , $indicator_posn + $border_top, 0, 0, $badge_width, $indicatorbar_height);
    imagedestroy($indicatorbar);
    }

    //A coloured bar the indicates the type of delegate
    
      //text placement
    $size = 0.04167 * $badge_height;
    $xp = ($badge_width / 2) - ( (strlen($att_type) -1 ) * $size / 2);
    $yp = 0.75 * $del_strip_height;
    
    
    $del = imagecreate($badge_width, $del_strip_height);
    if($att_type == "DELEGATE"){
        $gold_d = imagecolorallocate($del, $gold[0], $gold[1], $gold[2]);
        $t = imagecolorallocate($del, $del_text[0], $del_text[1], $del_text[2]);
        imagettftext($del, $size, 0, $xp, $yp, $t, $font_path, $att_type);

    } else if ($att_type == "SPEAKER") {
        $spkr_d = imagecolorallocate($del, $spkr[0], $spkr[1], $spkr[2]);
        $t = imagecolorallocate($del, $spkr_text[0], $spkr_text[1], $spkr_text[2]);
        imagettftext($del, $size, 0, $xp, $yp, $t, $font_path, $att_type);
         
    } else if ($att_type == "EXHIBITOR") {
        $exhi_d = imagecolorallocate($del, $exhi[0], $exhi[1], $exhi[2]);
        $black_d = imagecolorallocate($del, $black[0], $black[1], $black[2]);
        imagettftext($del, $size, 0, $xp, $yp, $black_d, $font_path, $att_type);
         
    } else if ($att_type == "SPONSOR") {
        $spon_d = imagecolorallocate($del, $spon[0], $spon[1], $spon[2]);
        $t = imagecolorallocate($del, $spon_text[0], $spon_text[1], $spon_text[2]);
        imagettftext($del, $size, 0, $xp, $yp, $t, $font_path, $att_type);
        
    } else if ($att_type == "VOLUNTEER") {
        $vol_d = imagecolorallocate($del, $volu[0], $volu[1], $volu[2]);
        $t = imagecolorallocate($del, $volu_text[0], $volu_text[1], $volu_text[2]);
        imagettftext($del, $size, 0, $xp, $yp, $t, $font_path, $att_type);
        
    } else if ($att_type == "ORGANISER") {
        $org_d = imagecolorallocate($del, $dire[0], $dire[1], $dire[2]);
        $t = imagecolorallocate($del, $dire_text[0], $dire_text[1], $dire_text[2]);
        imagettftext($del, $size, 0, $xp, $yp, $t, $font_path, $att_type);

    } else if ($att_type == "KEYNOTE") {
        $key_d = imagecolorallocate($del, $keyn[0], $keyn[1], $keyn[2]); 
        $t = imagecolorallocate($del, $keyn_text[0], $keyn_text[1], $keyn_text[2]);
        imagettftext($del, $size, 0, $xp, $yp, $t, $font_path, "SPEAKER");     

    } else {
        echo "WARNING - UNKNOWN ATTENDEE TYPE \n";
        $wh_d = imagecolorallocate($del, $white[0], $white[1], $white[2]);
        $black_d = imagecolorallocate($del, $black[0], $black[1], $black[2]);
        imagettftext($del, $size, 0, $xp, $yp, $black_d, $font_path, $att_type);
    }

   
  

    imagecopy($base_front, $del, $border_left,  $del_strip_posn + $border_top, 0, 0, $badge_width, $del_strip_height);
    imagecopy($base_back, $del, $border_left,  $del_strip_posn + $border_top, 0, 0, $badge_width, $del_strip_height);
    imagedestroy($del);

    //Get the schedules to append to the tickets
    list($width, $height, $type, $attr) = getimagesize($friday_schedule);
    $fschedule=imagecreatefrompng($friday_schedule);
    $sschedule=imagecreatefrompng($saturday_schedule);
    if($days == "TWO") {
        imagecopy($base_front, $fschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
        imagecopy($base_back, $sschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
    } else if ($days == "FRIDAY") {
        imagecopy($base_front, $fschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
        imagecopy($base_back, $fschedule, $border_left , $sched_posn + $border_top, 0, 0, $width, $height);
    } else if ($days == "SATURDAY") {
        imagecopy($base_front, $sschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
        imagecopy($base_back, $sschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
         
    } else {
        imagecopy($base_front, $fschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
        imagecopy($base_back, $sschedule, $border_left, $sched_posn + $border_top, 0, 0, $width, $height);
    }


    imagedestroy($fschedule);
    imagedestroy($sschedule);
     
    //save the image as a png and output
    $outfilename_front = $outpath. "/templates/" .$days. "_" .$att_type. "_a.png";
    $outfilename_back = $outpath. "/templates/" .$days. "_" .$att_type. "_b.png";
    imagepng($base_front, $outfilename_front);
    imagepng($base_back, $outfilename_back);
    imagedestroy($base_front);
    imagedestroy($base_back);
     
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
            if($company == "---") {$company = "";}
            $job_title = $attendee->attendee->job_title;
            if($job_title == "---") {$job_title = "";}
            $attendee_info[$id] = array("first_name"=>$fname, "last_name"=>$lname, "ticket_type"=>$ticket_type, "barcode"=>$barcode, "job_title" => $job_title, "company" => $company);
        }
    }else{
        //No attendees
    }
    return $attendee_info;

}

function make_badge($firstname, $lastname, $company, $job_title, $qrfile, $headerfile, $ticket_type, $outpath, $border_left, $border_right) {
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
    //echo $firstname, $lastname, $ticket_type["days"], $ticket_type["delegate_type"] . "\n";
    $template_front = $outpath. "/templates/" .$ticket_type["days"]. "_" . $ticket_type["delegate_type"]. "_a.png";
    $template_back = $outpath. "/templates/" .$ticket_type["days"]. "_" . $ticket_type["delegate_type"]. "_b.png";
    $badge_template_front = imagecreatefrompng($template_front);
    $badge_template_back = imagecreatefrompng($template_back);
    $bbb = imagecolorallocate($badge_template_front, $brightblue[0], $brightblue[1], $brightblue[2]);
    $bbf = imagecolorallocate($badge_template_back, $brightblue[0], $brightblue[1], $brightblue[2]);
     
    //Set heights and positions of elements
    $del_details_height = 0.32 * $badge_height;
    $inset = 0.12 * $badge_width;
    $del_details_posn = .31 * $badge_height;

    $font_path = 'arial';


    $delegate_details = imagecreate($badge_width, $del_details_height);
    $wh = imagecolorallocate($delegate_details, $grey[0], $grey[1], $grey[2]);
    $bb = imagecolorallocate($delegate_details, $black[0], $black[1], $black[2]);
    $bbb = imagecolorallocate($delegate_details, $brightblue[0], $brightblue[1], $brightblue[2]);
    //Write the name on the badge.

    //$size = .0547 * $badge_height;
    $size = .03 * $badge_height;
    $y = .45 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bb, $font_path, $firstname);

    $y = .6 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bb, $font_path, $lastname);

    //$size = .02708 * $badge_height;
    $size = .019 * $badge_height;
    $y = .8 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bbb, $font_path, $company);
    $y = .9 * $del_details_height;
    imagettftext($delegate_details, $size, 0, $inset, $y, $bbb, $font_path, $job_title);



    //get the QR code and copy it onto the image
    list($width, $height, $type, $attr) = getimagesize($qrfile);
    $qr=imagecreatefrompng($qrfile);
    $y = 0.208 * $del_details_height;
    $x = .63 * $badge_width;
    imagecopy($delegate_details, $qr, $x, $y, 0, 0, $width, $height);



    imagecopy($badge_template_front, $delegate_details, $border_left, $del_details_posn + $border_top, 0, 0, $badge_width, $del_details_height);
    imagecopy($badge_template_back, $delegate_details, $border_left, $del_details_posn + $border_top, 0, 0, $badge_width, $del_details_height);
    

    //save the image as a png and output
    $outfilename_front = $outpath. "/badges/".$ticket_type["days"]. "_" .$ticket_type["delegate_type"]. "/" .$firstname. "_" .$lastname. "_a.png";
    $outfilename_back = $outpath. "/badges/".$ticket_type["days"]. "_" .$ticket_type["delegate_type"]. "/" .$firstname. "_" .$lastname. "_b.png";
    if(file_exists($outfilename_front)) {
        echo "WARNING Creating duplicate badge for $firstname $lastname\n";
    }
    imagepng($badge_template_front, $outfilename_front);
    imagepng($badge_template_back, $outfilename_back);
  
    //Clear up memory used by images
    imagedestroy($badge_template_front);
    imagedestroy($badge_template_back);
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
        
        
        if (preg_match("/SPKR/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "SPEAKER", "days" =>"TWO");
        
        
        } else if (preg_match("/Keynote/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "KEYNOTE", "days" =>"TWO");
        
        
        } else if (preg_match("/olun/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "VOLUNTEER", "days" =>"TWO");
        
        
        } else if (preg_match("/xhibi/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "EXHIBITOR", "days" =>"TWO"); 
                   
        } else if (preg_match("/ORGANISER/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "ORGANISER", "days" =>"TWO");
            
        } else if (preg_match("/onsor/", $ticket->ticket->name)) {
            $tickets[$ticket->ticket->id] = array("delegate_type" => "SPONSOR", "days" =>"TWO");        
                      
        } else if (preg_match("/wo day/", $ticket->ticket->name)  ){
                $tickets[$ticket->ticket->id] = array("delegate_type" => "DELEGATE", "days" =>"TWO");

        } else if (preg_match("/riday/", $ticket->ticket->name)) {             
                $tickets[$ticket->ticket->id] = array("delegate_type" => "DELEGATE", "days" =>"FRIDAY");
    
        } else if (preg_match("/aturday/", $ticket->ticket->name)) {
            
                $tickets[$ticket->ticket->id] = array("delegate_type" => "DELEGATE", "days" =>"SATURDAY");            
        }else {
            echo "Ticket type has no day" . $ticket->ticket->name . "\n";
        }
    }

    if(count($tickets) != count($ticket_types)) {
        echo "\nWARNING: Failed to identify one or more ticket types \n";
    }
    return $tickets;

}


