<?php

$userId = getUserId($messageFromUser);
if (isset($userId)) {
    // updateEntityItem($entityCode, $currentItemId, 'timeman_employee_user', $userId);
    $schedule = getShedule($userId);
    $UF_TIMEMAN = $schedule['result']['UF_TIMEMAN'];
    $UF_TM_FREE = $schedule['result']['UF_TM_FREE'];
    $UF_TM_MAX_START = $schedule['result']['UF_TM_MAX_START'];
    $UF_TM_MIN_FINISH = $schedule['result']['UF_TM_MIN_FINISH'];
    $UF_TM_MIN_DURATION = $schedule['result']['UF_TM_MIN_DURATION'];
    $UF_TM_ALLOWED_DELTA = $schedule['result']['UF_TM_ALLOWED_DELTA'];
    $ADMIN = $schedule['result']['ADMIN'];
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Сотрудник:[/b] {$messageFromUser}, [b]ID:[/b] {$userId}.\n
                [b]Информация о рабочем дне сотрудника:[/b]\n
                [b]UF_TIMEMAN:[/b] {$UF_TIMEMAN}\n
                [b]UF_TM_FREE:[/b] {$UF_TM_FREE}\n                
                [b]UF_TM_MAX_START:[/b] {$UF_TM_MAX_START}\n
                [b]UF_TM_MIN_FINISH:[/b] {$UF_TM_MIN_FINISH}\n
                [b]UF_TM_MIN_DURATION:[/b] {$UF_TM_MIN_DURATION}\n
                [b]UF_TM_ALLOWED_DELTA:[/b] {$UF_TM_ALLOWED_DELTA}\n
                [b]ADMIN:[/b] {$ADMIN}",
        'attach' => $attach,
    );
} else {
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Некорректно введены имя и фамилия сотрудника:[/b] {$messageFromUser}. Пожалуйста, введите имя и фамилию сотрудника [u]без ошибки[/u]:",
        'attach' => $attach,
    );
}
