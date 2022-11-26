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
    $item = addEntityItem($entityCode, 'user_' . $userId . '_' . $messageFromUser);
    $itemId = $item['result'];
    updateEntityItem($entityCode, $itemId, 'step', '1');
    updateEntityItem($entityCode, $itemId, 'command', 'absence');
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
    $item = addEntityItem($entityCode, 'user_' . $userId . '_' . $messageFromUser);
    $itemId = $item['result'];
    updateEntityItem($entityCode, $itemId, 'step', '1');
    updateEntityItem($entityCode, $itemId, 'command', 'businessTrip');
    // writeToLog($item, 'EntityItem step 1');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Куда:',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вношу данные об отсутствии') {
    $arItemsInfo = getEntityItems($entityCode);
    // writeToLog($arItemsInfo, 'вношу данные об отсутствии');
    $itemsInfo = $arItemsInfo['result'];
    $case = $itemsInfo[0]['PROPERTY_VALUES']['case'];
    $dateBegin = $itemsInfo[0]['PROPERTY_VALUES']['dateBegin'];
    $dateEnd = $itemsInfo[0]['PROPERTY_VALUES']['dateEnd'];
    $type = $itemsInfo[0]['PROPERTY_VALUES']['type'];
    $department = $itemsInfo[0]['PROPERTY_VALUES']['department'];
    // writeToLog($userId, 'userId');
    absenceAndProcessing($case, $dateBegin, $dateEnd, $userId, $type, $department);

    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => 'Данные успешно внесены!',
        'attach' => $attach,
    );
} elseif ($messageFromUser == 'вношу данные о командировке') {
    $arItemsInfo = getEntityItems($entityCode);
    // writeToLog($arItemsInfo, 'вношу данные об отсутствии');
    $itemsInfo = $arItemsInfo['result'];
    $rpaTypeId = 7;
    $where = $itemsInfo[0]['PROPERTY_VALUES']['where'];
    $departingTime = $itemsInfo[0]['PROPERTY_VALUES']['departingTime'];
    $arrivingTime = $itemsInfo[0]['PROPERTY_VALUES']['arrivingTime'];
    $purpose = $itemsInfo[0]['PROPERTY_VALUES']['purpose'];
    // writeToLog($userId, 'userId');
    businessTrip($rpaTypeId, $userId, $where, $departingTime, $arrivingTime, $purpose);

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
            $step = $itemInfo['PROPERTY_VALUES']['step'];
            $command = $itemInfo['PROPERTY_VALUES']['command'];
        }
    }
    // по умолчанию при непрописанном сообщении
    $arResult = array(
        'title' => '[b]Туплю-с[/b]',
        'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
    );
    if ($command == 'absence') {
        // по шагам
        if ($step == 1) {
            updateEntityItem($entityCode, $currentItemId, 'case', $messageFromUser);
            updateEntityItem($entityCode, $currentItemId, 'step', '2');
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            // writeToLog($itemsInfo, 'itemsNewInfo');
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Причина:[/b] {$messageFromUser}. Далее заполните дату начала в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
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
                'report' => "[b]Дата начала:[/b] {$messageFromUser}. Далее заполните дату окончания в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
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
                'report' => "[b]Дата окончания:[/b] {$messageFromUser}. Далее заполните тип отсутствия:\n{$typeInfo}",
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
                'report' => "[b]Тип отсутствия:[/b] {$types[$messageFromUser]}.\n Далее заполните подразделение:\n{$departmentInfo}",
                'attach' => $attach,
            );
        } elseif ($step == 5) {
            updateEntityItem($entityCode, $currentItemId, 'department', $messageFromUser);
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            // writeToLog($itemsInfo, 'Внесенные данные:');
            $case = $itemsInfo[0]['PROPERTY_VALUES']['case'];
            $dateBegin = $itemsInfo[0]['PROPERTY_VALUES']['dateBegin'];
            $dateEnd = $itemsInfo[0]['PROPERTY_VALUES']['dateEnd'];
            $type = $types[$itemsInfo[0]['PROPERTY_VALUES']['type']];
            $department = $departments[$itemsInfo[0]['PROPERTY_VALUES']['department']];
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Внесенные данные:[/b]\n
        [b]Причина:[/b] {$case}\n
        [b]Дата начала:[/b] {$dateBegin}\n
        [b]Дата окончания:[/b] {$dateEnd}\n
        [b]Тип отсутствия:[/b] {$type}\n
        [b]Отдел:[/b] {$department}\n
        [b]Если все верно, [send=вношу данные об отсутствии]вносим[/send][/b]",
                'attach' => $attach,
            );
        }
    } elseif ($command == 'businessTrip') {
        if ($step == 1) {
            updateEntityItem($entityCode, $currentItemId, 'where', $messageFromUser);
            updateEntityItem($entityCode, $currentItemId, 'step', '2');
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            // writeToLog($itemsInfo, 'itemsNewInfo');
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Куда:[/b] {$messageFromUser}. Далее заполните планируемое время вылета/поезда в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
                'attach' => $attach,
            );
        } elseif ($step == 2) {
            updateEntityItem($entityCode, $currentItemId, 'departingTime', $messageFromUser);
            updateEntityItem($entityCode, $currentItemId, 'step', '3');
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            // writeToLog($itemsInfo, 'itemsNewInfo');
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Планируемое время вылета/поезда:[/b] {$messageFromUser}. Далее заполните планируемое время вылета/поезда в обратную сторону в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
                'attach' => $attach,
            );
        } elseif ($step == 3) {
            updateEntityItem($entityCode, $currentItemId, 'arrivingTime', $messageFromUser);
            updateEntityItem($entityCode, $currentItemId, 'step', '4');
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            // writeToLog($itemsInfo, 'itemsNewInfo');
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Планируемое время вылета/поезда в обратную сторону:[/b] {$messageFromUser}. Далее заполните цель командировки (сформируйте амбициозную и полезную цель):",
                'attach' => $attach,
            );
        } elseif ($step == 4) {
            updateEntityItem($entityCode, $currentItemId, 'purpose', $messageFromUser);
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            writeToLog($itemsInfo, 'Внесенные данные:');
            $where = $itemsInfo[0]['PROPERTY_VALUES']['where'];
            $departingTime = $itemsInfo[0]['PROPERTY_VALUES']['departingTime'];
            $arrivingTime = $itemsInfo[0]['PROPERTY_VALUES']['arrivingTime'];
            $purpose = $itemsInfo[0]['PROPERTY_VALUES']['purpose'];
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Внесенные данные:[/b]\n
        [b]Куда:[/b] {$where}\n
        [b]Планируемое время вылета/поезда:[/b] {$departingTime}\n
        [b]Планируемое время вылета/поезда в обратную сторону:[/b] {$arrivingTime}\n
        [b]Цель командировки:[/b] {$purpose}\n
        [b]Если все верно, [send=вношу данные о командировке]вносим[/send][/b]",
                'attach' => $attach,
            );
        }
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
