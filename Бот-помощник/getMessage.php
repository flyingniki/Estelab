<?php

/**
$item = addEntityItem($entityCode, $itemName);
$itemId = $item['result'];    

$arItemsInfo = getEntityItems($entityCode);
$itemsInfo = $arItemsInfo['result'];

$arItemProperties = getEntityItemProperties($entityCode);
$itemProperties = $arItemProperties['result'];
 */

// check the event - register this application or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
    return false;
}
// writeToLog($_REQUEST, '$_REQUEST');
// writeToLog(getEntityItemProperties($entityCode), 'properties');
// writeToLog($_REQUEST['data']['PARAMS']['DIALOG_ID'], $title = 'DIALOG_ID');
$messageFromUser = trim(mb_strtolower($_REQUEST['data']['PARAMS']['MESSAGE']));
$userId = $_REQUEST['data']['USER']['ID'];

// info about types of absence
$iBlockFields = restCommand('lists.field.get', array(
    'IBLOCK_TYPE_ID' => 'bitrix_processes',
    'IBLOCK_ID' => 119,
), $_REQUEST["auth"]);

$types = $iBlockFields['result']['PROPERTY_854']['DISPLAY_VALUES_FORM'];
$typeInfo = '';
foreach ($types as $typeId => $typeValue) {
    $typeInfo .= '[send=' . $typeId . ']' . $typeValue . '[/send] [BR]';
}

// info about departments
$iBlockFields = restCommand('lists.field.get', array(
    'IBLOCK_TYPE_ID' => 'bitrix_processes',
    'IBLOCK_ID' => 119,
), $_REQUEST["auth"]);

$departments = $iBlockFields['result']['PROPERTY_855']['DISPLAY_VALUES_FORM'];
$departmentInfo = '';
foreach ($departments as $departmentId => $departmentValue) {
    $departmentInfo .= '[send=' . $departmentId . ']' . $departmentValue . '[/send] [BR]';
}

// info about relations
$iBlockElements = restCommand('lists.element.get', array(
    'IBLOCK_TYPE_ID' => 'lists',
    'IBLOCK_ID' => 362,
), $_REQUEST["auth"]);
// writeToLog($iBlockFields, 'relations');
$arRelations = $iBlockElements['result'];
$relationInfo = '';
foreach ($arRelations as $relation) {
    $relationInfo .= '[send=' . $relation['ID'] . ']' . $relation['NAME'] . '[/send] [BR]';
}
// writeToLog($relationInfo, 'relationInfo');

