<?php

if ($step == 1) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_title', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Название:[/b] {$messageFromUser}. Далее заполните описание проблемы/задачи:",
        'attach' => $attach,
    );
} elseif ($step == 2) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_task_description', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Описание проблемы/задачи:[/b] {$messageFromUser}. Далее заполните к чему относится:[BR] {$relationInfo}",
        'attach' => $attach,
    );
} elseif ($step == 3) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_relation', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]К чему относится:[/b] {$messageFromUser}. Далее заполните ID сотрудника:",
        'attach' => $attach,
    );
} elseif ($step == 4) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_employee', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]ID сотрудника:[/b] {$messageFromUser}. Далее заполните ссылку:",
        'attach' => $attach,
    );
}  
elseif ($step == 5) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_link', $messageFromUser);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    writeToLog($itemsInfo, 'Внесенные данные:');
    $title = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_title'];
    $task_description = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_task_description'];
    $relation = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_relation'];
    $employee = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_employee'];
    $link = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_link'];
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Внесенные данные:[/b]\n
[b]Название:[/b] {$title}\n
[b]Описание проблемы/задачи:[/b] {$task_description}\n
[b]К чему относится:[/b] {$relation}\n
[b]Сотрудник:[/b] {$employee}\n
[b]Ссылка:[/b] {$link}\n
[b]Если все верно, [send=вношу данные о внутреннем обучении]вносим[/send][/b]",
        'attach' => $attach,
    );
}
