<?php

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