if ($messageFromUser == 'привет') {
    deleteEntityItem($entityCode, $userId);

    $attach[] = array("MESSAGE" => 'Если хочешь узнать, что я могу, набери в сообщении или нажми [send=меню]меню[/send]');
    $arResult = array(
        'title' => '[b]Я чат-бот помощник, создан для удобства в работе на нашем портале. Пока что мой функционал ограничен, но я учусь :)[/b]',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'меню') {
    deleteEntityItem($entityCode, $userId);

    $arResult = array(
        'title' => '[b]Мои функции[/b]',
        'report' => 'Для вызова, нажми кнопку ниже',
        'keyboard' => $keyboardMain,
    );
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsInfo After deleting');
} elseif ($messageFromUser == 'заполняем процесс отсутствие:') {
    deleteEntityItem($entityCode, $userId);

    $item = addEntityItem($entityCode, 'user_' . $userId . '_' . $messageFromUser);
    $itemId = $item['result'];
    updateEntityItem($entityCode, $itemId, 'general_step', '1');
    updateEntityItem($entityCode, $itemId, 'general_command', 'absence');
    // writeToLog($item, 'EntityItem step 1');

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Введите причину:',
        'attach' => $attach,
    );

    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'заполняем процесс отсутствие:');
} elseif ($messageFromUser == 'заполняем данные о предстоящей командировке:') {
    deleteEntityItem($entityCode, $userId);

    $item = addEntityItem($entityCode, 'user_' . $userId . '_' . $messageFromUser);
    $itemId = $item['result'];
    updateEntityItem($entityCode, $itemId, 'general_step', '1');
    updateEntityItem($entityCode, $itemId, 'general_command', 'businessTrip');
    // writeToLog($item, 'EntityItem step 1');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Введите имя и фамилию сотрудника:',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'заполняем данные для вызова курьера:') {
    deleteEntityItem($entityCode, $userId);

    $item = addEntityItem($entityCode, 'user_' . $userId . '_' . $messageFromUser);
    $itemId = $item['result'];
    updateEntityItem($entityCode, $itemId, 'general_step', '1');
    updateEntityItem($entityCode, $itemId, 'general_command', 'courierCall');
    // writeToLog($item, 'EntityItem step 1');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Название:',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'заполняем данные для внутреннего обучения:') {
    deleteEntityItem($entityCode, $userId);

    $item = addEntityItem($entityCode, 'user_' . $userId . '_' . $messageFromUser);
    $itemId = $item['result'];
    updateEntityItem($entityCode, $itemId, 'general_step', '1');
    updateEntityItem($entityCode, $itemId, 'general_command', 'internalTraining');
    // writeToLog($item, 'EntityItem step 1');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Название:',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вношу данные об отсутствии') {
    $arItemsInfo = getEntityItems($entityCode);
    // writeToLog($arItemsInfo, 'вношу данные об отсутствии');
    $itemsInfo = $arItemsInfo['result'];
    $case = $itemsInfo[0]['PROPERTY_VALUES']['absence_case'];
    $dateBegin = $itemsInfo[0]['PROPERTY_VALUES']['absence_date_begin'];
    $dateEnd = $itemsInfo[0]['PROPERTY_VALUES']['absence_date_end'];
    $type = $itemsInfo[0]['PROPERTY_VALUES']['absence_type'];
    $department = $itemsInfo[0]['PROPERTY_VALUES']['absence_department'];
    // writeToLog($userId, 'userId');
    absenceAndProcessing($case, $dateBegin, $dateEnd, $userId, $type, $department);

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Данные успешно внесены!',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вношу данные о командировке') {
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    $rpaTypeId = 7;
    $employee = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_employee'];
    $where = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_where'];
    $departingTime = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_departing_time'];
    $arrivingTime = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_arriving_time'];
    $purpose = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_purpose'];
    // writeToLog($userId, 'userId');
    businessTrip($rpaTypeId, $employee, $where, $departingTime, $arrivingTime, $purpose);

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Данные успешно внесены!',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вношу данные для вызова курьера') {
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    $rpaTypeId = 15;
    $title = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_title'];
    $from = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_from'];
    $to = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_to'];
    $sender_contact = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_sender_contact'];
    $sender_phone = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_sender_phone'];
    $recipient_contact = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_recipient_contact'];
    $recipient_phone = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_recipient_phone'];
    $pickup_date = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_pickup_date'];
    $weight = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_weight'];
    $dimensions = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_dimensions'];
    $procuration = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_procuration'];
    $declared_value = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_declared_value'];
    $comment = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_comment'];
    // writeToLog($userId, 'userId');
    courierCall($rpaTypeId, $title, $from, $to, $sender_contact, $sender_phone, $recipient_contact, $recipient_phone, $pickup_date, $weight, $dimensions, $procuration, $declared_value, $comment);

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Данные успешно внесены!',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вношу данные о внутреннем обучении') {
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    $Smart_Type_ID = 151;
    $title = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_title'];
    $task_description = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_task_description'];
    $relation = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_relation'];
    $employee = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_employee'];
    $link = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_link'];
    // writeToLog($userId, 'userId');
    internalTraining($Smart_Type_ID, $title, $task_description, $relation, $employee, $link);

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Данные успешно внесены!',
        'attach' => $attach,
    );
} else {
    // получаем текущий элемент сущности для определенного пользователя
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    writeToLog($itemsInfo, 'itemsInfo');
    foreach ($itemsInfo as $itemInfo) {
        if ($itemInfo['CREATED_BY'] == $userId) {
            $currentItemId = $itemInfo['ID'];
            $step = $itemInfo['PROPERTY_VALUES']['general_step'];
            $command = $itemInfo['PROPERTY_VALUES']['general_command'];
        }
    }
    // по умолчанию при непрописанном в условии сообщении
    $arResult = array(
        'title' => '[b]Туплю-с[/b]',
        'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
    );
    if ($command == 'absence') {
        require_once __DIR__ . '/absence.php';
    } elseif ($command == 'businessTrip') {
        require_once __DIR__ . '/businessTrip.php';
    } elseif ($command == 'courierCall') {
        require_once __DIR__ . '/courierCall.php';
    } elseif ($command == 'internalTraining') {
        require_once __DIR__ . '/internalTraining.php';
    }
}

$answerParams = array(
    "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
    "MESSAGE" => $arResult['title'] . "\n" . $arResult['report'] . "\n",
    "KEYBOARD" => $arResult['keyboard'],
    "ATTACH" => array_merge(
        $arResult['attach']
    ),
);
$messageId = restCommand('imbot.message.add', $answerParams, $_REQUEST["auth"]);
