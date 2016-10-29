<?php
if (!isset($_GET['m3u'])){
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr" class="client-nojs">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sportb</title>
</head>
<body>
<?
}

function curl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

if (isset($_GET['id'])) {
    $json = json_decode(curl("http://news.sportbox.ru/api2/videostream?id=" . $_GET['id'] . "&app_id=android%2F3"), true);
    $url = $json['streams']['url'];
    if (isset($_GET['video'])) { ?>
        <h1><?=$json['streams']['id'] ?>:<?=$json['streams']['name'] ?></h1>
        <video controls="true" src="<?= $url ?>"></video> <br>
    <? } else if (isset($_GET['m3u'])) {
        header('Content-Type: application/vnd.apple.mpegurl');
        ?>
#EXTM3U
#EXT-X-VERSION:3
#EXT-X-STREAM-INF:BANDWIDTH=1,CODECS="avc1.66.31,mp4a.40.2",RESOLUTION=1280x720
<? echo str_replace('playlist.m3u8', 'chunklist.m3u8', $url);
    }
} else {
    ?>
    <ol type="1">
        <?
        $videos = json_decode(curl("http://news.sportbox.ru/api2/rubricvideo?term_id=7212&app_id=android%2F3&page_size=100"), true);
        foreach ($videos['nodes'] as $video) {
            $id = $video['video']['streams'][2]['id'];
            $id_sd = $video['video']['streams'][1]['id'];
            ?>
            <li>
                <?=($video['media_state'] == 'live' ? '[LIVE] ' : '') ?><?= $video['title'] ?>
                <a href="?id=<?= $id ?>&video">HD Video</a> | <a href="?id=<?= $id ?>&m3u">HD M3U</a> | <a href="?id=<?= $id_sd ?>&video">SD Video</a> | <a href="?id=<?= $id_sd ?>&m3u">SD M3U</a> </li>
        <? } ?>
    </ol>
    <?
}
if (!isset($_GET['m3u'])){ ?>
</body>
<?
}
?>
