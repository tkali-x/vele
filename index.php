<?php

// Include the Telegram Bot API library
require 'path/to/telegram-bot-api.php';

// Set the bot token
$botToken = 'YOUR_BOT_TOKEN';

// Create a new bot instance
$bot = new TelegramBotAPI($botToken);

// Get the chat ID
$chatID = $bot->getChatID();

// Check if it's the first time the user is starting the bot
if (firstTimeUser($chatID)) {
    // Send a welcome message to the user
    $bot->sendMessage($chatID, 'سلام چه کمکی از دستم بر می‌آد؟ :)');
}

// Handle the commands
if (isset($_GET['command'])) {
    $command = $_GET['command'];

    if ($command == 'چخش') {
        // Play the song from the beginning
        playSong();
    } elseif ($command == 'ایست') {
        // Stop the song
        stopSong();
    } elseif ($command == '10 ثانیه جلو') {
        // Skip 10 seconds forward in the song
        skipSong(10);
    }
}

// Function to check if it's the first time the user is starting the bot
function firstTimeUser($chatID)
{
    // Replace with your database credentials
    $servername = 'YOUR_DB_SERVERNAME';
    $username = 'YOUR_DB_USERNAME';
    $password = 'YOUR_DB_PASSWORD';
    $dbname = 'YOUR_DB_NAME';

    // Create a new MySQLi instance
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // Check if connection error
    if ($mysqli->connect_error) {
        die('Connection failed: ' . $mysqli->connect_error);
    }

    // Prepare the query
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE chat_id = ?');

    // Bind the chat ID parameter
    $stmt->bind_param('s', $chatID);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 0) {
        // User is starting the bot for the first time
        // Insert the user's chat ID into the database
        $stmt = $mysqli->prepare('INSERT INTO users (chat_id) VALUES (?)');
        $stmt->bind_param('s', $chatID);
        $stmt->execute();

        // Close the statement
        $stmt->close();

        return true;
    } else {
        // User has used the bot before

        // Close the statement
        $stmt->close();

        return false;
    }
}

// Function to play the song from the beginning
function playSong()
{
    // Implement your own logic to play the song from the beginning
    // Use the audio player library or any other method to start playing the song
}

// Function to stop the song
function stopSong()
{
    // Implement your own logic to stop the song
    // Use the audio player library or any other method to stop the currently playing song
}

// Function to skip forward in the song
function skipSong($seconds)
{
    // Implement your own logic to skip forward in the song
    // Use the audio player library or any other method to skip the specified number of seconds in the currently playing song
}

?>
