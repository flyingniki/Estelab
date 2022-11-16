<?php

/**
 * Бот-помощник по порталу
 */

require_once 'defaultFunctions.php';
require_once 'myFunctions.php';
require_once 'keyboard.php';

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
    addMessage($_REQUEST['data']['PARAMS']['MESSAGE'], $keyboard);
}
if ($_REQUEST['event'] == 'ONIMCOMMANDADD') {
    // check the event - authorize this event or not
    if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
        return false;

    $result = false;

    foreach ($_REQUEST['data']['COMMAND'] as $command) {
        if ($command['COMMAND'] == 'absence') {
            $result = restCommand('imbot.command.answer', array(
                "COMMAND_ID" => $command['COMMAND_ID'],
                "MESSAGE_ID" => $command['MESSAGE_ID'],
                "MESSAGE" => "Отсутствие и переработка",
            ), $_REQUEST["auth"]);
        }
    }

    // write debug log
    writeToLog($_REQUEST['data']['COMMAND'], 'AssistantBot command add');
}
// receive event "open private dialog with bot" or "join bot to group chat"
else {
    if ($_REQUEST['event'] == 'ONIMBOTJOINCHAT') {
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
    } // receive event "delete chat-bot"
    else {
        if ($_REQUEST['event'] == 'ONIMBOTDELETE') {
            // check the event - register this application or not
            if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
                return false;
            }
            // unset application variables
            unset($appsConfig[$_REQUEST['auth']['application_token']]);
            // save params
            saveParams($appsConfig);
        } // receive event "Application install"
        else {
            if ($_REQUEST['event'] == 'ONAPPINSTALL') {
                // handler for events
                $handlerBackUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array(
                    $_SERVER['SERVER_PORT'],
                    array(80, 443)
                ) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];
                // If your application supports different localizations
                // use $_REQUEST['data']['LANGUAGE_ID'] to load correct localization
                // register new bot
                $result = restCommand('imbot.register', array(
                    'CODE'                  => 'AssistantBot',
                    // строковой идентификатор бота, уникальный в рамках вашего приложения (обяз.)
                    'TYPE'                  => 'B',
                    // Тип бота, B - бот, ответы  поступают сразу, H - человек, ответы поступаю с задержкой от 2х до 10 секунд
                    'EVENT_MESSAGE_ADD'     => $handlerBackUrl,
                    // Ссылка на обработчик события отправки сообщения боту (обяз.)
                    'EVENT_WELCOME_MESSAGE' => $handlerBackUrl,
                    // Ссылка на обработчик события открытия диалога с ботом или приглашения его в групповой чат (обяз.)
                    'EVENT_BOT_DELETE'      => $handlerBackUrl,
                    // Ссылка на обработчик события удаление бота со стороны клиента (обяз.)
                    'PROPERTIES'            => array( // Личные данные чат-бота (обяз.)
                        'NAME'              => 'Бот-помощник',
                        // Имя бота (обязательное одно из полей NAME или LAST_NAME)
                        'LAST_NAME'         => '',
                        // Фамилия бота (обязательное одно из полей NAME или LAST_NAME)
                        'COLOR'             => 'AZURE',
                        // Цвет бота для мобильного приложения RED,  GREEN, MINT, LIGHT_BLUE, DARK_BLUE, PURPLE, AQUA, PINK, LIME, BROWN,  AZURE, KHAKI, SAND, MARENGO, GRAY, GRAPHITE
                        'EMAIL'             => '',
                        // Емейл для связи
                        'PERSONAL_BIRTHDAY' => '2022-11-11',
                        // День рождения в формате YYYY-mm-dd
                        'WORK_POSITION'     => 'Помогаю во многих вопросах',
                        // Занимаемая должность, используется как описание бота
                        'PERSONAL_WWW'      => '',
                        // Ссылка на сайт
                        'PERSONAL_GENDER'   => 'M',
                        // Пол бота, допустимые значения M -  мужской, F - женский, пусто если не требуется указывать
                        'PERSONAL_PHOTO'    => '',
                        // Аватар бота - base64
                    ),
                ), $_REQUEST["auth"]);

                $botId = $result['result'];

                $result = restCommand('imbot.command.register', array(
                    'BOT_ID' => $botId,
                    'COMMAND' => 'absence',
                    'COMMON' => 'Y',
                    'HIDDEN' => 'N',
                    'EXTRANET_SUPPORT' => 'N',
                    'LANG' => array(
                        array('LANGUAGE_ID' => 'en', 'TITLE' => 'Процесс "Отсутствие и переработка"', 'PARAMS' => 'some text'),
                    ),
                    'EVENT_COMMAND_ADD' => $handlerBackUrl,
                ), $_REQUEST["auth"]);

                $commandAbsenceId = $result['result'];

                $result = restCommand('imbot.command.register', array(
                    'BOT_ID' => $botId,
                    'COMMAND' => 'billPayment',
                    'COMMON' => 'Y',
                    'HIDDEN' => 'N',
                    'EXTRANET_SUPPORT' => 'N',
                    'LANG' => array(
                        array('LANGUAGE_ID' => 'en', 'TITLE' => 'Оплата счета', 'PARAMS' => 'some text'),
                    ),
                    'EVENT_COMMAND_ADD' => $handlerBackUrl,
                ), $_REQUEST["auth"]);

                $commandBillPaymentId = $result['result'];

                // save params
                $appsConfig[$_REQUEST['auth']['application_token']] = array(
                    'BOT_ID' => $botId,
                    'COMMAND_ABSENCE' => $commandAbsenceId,
                    'COMMAND_BILL_PAYMENT' => $commandBillPaymentId,
                    'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
                    'AUTH' => $_REQUEST['auth'],
                );
                saveParams($appsConfig);

                // write debug log
                writeToLog(array($botId, $commandAbsenceId, $commandBillPaymentId), 'Assistant register');
            }
        }
    }
}
