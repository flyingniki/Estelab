<?php

/**
 * Бот-помощник по порталу
 */

require_once 'defaultFunctions.php';
require_once 'myFunctions.php';

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
} // receive event "new command for bot"
elseif ($_REQUEST['event'] == 'ONIMCOMMANDADD') {
    // check the event - authorize this event or not
    if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
        return false;

    // response time
    $latency = (time() - $_REQUEST['ts']);
    $latency = $latency > 60 ? (round($latency / 60)) . 'm' : $latency . "s";

    $result = false;
    writeToLog($_REQUEST['data']['COMMAND'], 'Commands');
    foreach ($_REQUEST['data']['COMMAND'] as $command) {
        if ($command['COMMAND'] == 'echo') {
            $result = restCommand('imbot.command.answer', array(
                "COMMAND_ID" => $command['COMMAND_ID'],
                "MESSAGE_ID" => $command['MESSAGE_ID'],
                "MESSAGE" => "Answer command",
                "ATTACH" => array(
                    array("MESSAGE" => "reply: /" . $command['COMMAND'] . ' ' . $command['COMMAND_PARAMS']),
                    array("MESSAGE" => "latency: " . $latency),
                )
            ), $_REQUEST["auth"]);
        } else if ($command['COMMAND'] == 'echoList') {
            $initList = false;
            if (!$command['COMMAND_PARAMS']) {
                $initList = true;
                $command['COMMAND_PARAMS'] = 1;
            }

            $attach = array();
            if ($command['COMMAND_PARAMS'] == 1) {
                $attach[] = array("GRID" => array(
                    array("VALUE" => "RED", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#df532d", "COLOR" => "#df532d", "DISPLAY" => "LINE"),
                ));
                $attach[] = array("GRID" => array(
                    array("VALUE" => "GRAPHITE", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#3a403e", "COLOR" => "#3a403e", "DISPLAY" => "LINE"),
                ));
            } else if ($command['COMMAND_PARAMS'] == 2) {
                $attach[] = array("GRID" => array(
                    array("VALUE" => "MINT", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#4ba984", "COLOR" => "#4ba984", "DISPLAY" => "LINE"),
                ));
                $attach[] = array("GRID" => array(
                    array("VALUE" => "LIGHT BLUE", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#6fc8e5", "COLOR" => "#6fc8e5", "DISPLAY" => "LINE"),
                ));
            } else if ($command['COMMAND_PARAMS'] == 3) {
                $attach[] = array("GRID" => array(
                    array("VALUE" => "PURPLE", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#8474c8", "COLOR" => "#8474c8", "DISPLAY" => "LINE"),
                ));
                $attach[] = array("GRID" => array(
                    array("VALUE" => "AQUA", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#1eb4aa", "COLOR" => "#1eb4aa", "DISPLAY" => "LINE"),
                ));
            } else if ($command['COMMAND_PARAMS'] == 4) {
                $attach[] = array("GRID" => array(
                    array("VALUE" => "PINK", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#e98fa6", "COLOR" => "#e98fa6", "DISPLAY" => "LINE"),
                ));
                $attach[] = array("GRID" => array(
                    array("VALUE" => "LIME", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#85cb7b", "COLOR" => "#85cb7b", "DISPLAY" => "LINE"),
                ));
            } else if ($command['COMMAND_PARAMS'] == 5) {
                $attach[] = array("GRID" => array(
                    array("VALUE" => "AZURE", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#29619b", "COLOR" => "#29619b", "DISPLAY" => "LINE"),
                ));
                $attach[] = array("GRID" => array(
                    array("VALUE" => "ORANGE", "DISPLAY" => "LINE", "WIDTH" => 100),
                    array("VALUE" => "#e8a441", "COLOR" => "#e8a441", "DISPLAY" => "LINE"),
                ));
            }
            $keyboard = array(
                array("TEXT" => $command['COMMAND_PARAMS'] == 1 ? "· 1 ·" : "1", "COMMAND" => "echoList", "COMMAND_PARAMS" => "1", "DISPLAY" => "LINE", "BLOCK" => "Y"),
                array("TEXT" => $command['COMMAND_PARAMS'] == 2 ? "· 2 ·" : "2", "COMMAND" => "echoList", "COMMAND_PARAMS" => "2", "DISPLAY" => "LINE", "BLOCK" => "Y"),
                array("TEXT" => $command['COMMAND_PARAMS'] == 3 ? "· 3 ·" : "3", "COMMAND" => "echoList", "COMMAND_PARAMS" => "3", "DISPLAY" => "LINE", "BLOCK" => "Y"),
                array("TEXT" => $command['COMMAND_PARAMS'] == 4 ? "· 4 ·" : "4", "COMMAND" => "echoList", "COMMAND_PARAMS" => "4", "DISPLAY" => "LINE", "BLOCK" => "Y"),
                array("TEXT" => $command['COMMAND_PARAMS'] == 5 ? "· 5 ·" : "5", "COMMAND" => "echoList", "COMMAND_PARAMS" => "5", "DISPLAY" => "LINE", "BLOCK" => "Y"),
            );

            if (!$initList && $command['COMMAND_CONTEXT'] == 'KEYBOARD') {
                $result = restCommand('imbot.message.update', array(
                    "BOT_ID" => $command['BOT_ID'],
                    "MESSAGE_ID" => $command['MESSAGE_ID'],
                    "ATTACH" => $attach,
                    "KEYBOARD" => $keyboard
                ), $_REQUEST["auth"]);
            } else {
                $result = restCommand('imbot.command.answer', array(
                    "COMMAND_ID" => $command['COMMAND_ID'],
                    "MESSAGE_ID" => $command['MESSAGE_ID'],
                    "MESSAGE" => "List of colors",
                    "ATTACH" => $attach,
                    "KEYBOARD" => $keyboard
                ), $_REQUEST["auth"]);
            }
        } else if ($command['COMMAND'] == 'help') {
            $keyboard = array(
                array(
                    "TEXT" => "Bitrix24",
                    'LINK' => "http://bitrix24.com",
                    "BG_COLOR" => "#29619b",
                    "TEXT_COLOR" => "#fff",
                    "DISPLAY" => "LINE",
                ),
                array(
                    "TEXT" => "BitBucket",
                    "LINK" => "https://bitbucket.org/Bitrix24com/rest-bot-echotest",
                    "BG_COLOR" => "#2a4c7c",
                    "TEXT_COLOR" => "#fff",
                    "DISPLAY" => "LINE",
                ),
                array("TYPE" => "NEWLINE"),
                array("TEXT" => "Echo", "COMMAND" => "echo", "COMMAND_PARAMS" => "test from keyboard", "DISPLAY" => "LINE"),
                array("TEXT" => "List", "COMMAND" => "echoList", "DISPLAY" => "LINE"),
                array("TEXT" => "Help", "COMMAND" => "help", "DISPLAY" => "LINE"),
            );

            $result = restCommand('imbot.command.answer', array(
                "COMMAND_ID" => $command['COMMAND_ID'],
                "MESSAGE_ID" => $command['MESSAGE_ID'],
                "MESSAGE" => "Hello! My name is AssistantBot :)[br] I designed to help you here!",
                "KEYBOARD" => $keyboard
            ), $_REQUEST["auth"]);
        }
    }

    // write debug log
    // writeToLog($result, 'Assistant Event message add');
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
                array('MESSAGE' => 'Спросите меня что-нибудь...'),
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
                    'COMMAND' => 'echo',
                    'COMMON' => 'Y',
                    'HIDDEN' => 'N',
                    'EXTRANET_SUPPORT' => 'N',
                    'LANG' => array(
                        array('LANGUAGE_ID' => 'en', 'TITLE' => 'Get echo message', 'PARAMS' => 'some text'),
                    ),
                    'EVENT_COMMAND_ADD' => $handlerBackUrl,
                ), $_REQUEST["auth"]);

                $commandEcho = $result['result'];

                $result = restCommand('imbot.command.register', array(
                    'BOT_ID' => $botId,
                    'COMMAND' => 'echoList',
                    'COMMON' => 'N',
                    'HIDDEN' => 'N',
                    'EXTRANET_SUPPORT' => 'N',
                    'LANG' => array(
                        array('LANGUAGE_ID' => 'en', 'TITLE' => 'Get list of colors', 'PARAMS' => ''),
                    ),
                    'EVENT_COMMAND_ADD' => $handlerBackUrl,
                ), $_REQUEST["auth"]);

                $commandList = $result['result'];

                $result = restCommand('imbot.command.register', array(
                    'BOT_ID' => $botId,
                    'COMMAND' => 'help',
                    'COMMON' => 'N',
                    'HIDDEN' => 'N',
                    'EXTRANET_SUPPORT' => 'N',
                    'LANG' => array(
                        array('LANGUAGE_ID' => 'en', 'TITLE' => 'Get help message', 'PARAMS' => 'some text'),
                    ),
                    'EVENT_COMMAND_ADD' => $handlerBackUrl,
                ), $_REQUEST["auth"]);

                $commandHelp = $result['result'];

                $result = restCommand('event.bind', array(
                    'EVENT' => 'OnAppUpdate',
                    'HANDLER' => $handlerBackUrl
                ), $_REQUEST["auth"]);

                // save params
                $appsConfig[$_REQUEST['auth']['application_token']] = array(
                    'BOT_ID' => $botId,
                    'COMMAND_ECHO' => $commandEcho,
                    'COMMAND_HELP' => $commandHelp,
                    'COMMAND_LIST' => $commandList,
                    'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
                    'AUTH' => $_REQUEST['auth'],
                );
                saveParams($appsConfig);

                // write debug log
                writeToLog(array($botId, $commandEcho, $commandHelp, $commandList), 'Assistant register');
            }
        }
    }
}
