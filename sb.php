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
<?php
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
        <h1><?=$json['streams']['id'] ?>:HD</h1>
        <video controls="true" src="<?= str_replace('_1','_3',$url) ?>"></video> <br>
        <h1><?=$json['streams']['id'] ?>:SD</h1>
        <video controls="true" src="<?= $url ?>"></video> <br>
    <?php } else if (isset($_GET['m3u'])) {
        header('Content-Type: application/vnd.apple.mpegurl');
        ?>
#EXTM3U
#EXT-X-VERSION:3
#EXT-X-STREAM-INF:BANDWIDTH=1,CODECS="avc1.66.31,mp4a.40.2",RESOLUTION=1280x720
<?php echo str_replace('playlist.m3u8', 'chunklist.m3u8', $url);
    }
} else {
    ?>
    <ol type="1">
        <?php
        $videos = json_decode(curl("http://news.sportbox.ru/api2/rubricvideo?term_id=7212&app_id=android%2F3&page_size=100"), true);
        foreach ($videos['nodes'] as $video) {
            $id = $video['video']['streams'][1]['id'];
            //$id_auto = $video['video']['streams'][2]['id'];
            ?>
            <li>
                <?=($video['media_state'] == 'live' ? '[LIVE] ' : '') ?><?= $video['title'] ?>
                <a href="?id=<?= $id ?>&video">Video</a></li>
        <?php } ?>
    </ol>
    <?php
}
if (!isset($_GET['m3u'])){ ?>
</body>
<?php
}
?>
