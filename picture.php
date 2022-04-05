<?php
/**
 * 返回图片
 * Author: 诺天 <s235784@gmail.com>
 * Date  : 2022/3/24
 * Time  : 21:20
 */

header('Content-type: image/jpeg');

$url=urldecode($_GET["url"]);
$referer=urldecode($_GET["from"]);

if (empty($url)) {
    header('HTTP/1.1 500 Internal Server Error');
    die();
}

if (!empty($referer)) {
    $opt=array('http'=>array('header'=>"Referer: $referer"));
    $context=stream_context_create($opt);
    $file_contents = file_get_contents($url,false, $context);
} else {
    $file_contents = file_get_contents($url);
}

if (empty($file_contents)) {
    header('HTTP/1.1 500 Internal Server Error');
    die();
} else {
    if (isImage($file_contents)){
        echo $file_contents;
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        die();
    }
}

function isImage($image)
{
    $bits = array(
        "\xFF\xD8\xFF",
        "GIF",
        "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a",
        'BM',
    );
    foreach ($bits as $bit) {
        if (substr($image, 0, strlen($bit)) === $bit) {
            return true;
        }
    }
    return false;
}