<?php

/**
 * Бот-помощник по порталу
 */

require_once __DIR__ . '/defaultFunctions.php';
require_once __DIR__ . '/myFunctions.php';
require_once __DIR__ . '/keyboards.php';

$appsConfig = array();
$configFileName = '/config_' . trim(str_replace('.', '_', $_REQUEST['auth']['domain'])) . '.php';
if (file_exists(__DIR__ . $configFileName)) {
    include_once __DIR__ . $configFileName;
}
// receive event "new message for bot" after sending message from user
if ($_REQUEST['event'] == 'ONIMBOTMESSAGEADD') {
    // check the event - register this application or not
    if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
        return false;
    }
    // writeToLog($_REQUEST, '$_REQUEST');
    // writeToLog($_REQUEST['data']['PARAMS']['DIALOG_ID'], $title = 'DIALOG_ID');
    addMessage($_REQUEST['data']['PARAMS']['MESSAGE'], $keyboardMain);
} elseif ($_REQUEST['event'] == 'ONIMCOMMANDADD') {
    // check the event - authorize this event or not
    if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
        return false;

    $result = false;
    // in case of some command
    foreach ($_REQUEST['data']['COMMAND'] as $command) {
        if ($command['COMMAND'] == 'absence') {
            $result = restCommand('imbot.command.answer', array(
                "COMMAND_ID" => $command['COMMAND_ID'],
                "MESSAGE_ID" => $command['MESSAGE_ID'],
                "MESSAGE" => "[b]Тебе следует внести следующую информацию:[/b]\n Причина \n Дата начала \n Дата окончания \n Сотрудник \n Тип \n Подразделение
                \n Как будешь готов, [send=absenceClick]кликай![/send]",
            ), $_REQUEST["auth"]);
        } elseif ($command['COMMAND'] == 'billPayment') {
            $result = restCommand('imbot.command.answer', array(
                "COMMAND_ID" => $command['COMMAND_ID'],
                "MESSAGE_ID" => $command['MESSAGE_ID'],
                "MESSAGE" => "Оплата счета",
            ), $_REQUEST["auth"]);
        }

        // write debug log
        writeToLog($_REQUEST['data']['COMMAND'], 'AssistantBot command add');
    }
}
// receive event "open private dialog with bot" or "join bot to group chat"
else {
    if ($_REQUEST['event'] == 'ONIMBOTJOINCHAT') {
        require_once __DIR__ . '/joinChat.php';
    } // receive event "delete chat-bot"
    else {
        if ($_REQUEST['event'] == 'ONIMBOTDELETE') {
            require_once __DIR__ . '/botDelete.php';
        } // receive event "Application install"
        else {
            if ($_REQUEST['event'] == 'ONAPPINSTALL') {
                require_once __DIR__ . '/appInstall.php';
            }
        }
    }
}
