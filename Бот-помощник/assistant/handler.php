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
    // response time
    $arReport = getAnswer($_REQUEST['data']['PARAMS']['MESSAGE'], $_REQUEST['data']['PARAMS']['FROM_USER_ID']);
    $arReport['attach'][] = array("MESSAGE" => 'Как будете готовы, спросите меня снова!');
    // $arReport['attach'][] = array("MESSAGE" => 'Если хочешь узнать, что я могу, набери в сообщении "помощь"');

    // writeToLog($_REQUEST, '$_REQUEST');
    // writeToLog($arReport, $title = '$arReport');
    // writeToLog($_REQUEST['data']['PARAMS']['DIALOG_ID'], $title = 'DIALOG_ID');

    // send answer message from bot
    $result = restCommand(
        'imbot.message.add',
        array(
            "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
            "MESSAGE"   => $arReport['title'] . "\n" . $arReport['report'] . "\n",
            "ATTACH"    => array_merge(
                $arReport['attach']
            ),
        ),
        $_REQUEST["auth"]
    );
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

                // save params
                $appsConfig[$_REQUEST['auth']['application_token']] = array(
                    'BOT_ID' => $botId,
                    'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
                    'AUTH' => $_REQUEST['auth'],
                );
                saveParams($appsConfig);

                // write debug log
                writeToLog(array($botId, $commandEchoId, $commandHelpId, $commandListId), 'Assistant register');
            }
        }
    }
}
