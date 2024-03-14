<?php

// تنظیمات
$voiceChannelId = 'YOUR_VOICE_CHANNEL_ID'; // شناسه کانال صوتی در گروه
$token = '7094896891:AAFDdQUcOLlUwpSSPs9yp2wndS07t4nUzlQ'; // توکن ربات شما در تلگرام
$googleApiKey = 'YOUR_GOOGLE_API_KEY'; // کلید API یوتیوب شما

// دریافت دیتا از تلگرام
$update = json_decode(file_get_contents('php://input'), true);
$message = $update['message'];
$text = $message['text'];

// بررسی درخواست پخش موسیقی
if ($text == 'پخش آهنگ') {
    // ارسال درخواست به تلگرام برای شروع ضبط ویس
    sendVoiceRecordingRequest($message['chat']['id'], $voiceChannelId, $token);
} else if ($message['voice']) {
    // دریافت فایل صوتی
    $voice = $message['voice'];
    $fileId = $voice['file_id'];
    
    // دریافت اطلاعات فایل صوتی
    $fileInfo = getVoiceFileInformation($fileId, $token);
    $fileUrl = $fileInfo['file_path'];
    
    // پخش موسیقی از طریق YouTube
    playMusic($fileUrl, $googleApiKey);
}

// تابع ارسال درخواست به تلگرام برای شروع ضبط ویس
function sendVoiceRecordingRequest($chatId, $voiceChannelId, $token) {
    $url = "https://api.telegram.org/bot$token/sendVoice";
    $data = array(
        'chat_id' => $chatId,
        'voice_chat_id' => $voiceChannelId
    );
    file_get_contents($url . '?' . http_build_query($data));
}

// تابع دریافت اطلاعات فایل صوتی
function getVoiceFileInformation($fileId, $token) {
    $url = "https://api.telegram.org/bot$token/getFile";
    $data = array(
        'file_id' => $fileId
    );
    $response = file_get_contents($url . '?' . http_build_query($data));
    return json_decode($response, true)['result'];
}

// تابع پخش موسیقی از طریق YouTube
function playMusic($fileUrl, $googleApiKey) {
    $youtubeUrl = "https://www.googleapis.com/youtube/v3/search";
    $data = array(
        'part' => 'snippet',
        'q' => 'music',
        'type' => 'video',
        'key' => $googleApiKey,
        'maxResults' => 1
    );
    $response = file_get_contents($youtubeUrl . '?' . http_build_query($data));
    $videoId = json_decode($response, true)['items'][0]['id']['videoId'];
    
    // ارسال درخواست پخش به تلگرام
    sendMusicPlaybackRequest($videoId, $fileUrl);
}

// تابع ارسال درخواست پخش به تلگرام
function sendMusicPlaybackRequest($videoId, $fileUrl) {
    $url = "https://api.telegram.org/bot$token/sendAudio";
    $data = array(
        'chat_id' => $chatId,
        'audio' => $fileUrl,
        'caption' => 'Now playing: ' . $videoId
    );
    file_get_contents($url . '?' . http_build_query($data));
}
