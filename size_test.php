<?php

$y = imagecreate(1240,1748);

imagecolorallocate($y, 0, 255, 0);

$outfilename = "T2.png";
imagepng($y, $outfilename);
imagedestroy($y);
