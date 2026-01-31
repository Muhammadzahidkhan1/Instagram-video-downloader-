<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Frontend se connect karne ke liye zaroori

if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing Instagram URL']);
    exit;
}

$inputUrl = $_GET['url'];
$encodedUrl = urlencode($inputUrl);
$targetUrl = "https://snapdownloader.com/tools/instagram-reels-downloader/download?url={$encodedUrl}";

$ch = curl_init($targetUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error || !$response) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch data']);
    exit;
}

preg_match('/<a[^>]+href="([^"]+\.mp4[^"]*)"[^>]*>/', $response, $videoMatch);
$videoUrl = html_entity_decode($videoMatch[1] ?? '');

preg_match('/<a[^>]+href="([^"]+\.jpg[^"]*)"[^>]*>/', $response, $thumbMatch);
$thumbUrl = html_entity_decode($thumbMatch[1] ?? '');

if ($videoUrl) {
    echo json_encode([
        'status' => 'success',
        'video' => $videoUrl,
        'thumbnail' => $thumbUrl
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Unable to extract video']);
}
?>
