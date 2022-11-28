<?php

/**
 * Бот-помощник по порталу
 */

require_once __DIR__ . '/functions/defaultFunctions.php';
require_once __DIR__ . '/functions/myFunctions.php';
require_once __DIR__ . '/lib/keyboards.php';

$entityCode = 'bot_assistant';
$entityName = 'Assistant Bot';

$appsConfig = array();
$configFileName = '/config_' . trim(str_replace('.', '_', $_REQUEST['auth']['domain'])) . '.php';
if (file_exists(__DIR__ . $configFileName)) {
    include_once __DIR__ . $configFileName;
}
// receive event "new message for bot" after sending message from user
if ($_REQUEST['event'] == 'ONIMBOTMESSAGEADD') {
    require_once __DIR__ . '/getMessage.php';
} elseif ($_REQUEST['event'] == 'ONIMCOMMANDADD') {
    require_once __DIR__ . '/getCommand.php';
}
// receive event "open private dialog with bot" or "join bot to group chat"
else {
    if ($_REQUEST['event'] == 'ONIMBOTJOINCHAT') {
        require_once __DIR__ . '/lib/joinChat.php';
    } // receive event "delete chat-bot"
    else {
        if ($_REQUEST['event'] == 'ONIMBOTDELETE') {
            require_once __DIR__ . '/lib/botDelete.php';
        } // receive event "Application install"
        else {
            if ($_REQUEST['event'] == 'ONAPPINSTALL') {
                require_once __DIR__ . '/lib/appInstall.php';
            }
        }
    }
}
