<?php

// по шагам
if ($step == 1) {
    updateEntityItem($entityCode, $currentItemId, 'absence_case', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Причина:[/b] {$messageFromUser}. Далее заполните дату начала в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
        'attach' => $attach,
    );
} elseif ($step == 2) {
    updateEntityItem($entityCode, $currentItemId, 'absence_date_begin', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Дата начала:[/b] {$messageFromUser}. Далее заполните дату окончания в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
        'attach' => $attach,
    );
} elseif ($step == 3) {
    updateEntityItem($entityCode, $currentItemId, 'absence_date_end', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Дата окончания:[/b] {$messageFromUser}. Далее заполните тип отсутствия:\n{$typeInfo}",
        'attach' => $attach,
    );
} elseif ($step == 4) {
    updateEntityItem($entityCode, $currentItemId, 'absence_type', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Тип отсутствия:[/b] {$types[$messageFromUser]}.\n Далее заполните подразделение:\n{$departmentInfo}",
        'attach' => $attach,
    );
} elseif ($step == 5) {
    updateEntityItem($entityCode, $currentItemId, 'absence_department', $messageFromUser);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'Внесенные данные:');
    $case = $itemsInfo[0]['PROPERTY_VALUES']['absence_case'];
    $dateBegin = $itemsInfo[0]['PROPERTY_VALUES']['absence_date_begin'];
    $dateEnd = $itemsInfo[0]['PROPERTY_VALUES']['absence_date_end'];
    $type = $types[$itemsInfo[0]['PROPERTY_VALUES']['absence_type']];
    $department = $departments[$itemsInfo[0]['PROPERTY_VALUES']['absence_department']];
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
