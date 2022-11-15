<?php

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
    writeToLog(array('URL' => $queryUrl, 'PARAMS' => array_merge($params, array("auth" => $auth["access_token"]))), 'ReportBot send data');
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
    file_put_contents(__DIR__ . '/assistant.log', $log, FILE_APPEND);
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