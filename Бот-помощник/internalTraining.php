<?php

if ($step == 1) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_title', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Название:[/b] {$messageFromUser}. Далее заполните описание проблемы/задачи:",
        'attach' => $attach,
    );
} elseif ($step == 2) {
    updateEntityItem($entityCode, $currentItemId, 'internal_training_task_description', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Описание проблемы/задачи:[/b] {$messageFromUser}. Далее заполните к чему относится:[BR] {$relationInfo}",
        'attach' => $attach,
    );
} elseif ($step == 3) {
    if (isset($relations[$messageFromUser])) {
        updateEntityItem($entityCode, $currentItemId, 'internal_training_relation', $messageFromUser);
        updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        $relation = $relations[$messageFromUser]['NAME'];
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "[b]К чему относится:[/b] {$relation}. Далее введите имя и фамилию сотрудника:",
            'attach' => $attach,
        );
    } else {
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'report' => "[b]Неверно введено, к чему относится:[/b] {$messageFromUser}. Пожалуйста, выберите из списка:[BR] {$relationInfo}",
            'attach' => $attach,
        );
    }
} elseif ($step == 4) {
    $userId = filter_var($messageFromUser, FILTER_SANITIZE_NUMBER_INT);
    if (is_numeric($userId)) {
        $arUser = getUserById($userId);
        if (!empty($arUser['result'])) {
            updateEntityItem($entityCode, $currentItemId, 'internal_training_employee', $userId);
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            $title = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_title'];
            $task_description = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_task_description'];
            $relation = $relations[$itemsInfo[0]['PROPERTY_VALUES']['internal_training_relation']]['NAME'];
            $user_full_name = $arUser['result'][0]['NAME'] . ' ' . $arUser['result'][0]['LAST_NAME'];
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Внесенные данные:[/b]\n
                            [b]Название:[/b] {$title}\n
                            [b]Описание проблемы/задачи:[/b] {$task_description}\n
                            [b]К чему относится:[/b] {$relation}\n
                            [b]Сотрудник:[/b] {$user_full_name}\n
                            [b]Если все верно, [send=вношу данные о внутреннем обучении]вносим[/send][/b]",
                'attach' => $attach,
            );
        } else {
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Некорректно введены имя и фамилия сотрудника:[/b] {$messageFromUser}. Пожалуйста, введите имя и фамилию сотрудника [u]без ошибки[/u]:",
                'attach' => $attach,
            );
        }
    } else {
        $userId = getUserId($messageFromUser);
        if (isset($userId)) {
            $messageFromUser = mb_strtoupper($messageFromUser);
            updateEntityItem($entityCode, $currentItemId, 'internal_training_employee', $userId);
            $arItemsInfo = getEntityItems($entityCode);
            $itemsInfo = $arItemsInfo['result'];
            $title = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_title'];
            $task_description = $itemsInfo[0]['PROPERTY_VALUES']['internal_training_task_description'];
            $relation = $relations[$itemsInfo[0]['PROPERTY_VALUES']['internal_training_relation']]['NAME'];
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Внесенные данные:[/b]\n
                            [b]Название:[/b] {$title}\n
                            [b]Описание проблемы/задачи:[/b] {$task_description}\n
                            [b]К чему относится:[/b] {$relation}\n
                            [b]Сотрудник:[/b] {$messageFromUser}\n
                            [b]Если все верно, [send=вношу данные о внутреннем обучении]вносим[/send][/b]",
                'attach' => $attach,
            );
        } else {
            $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
            $arResult = array(
                'report' => "[b]Некорректно введены имя и фамилия сотрудника:[/b] {$messageFromUser}. Пожалуйста, введите имя и фамилию сотрудника [u]без ошибки[/u]:",
                'attach' => $attach,
            );
        }
    }
}
