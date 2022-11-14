<?php

/**
 * Бот-помощник по порталу
 */

$appsConfig = array();
$configFileName = '/config_' . trim(str_replace('.', '_', $_REQUEST['auth']['domain'])) . '.php';
if (file_exists(__DIR__ . $configFileName)) {
    include_once __DIR__ . $configFileName;
}
// receive event "new message for bot"
if ($_REQUEST['event'] == 'ONIMBOTMESSAGEADD') {
    // check the event - register this application or not
    if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
        return false;
    }
    // response time
    $arReport = getAnswer($_REQUEST['data']['PARAMS']['MESSAGE'], $_REQUEST['data']['PARAMS']['FROM_USER_ID']);
    $arReport['attach'][] = array("MESSAGE" => 'Как разберетесь с этими задачами, просто спросите еще раз [send=что горит]Что горит?[/send] и я дам новую сводку!');

    // send answer message
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
} // receive event "open private dialog with bot" or "join bot to group chat"
else {
    if ($_REQUEST['event'] == 'ONIMBOTJOINCHAT') {
        // check the event - register this application or not
        if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
            return false;
        }
        // send help message how to use chat-bot. For private chat and for group chat need send different instructions.
        $result = restCommand('imbot.message.add', array(
            'DIALOG_ID' => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
            'MESSAGE'   => 'Привет! Я Докладун, докладываю все как есть.',
            "ATTACH"    => array(
                array('MESSAGE' => '[send=что горит]Что горит?[/send]'),
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
                // save params
                $appsConfig[$_REQUEST['auth']['application_token']] = array(
                    'BOT_ID'      => $result['result'],
                    'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
                );
                saveParams($appsConfig);
                // write debug log
                // writeToLog($result, 'ReportBot register');
            }
        }
    }
}
/**
 * Save application configuration.
 *
 * @param $params
 *
 * @return bool
 */
function saveParams($params)
{
    $config = "<?php\n";
    $config .= "\$appsConfig = " . var_export($params, true) . ";\n";
    $config .= "?>";
    $configFileName = '/config_' . trim(str_replace('.', '_', $_REQUEST['auth']['domain'])) . '.php';
    file_put_contents(__DIR__ . $configFileName, $config);
    return true;
}
/**
 * Send rest query to Bitrix24.
 *
 * @param       $method - Rest method, ex: methods
 * @param array $params - Method params, ex: array()
 * @param array $auth   - Authorize data, ex: array('domain' => 'https://test.bitrix24.com', 'access_token' => '7inpwszbuu8vnwr5jmabqa467rqur7u6')
 *
 * @return mixed
 */
function restCommand($method, array $params = array(), array $auth = array())
{
    $queryUrl  = 'https://' . $auth['domain'] . '/rest/' . $method;
    $queryData = http_build_query(array_merge($params, array('auth' => $auth['access_token'])));
    // writeToLog(array('URL' => $queryUrl, 'PARAMS' => array_merge($params, array("auth" => $auth["access_token"]))), 'ReportBot send data');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_POST           => 1,
        CURLOPT_HEADER         => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL            => $queryUrl,
        CURLOPT_POSTFIELDS     => $queryData,
    ));
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result, 1);
    return $result;
}
/**
 * Write data to log file.
 *
 * @param mixed  $data
 * @param string $title
 *
 * @return bool
 */
function writeToLog($data, $title = '')
{
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents(__DIR__ . '/imbot.log', $log, FILE_APPEND);
    return true;
}
/**
 * Формируем отчет по команде
 *
 * @param      string $text строка, которую отправил юзер
 * @param      int $user идентификатор пользователя, который нам написал
 *
 * @return     array
 */
function getAnswer($command = '', $user)
{

    switch (strtolower($command)) {
        case 'что горит':
            $arResult = b24BadTasks($user);
            break;
        default:
            $arResult = array(
                'title' => 'Туплю-с',
                'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
            );
    }

    return $arResult;
}

function b24BadTasks($user)
{
    $tasks = restCommand(
        'task.item.list',
        array(
            'ORDER' => array('DEADLINE' => 'desc'),
            'FILTER' => array('RESPONSIBLE_ID' => $user, '<DEADLINE' => '2016-03-23'),
            'PARAMS' => array(),
            'SELECT' => array()
        ),
        $_REQUEST["auth"]
    );

    if (count($tasks['result']) > 0) {
        $arTasks = array();
        foreach ($tasks['result'] as $id => $arTask) {
            $arTasks[] = array(
                'LINK' => array(
                    'NAME' => $arTask['TITLE'],
                    'LINK' => 'https://' . $_REQUEST['auth']['domain'] . '/company/personal/user/' . $arTask['RESPONSIBLE_ID'] . '/tasks/task/view/' . $arTask['ID'] . '/'
                )
            );
            $arTasks[] = array(
                'DELIMITER' => array(
                    'SIZE' => 400,
                    'COLOR' => '#c6c6c6'
                )
            );
        }
        $arReport = array(
            'title' => 'Да, кое-какие задачи уже пролетели, например:',
            'report'  => '',
            'attach' => $arTasks
        );
    } else {
        $arReport = array(
            'title' => 'Шикарно работаете!',
            'report'  => 'Нечем даже огорчить - ни одной просроченной задачи',
        );
    }
    return $arReport;
}
