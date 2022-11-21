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
writeToLog($_REQUEST, '$_REQUEST');
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

if ($messageFromUser == 'привет') {
    deleteEntityItems($entityCode);
    $attach[] = array("MESSAGE" => 'Если хочешь узнать, что я могу, набери в сообщении или нажми [send=меню]меню[/send]');
    $arResult = array(
        'title' => '[b]Я чат-бот помощник, создан для удобства в работе на нашем портале. Пока что мой функционал ограничен, но я учусь :)[/b]',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'меню') {
    deleteEntityItems($entityCode);
    $arResult = array(
        'title' => '[b]Мои функции[/b]',
        'report' => 'Для вызова, нажми кнопку ниже',
        'keyboard' => $keyboardMain,
    );
} elseif ($messageFromUser == 'заполняем отсутствие:') {
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Введите причину:',
        'attach' => $attach,
    );

    $item = addEntityItem($entityCode, $messageFromUser);
    $itemId = $item['result'];
    // writeToLog($itemId, 'itemId');

    updateEntityItem($entityCode, $itemId, 'step', '1');

    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsInfo');
} elseif ($messageFromUser == 'вносим данные на оплату счета:') {
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вносим данные об отсутствии') {
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    $case = $itemsInfo[0]['PROPERTY_VALUES']['case'];
    $dateBegin = $itemsInfo[0]['PROPERTY_VALUES']['dateBegin'];
    $dateEnd = $itemsInfo[0]['PROPERTY_VALUES']['dateEnd'];
    $type = $itemsInfo[0]['PROPERTY_VALUES']['type'];
    $department = $itemsInfo[0]['PROPERTY_VALUES']['department'];
    absenceAndProcessing($case, $dateBegin, $dateEnd, $userId, $type, $department);

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Данные успешно внесены!',
        'attach' => $attach,
    );
} else {
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    $currentItem = $itemsInfo[0];
    $currentItemId = $itemsInfo[0]['ID'];
    $step = $currentItem['PROPERTY_VALUES']['step'];

    $arResult = array(
        'title' => '[b]Туплю-с[/b]',
        'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
    );

    if ($step == 1) {
        // $arItemsInfo = getEntityItems($entityCode);
        // $itemsInfo = $arItemsInfo['result'];
        // writeToLog($itemsInfo, 'itemsInfo');        
        updateEntityItem($entityCode, $currentItemId, 'case', $messageFromUser);
        updateEntityItem($entityCode, $currentItemId, 'step', '2');
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        // writeToLog($itemsInfo, 'itemsNewInfo');
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "Причина: {$messageFromUser}. Далее заполните дату начала в формате 'дд.мм.гггг чч:мм:сс':",
            'attach' => $attach,
        );
    } elseif ($step == 2) {
        updateEntityItem($entityCode, $currentItemId, 'dateBegin', $messageFromUser);
        updateEntityItem($entityCode, $currentItemId, 'step', '3');
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        // writeToLog($itemsInfo, 'itemsNewInfo');
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "Дата начала: {$messageFromUser}. Далее заполните дату окончания в формате 'дд.мм.гггг чч:мм:сс':",
            'attach' => $attach,
        );
    } elseif ($step == 3) {
        updateEntityItem($entityCode, $currentItemId, 'dateEnd', $messageFromUser);
        updateEntityItem($entityCode, $currentItemId, 'step', '4');
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        // writeToLog($itemsInfo, 'itemsNewInfo');

        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "Дата окончания: {$messageFromUser}. Далее заполните тип отсутствия:\n{$typeInfo}",
            'attach' => $attach,
        );
    } elseif ($step == 4) {
        updateEntityItem($entityCode, $currentItemId, 'type', $messageFromUser);
        updateEntityItem($entityCode, $currentItemId, 'step', '5');
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        // writeToLog($itemsInfo, 'itemsNewInfo');

        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "Тип отсутствия: {$messageFromUser}.\n Далее заполните подразделение:\n{$departmentInfo}",
            'attach' => $attach,
        );
    } elseif ($step == 5) {
        updateEntityItem($entityCode, $currentItemId, 'department', $messageFromUser);
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        writeToLog($itemsInfo, 'itemsinfo');
        $case = $itemsInfo[0]['PROPERTY_VALUES']['case'];
        $dateBegin = $itemsInfo[0]['PROPERTY_VALUES']['dateBegin'];
        $dateEnd = $itemsInfo[0]['PROPERTY_VALUES']['dateEnd'];
        $type = $types[$itemsInfo[0]['PROPERTY_VALUES']['type']];
        $department = $departments[$itemsInfo[0]['PROPERTY_VALUES']['department']];
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "[b]Внесенные данные:[/b]\n
            Причина: {$case}\n
            Дата начала: {$dateBegin}\n
            Дата окончания: {$dateEnd}\n
            Тип отсутствия: {$type}\n
            Отдел: {$department}\n
            [b]Если все верно, [send=вносим данные об отсутствии]вносим[/send][/b]",
            'attach' => $attach,
        );
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
