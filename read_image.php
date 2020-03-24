<?php
/**Decoding the image to access in the database 
init_set('display_errors', 'On');

if(isset($GET['id'])){
    require_once 'database.php';
    $sql = 'SELECT filename, filecontent from images where id="'.$_GET['id'].'"';
    $rExe = $pdo->query($sql);
    $result = $rExe->fetch();
    $data = base64_decode($result["filecontent"]);
    $img = imagecreatefromstring($data);
    header('Content-Type: '.$result["filename"]);
    echo $imagejpeg[$img];
}else{
    echo "Image not found";
}
*/

  ini_set('display_errors', 'On');
    if(isset($_GET['id'])) {
        require_once "database.php";
        $sql = 'SELECT mime, data from images where id="'.$_GET['id'].'"';
        $r = $pdo->query($sql);
        $result = $r->fetch();
        $data = base64_decode($result["data"]);
        $img = imagecreatefromstring($data);

        $newImage = imagecreatetruecolor(25, 25);
        imagecopyresampled($newImage, $img, 0, 0, 0, 0, 25, 25, imagesx($img), imagesy($img));



        header('Content-Type: '.$result["mime"]);
        imagejpeg($newImage);
    }else{

        echo "not found";
    }



?>