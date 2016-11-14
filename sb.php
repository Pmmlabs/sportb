<?php
$M3U_MODE = isset($_GET['m3u']) || isset($_GET['chunklist']);
if ($M3U_MODE) {
    header('Content-Type: application/vnd.apple.mpegurl');
} else {
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
        $playlist = curl($url);
        $streams = array();
        foreach (preg_split('/#EXT-X-STREAM-INF/', $playlist) as $stream) {
            if (preg_match('/BANDWIDTH=(\d+)/', $stream, $matches)) {
                $bitrate = $matches[1];
                if (preg_match('/\S+chunklist\.m3u8.*/', $stream, $matches)) {
                    $streams[$bitrate] = $matches[0];
                }
            }
        }
        krsort($streams);
        $best_chunklist = array_shift($streams);
        print "#EXTM3U\n"
            . "#EXT-X-VERSION:3\n"
            . "#EXT-X-STREAM-INF:PROGRAM-ID=0,BANDWIDTH=1\n"
            . basename($_SERVER["SCRIPT_NAME"]) . '?chunklist=' . urlencode($best_chunklist);
    }
} else if (isset($_GET['chunklist'])) {
    $chunklist_url = urldecode($_GET['chunklist']);
    $chunklist = curl($chunklist_url);
    $chunklist = str_replace('#EXT-X-ALLOW-CACHE:NO', '#EXT-X-ALLOW-CACHE:YES', $chunklist);
    $chunklist = str_replace('media', dirname($chunklist_url).'/media', $chunklist);
    print $chunklist;
} else {
    ?>
    <ol type="1">
        <?php
        $videos = json_decode(curl("http://news.sportbox.ru/api2/rubricvideo?term_id=7212&app_id=android%2F3&page_size=100"), true);
        foreach ($videos['nodes'] as $video) {
            $id = $video['video']['streams'][1]['id'];
            $id_auto = $video['video']['streams'][2]['id'];
            ?>
            <li>
                <?=($video['media_state'] == 'live' ? '[LIVE] ' : '') ?><?= $video['title'] ?>
                <a href="?id=<?= $id ?>&video">Video</a> | <a href="?id=<?= $id_auto ?>&m3u">M3U</a></li>
        <?php } ?>
    </ol>
    <?php
}
if (!$M3U_MODE){ ?>
</body>
<?php
}
?>
