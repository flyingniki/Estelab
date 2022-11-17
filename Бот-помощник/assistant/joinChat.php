<?php

// check the event - register this application or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
    return false;
}
// send help message how to use chat-bot. For private chat and for group chat need send different instructions.
$result = restCommand('imbot.message.add', array(
    'DIALOG_ID' => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
    'MESSAGE'   => 'Привет! Я проводник по этому порталу! 8-) Поехали :)',
    "ATTACH"    => array(
        array('MESSAGE' => 'Если хочешь начать, напиши "привет"'),
    ),
), $_REQUEST["auth"]);
