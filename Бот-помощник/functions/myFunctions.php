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

/**
 * Добавляем элемент RPA Командировка
 */
function businessTrip($rpaTypeId, $userId, $where, $departingTime, $arrivingTime, $purpose)
{
    $result = restCommand('rpa.item.add', array(
        'typeId' => $rpaTypeId,
        'fields' => array(
            'UF_RPA_7_1646576016' => $where,
            'UF_RPA_7_1646576079452' => $departingTime,
            'UF_RPA_7_1646576145747' => $arrivingTime,
            'UF_RPA_7_1646576677121' => $purpose,
            'UF_RPA_7_1646576967370' => $userId,
        ),
    ), $_REQUEST["auth"]);
    return $result;
}

/**
 * Добавляем элемент RPA Вызов курьера
 */
function courierCall($rpaTypeId, $title, $from, $to, $sender_contact, $sender_phone, $recipient_contact, $recipient_phone, $pickup_date, $weight, $dimensions, $procuration, $declared_value, $comment)
{
    $result = restCommand('rpa.item.add', array(
        'typeId' => $rpaTypeId,
        'fields' => array(
            'UF_RPA_15_NAME' => $title,
            'UF_RPA_15_1663760824400' => $from,
            'UF_RPA_15_1663760835551' => $to,
            'UF_RPA_15_1663930746' => $sender_contact,
            'UF_RPA_15_1669113982' => $sender_phone,
            'UF_RPA_15_1663930778' => $recipient_contact,
            'UF_RPA_15_1669114021' => $recipient_phone,
            'UF_RPA_15_1663760848635' => $pickup_date,
            'UF_RPA_15_1663760916425' => $weight,
            'UF_RPA_15_1663760943055' => $dimensions,
            'UF_RPA_15_1663760963155' => $procuration,
            'UF_RPA_15_1663761028407' => $declared_value,
            'UF_RPA_15_1663761338815' => $comment,
        ),
    ), $_REQUEST["auth"]);
    return $result;
}

/**
 * Добавляем элемент СП Внутреннее обучение
 */
function internalTraining($Smart_Type_ID, $title, $task_description, $relation, $employee, $link)
{
    $result = restCommand('crm.item.add', array(
        'entityTypeId' => $Smart_Type_ID,
        'fields' => [
            'title' => $title,
            'UfCrm_27_1642002369' => $task_description,
            'UfCrm_27_1641906223' => $relation,
            'UfCrm_27_1641906382' => $employee,
            'UfCrm_27_1655390003' => $link,
        ],
    ), $_REQUEST["auth"]);
    return $result;
}

/**
 * Поиск ID сотрудника по имени и фамилии
 */
function getUserId($fullName)
{
    $fullName = mb_strtolower($fullName);
    $sort = 'id';
    $order = "ASC";
    $stringItems = explode(" ", $fullName);
    $name = $stringItems[0];
    $lastName = $stringItems[1];
    $arUserInfo = restCommand('user.get', array(
        'sort' => $sort,
        'order' => $order,
        'FILTER' => array("ACTIVE" => "Y", "GROUPS_ID" => array(11), "NAME" => $name, "LAST_NAME" => $lastName),
        'ADMIN_MODE' => true,
    ), $_REQUEST["auth"]);
    $userInfo = $arUserInfo['result'];
    if (!empty($userInfo)) {
        $userId = $userInfo[0]['ID'];
        return $userId;
    } else {
        return null;
    }
}
