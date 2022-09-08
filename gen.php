<?php

include 'qrcode.php';

function data_provider($n, $hash_lenght=10) 
{
    $set = [];

    while(count($set) != $n) 
    {
        $hash = substr(str_shuffle(MD5(microtime())), 0, 10);
        if(in_array($hash, $set)) 
        {
            continue;
        }
        $set[] = $hash;
    }
    return $set;
}


function create_QR($path, $filename, $data) {
    $options = [];
    $generator = new QRCode($data, $options);

    /* Create bitmap image. */
    $image = $generator->render_image();
    imagepng($image, $path . "/" . $filename . '.png');
    imagedestroy($image);
}


function gen($path, $gen_amount) 
{
    $data = data_provider($gen_amount);
    file_put_contents($path."/hashset", json_encode($data), LOCK_EX);

    for($i = 0; $i < count($data); $i++)
    {
        create_QR($path, $data[$i], $data[$i]);
    }
}


$path = __DIR__."/".$argv[1];
$gen_amount = $argv[2];

if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

gen($path, $gen_amount);


?>