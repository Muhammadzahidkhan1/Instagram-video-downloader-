<?php
// --- Backend Logic ---
$videoUrl = $thumbUrl = $errorMsg = "";

if (isset($_POST['url']) && !empty($_POST['url'])) {
    $inputUrl = $_POST['url'];
    $encodedUrl = urlencode($inputUrl);
    $targetUrl = "https://snapdownloader.com/tools/instagram-reels-downloader/download?url={$encodedUrl}";

    $ch = curl_init($targetUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response) {
        // Extract Video
        preg_match('/<a[^>]+href="([^"]+\.mp4[^"]*)"[^>]*>/', $response, $videoMatch);
        $videoUrl = html_entity_decode($videoMatch[1] ?? '');

        // Extract Thumbnail
        preg_match('/<a[^>]+href="([^"]+\.jpg[^"]*)"[^>]*>/', $response, $thumbMatch);
        $thumbUrl = html_entity_decode($thumbMatch[1] ?? '');

        if (!$videoUrl) {
            $errorMsg = "Video link nahi mil saka. Shayad account private hai.";
        }
    } else {
        $errorMsg = "Connection failed! Server response nahi de raha.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insta Pro Saver</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: white; font-family: sans-serif; }
        .insta-gradient { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="bg-zinc-900 w-full max-w-md p-6 rounded-3xl border border-zinc-800 shadow-2xl">
        <div class="text-center mb-6">
            <div class="insta-gradient w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fab fa-instagram text-4xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold italic">REEL SAVER</h1>
            <p class="text-zinc-400 text-sm mt-1">High Quality Video Downloader</p>
        </div>

        <form method="POST" action="" class="space-y-4">
            <input type="url" name="url" required placeholder="Paste Instagram Link Here..." 
                   class="w-full bg-zinc-800 border border-zinc-700 px-4 py-4 rounded-xl focus:outline-none focus:border-pink-500 transition-all text-white">
            
            <button type="submit" class="insta-gradient w-full py-4 rounded-xl font-bold text-lg active:scale-95 transition-transform shadow-lg">
                <i class="fas fa-bolt mr-2"></i> GET VIDEO
            </button>
        </form>

        <?php if ($errorMsg): ?>
            <div class="mt-4 p-3 bg-red-900/30 border border-red-800 text-red-400 rounded-lg text-sm text-center">
                <?= $errorMsg ?>
            </div>
        <?php endif; ?>

        <?php if ($videoUrl): ?>
            <div class="mt-8 animate-in fade-in duration-500">
                <div class="relative rounded-2xl overflow-hidden mb-4 aspect-square bg-zinc-800">
                    <img src="<?= $thumbUrl ?>" class="w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <a href="<?= $videoUrl ?>" target="_blank" class="bg-white text-black h-14 w-14 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
                            <i class="fas fa-play text-xl ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <a href="<?= $videoUrl ?>" download="instagram_video.mp4" 
                   class="block w-full text-center bg-white text-black font-black py-4 rounded-xl hover:bg-zinc-200 transition-colors">
                    <i class="fas fa-download mr-2"></i> DOWNLOAD MP4
                </a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
