<?php




$message = [
    'text' => '',
    'type' => 'info'
];

if (isset($_FILES['uploadFiles'])) {
    $imageTypes = ['', '.gif', '.jpeg', '.png'];
    $amountImages = count($_FILES['uploadFiles']['name']);
    
    for ($i = 0; $i < $amountImages; $i++) {
        $src = $_FILES['uploadFiles']['tmp_name'][$i];
        $imgInfo = getimagesize($src);
    $imgType = $imgInfo[2]; //1 (gif), 2 (jpeg), 3 (png).
    
    if($imgType >=1 && $imgType <= 3){
        $dst = './uploads/'.uniqid('634287', true). $imageTypes[$imgType];
        move_uploaded_file($src, $dst);
    }
    
 }
 header("Location:files_multiple.php");
    
}