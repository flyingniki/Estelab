<?php

/**
 * Формируем отчет по команде
 *
 * @param      string $text строка, которую отправил юзер
 * @param      int $user идентификатор пользователя, который нам написал
 *
 * @return     array
 */
function addMessageOnStart($message, $keyboard = null)
{
    switch (mb_strtolower($message)) {
        case 'привет':
            $attach[] = array("MESSAGE" => 'Если хочешь узнать, что я могу, набери в сообщении или нажми [send=меню]меню[/send]');
            $arResult = array(
                'title' => '[b]Я чат-бот помощник, создан для удобства в работе на нашем портале. Пока что мой функционал ограничен, но я учусь :)[/b]',
                'attach' => $attach,
            );
            break;
        case 'меню':
            $arResult = array(
                'title' => '[b]Мои функции[/b]',
                'report' => 'Для вызова, нажми кнопку ниже',
                'keyboard' => showKeyboard($keyboard),
            );
            break;
        case 'заполняем отсутствие:':
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'title' => '[b]Всегда можно вернуться в начало[/b]',
                'attach' => $attach,
            );
            break;
        case 'вносим данные:':
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'title' => '[b]Всегда можно вернуться в начало[/b]',
                'attach' => $attach,
            );
            break;
        default:
            $arResult = array(
                'title' => '[b]Туплю-с[/b]',
                'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
            );
    }
    $answerParams = array(
        "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
        "MESSAGE" => $arResult['title'] . "\n" . $arResult['report'] . "\n",
        "KEYBOARD" => $arResult['keyboard'],
        "ATTACH" => array_merge(
            $arResult['attach']
        ),
    );
    $result = restCommand('imbot.message.add', $answerParams, $_REQUEST["auth"]);
    return $result;
}

/**
 * Вызывает клавиатуру
 * @param array массив с данными клавиатуры
 * 
 * @return array
 */
function showKeyboard($keyboard)
{
    return $keyboard;
}

function updateMessage($botId, $messageId, $messageText = '', $keyboard = null)
{
    $arFields = array(
        'BOT_ID' => $botId,
        "MESSAGE_ID" => $messageId,
        "MESSAGE" => $messageText,
        "KEYBOARD" => $keyboard,
    );
    $result = restCommand('imbot.message.update', $arFields, $_REQUEST["auth"]);
    return $result;
}

function deleteMessage($botId, $messageId)
{
    $result = restCommand('imbot.message.delete', array(
        'BOT_ID' => $botId,
        'MESSAGE_ID' => $messageId,
        'COMPLETE' => 'Y', //  If message is required to be deleted completely, without a trace, then specify 'Y' (optional parameter)
    ), $_REQUEST["auth"]);
    return $result;
}

/**
 * Добавляем элемент инфоблока отсутствие и переработка
 */
function absenceAndProcessing($case, $dateBegin, $dateEnd, $employee, $type, $department)
{
    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => 119,
        "ELEMENT_CODE" => 'element_' . time(),
        'FIELDS' => array(
            'NAME' => $case,
            'PROPERTY_608' => $dateBegin,
            'PROPERTY_609' => $dateEnd,
            'PROPERTY_853' => $employee,
            'PROPERTY_854' => $type,
            'PROPERTY_855' => $department
        ),
    );

    $result = restCommand('lists.element.add', $iBlockParams, $_REQUEST["auth"]);
    return $result['result'];
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

function entityItemAdd($entityCode, $entityName, $elemProperty, $elemPropValue)
{
    $result = restCommand('entity.item.add', array(
        "ENTITY" => $entityCode,
        'NAME' => $entityName,
        'PROPERTY_VALUES' => array(
            $elemProperty => $elemPropValue,
        ),
    ));

    return $result;
}

function entityItemGet($entityCode, $entityName)
{
    $result = restCommand('entity.item.get', array(
        'ENTITY' => $entityCode,
        'SORT' => array('ID' => 'DESC'),
        'FILTER' => array(
            // '=NAME' => $entityName
        ),
    ), $_REQUEST["auth"]);

    return $result;
}

function entityItemUpdate($entityCode, $elementId, $elemProperty, $elemPropValue)
{
    $result = restCommand('entity.item.update', array(
        "ENTITY" => $entityCode,
        "ID" => $elementId,
        "PROPERTY_VALUES" => array(
            $elemProperty => $elemPropValue,
        ),
    ), $_REQUEST["auth"]);

    return $result;
}

function getEntityItemProperty($entityCode, $entityProperty = '*')
{
    $result = restCommand('entity.item.property.get', array(
        'ENTITY' => $entityCode,
        'PROPERTY' => $entityProperty,
    ), $_REQUEST["auth"]);

    return $result;
}
