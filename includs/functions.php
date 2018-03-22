<?php

require_once './config.php';

function calcDimension($x1,$y1,$x2){
    $y2 = $y1 * $x2 / $x1;
    return $y2;
}
function getRandName($prefix = ''){
   
    return str_replace('.','_',uniqid($prefix, true));
}

function getGdImage($path){
   $info = getimagesize($path);
   $img = false;
   switch($info[2]){
       case 1:
           $img = imagecreatefromgif($path);
        break;
    case 2:
           $img = imagecreatefromjpeg($path);
        break;

    case 3:
           $img = imagecreatefrompng($path);
        break;
    
    default:
           $img = false;
        break;
   }
    
    return [$img, $info[0], $info[1], $info[2]];
    
}

function createResample($srcImg, $srcW, $srcH, $dstW, $dstH, $filetype, $path, $filename){
$dstPath = false;
$dstImg = imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

if($filetype === 2){
    $dstPath = $path. $filename. '.jpeg';
    imagejpeg($dstImg, $dstPath);
}elseif ($filetype === 3){
    $dstPath = $path. $filename. '.png';
    imagepng($dstImg, $dstPath);
}else{
    return false;
}
return $dstPath;
}

function getImageFileType($path) {
    $types = ['', 'gif', 'jpeg', 'png'];
    $type = getimagesize($path)[2];
    if ($type > 0 && $type < 4) {
        return $types[$type];
    }
    return false;
}

function uploadFile($tmpName, $path, $dstName = false) {
    $n = ($dstName) ? $dstName : getRandName() . '.' . getImageFileType($tmpName);
    if (move_uploaded_file($tmpName, $path . $n)) {
        return $path . $n;
    }
    return FALSE;
}
function uploadFiles($files, $path){
    $uploaded = [];
    for ($i = 0; $i < count($files['tmp_name']); $i++) {
        $uploade[] = uploadFile($files['tmp_name'][$i], $path);
    }
    return $uploade;
}

 function createThumbnails($files){
        
        for ($i = 0; $i < count($files); $i++) {
        $gdImg = getGdImage($files[$i]);
        $dstH = intval(calcDimension($gdImg[1], $gdImg[2], THUMB_WIDTH));
        $name = pathinfo($files[$i])['filename'] . '_' . THUMB_WIDTH . 'x' . $dstH;
        createResample($gdImg[0], $gdImg[1], $gdImg[2], THUMB_WIDTH, $dstH, IMAGETYPE_JPEG, PATH_THUMBNAILS, $name);
    }
    }