<?php

if ($step == 1) {
    $userId = getUserId($messageFromUser);
    if (isset($userId)) {
        $messageFromUser = mb_strtoupper($messageFromUser);
        updateEntityItem($entityCode, $currentItemId, 'business_trip_employee', $userId);
        updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "[b]Сотрудник:[/b] {$messageFromUser}, [b]ID:[/b] {$userId}. Далее заполните куда:",
            'attach' => $attach,
        );
    } else {
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "[b]Некорректно введены имя и фамилия сотрудника:[/b] {$messageFromUser}. Пожалуйста, введите имя и фамилию сотрудника [u]без ошибки[/u]:",
            'attach' => $attach,
        );
    }
} elseif ($step == 2) {
    updateEntityItem($entityCode, $currentItemId, 'business_trip_where', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Куда:[/b] {$messageFromUser}. Далее заполните планируемое время вылета/поезда в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
        'attach' => $attach,
    );
} elseif ($step == 3) {
    updateEntityItem($entityCode, $currentItemId, 'business_trip_departing_time', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Планируемое время вылета/поезда:[/b] {$messageFromUser}. Далее заполните планируемое время вылета/поезда в обратную сторону в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
        'attach' => $attach,
    );
} elseif ($step == 4) {
    updateEntityItem($entityCode, $currentItemId, 'business_trip_arriving_time', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Планируемое время вылета/поезда в обратную сторону:[/b] {$messageFromUser}. Далее заполните цель командировки (сформируйте амбициозную и полезную цель):",
        'attach' => $attach,
    );
} elseif ($step == 5) {
    updateEntityItem($entityCode, $currentItemId, 'business_trip_purpose', $messageFromUser);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'Внесенные данные:');
    $employee = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_employee'];
    $where = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_where'];
    $departingTime = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_departing_time'];
    $arrivingTime = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_arriving_time'];
    $purpose = $itemsInfo[0]['PROPERTY_VALUES']['business_trip_purpose'];
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Внесенные данные:[/b]\n
                        [b]Сотрудник:[/b] {$employee}\n
                        [b]Куда:[/b] {$where}\n
                        [b]Планируемое время вылета/поезда:[/b] {$departingTime}\n
                        [b]Планируемое время вылета/поезда в обратную сторону:[/b] {$arrivingTime}\n
                        [b]Цель командировки:[/b] {$purpose}\n
                        [b]Если все верно, [send=вношу данные о командировке]вносим[/send][/b]",
        'attach' => $attach,
    );
}
