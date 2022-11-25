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
    // writeToLog(array('URL' => $queryUrl, 'PARAMS' => array_merge($params, array("auth" => $auth["access_token"]))), 'AssistantBot send data');
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

function registerCommand($botId, $handlerBackUrl, $commandName, $title, $params)
{
    $result = restCommand('imbot.command.register', array(
        'BOT_ID' => $botId,
        'COMMAND' => $commandName,
        'COMMON' => 'Y',
        'HIDDEN' => 'N',
        'EXTRANET_SUPPORT' => 'N',
        'LANG' => array(
            array('LANGUAGE_ID' => 'en', 'TITLE' => $title, 'PARAMS' => $params),
        ),
        'EVENT_COMMAND_ADD' => $handlerBackUrl,
    ), $_REQUEST["auth"]);

    return $result['result'];
}

function addEntityItem($entityCode, $itemName)
{
    $result = restCommand('entity.item.add', array(
        "ENTITY" => $entityCode,
        'NAME' => $itemName,
    ), $_REQUEST["auth"]);

    return $result;
}

function updateEntityItem($entityCode, $itemId, $itemProperty, $itemPropValue)
{
    $result = restCommand('entity.item.update', array(
        "ENTITY" => $entityCode,
        "ID" => $itemId,
        "PROPERTY_VALUES" => array(
            $itemProperty => $itemPropValue,
        ),
    ), $_REQUEST["auth"]);

    return $result;
}

function getEntityItems($entityCode)
{
    $result = restCommand('entity.item.get', array(
        'ENTITY' => $entityCode,
        'SORT' => array('ID' => 'DESC'),
        'FILTER' => array(),
    ), $_REQUEST["auth"]);

    return $result;
}

function deleteEntityItem($entityCode, $userId)
{
    $arItemsInfo = getEntityItems($entityCode);
    foreach ($arItemsInfo['result'] as $itemInfo) {
        if ($userId == $itemInfo['CREATED_BY']) {
            restCommand('entity.item.delete', array(
                "ENTITY" => $itemInfo['ENTITY'],
                'ID' => $itemInfo['ID'],
            ), $_REQUEST["auth"]);
        }
    }
}

function getEntityItemProperties($entityCode, $entityProperty = '*')
{
    $result = restCommand('entity.item.property.get', array(
        'ENTITY' => $entityCode,
        'PROPERTY' => $entityProperty,
    ), $_REQUEST["auth"]);

    return $result;
}
