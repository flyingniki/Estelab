<?php

CModule::IncludeModule('timeman');
$USER_ID = 32896;
$TimemanUser = new CTimeManUser($USER_ID);
$userSettings = $TimemanUser->GetSettings();
if ($userSettings["UF_TIMEMAN"]) {
    if ($TimemanUser->isDayOpenedToday()) {
        $isDayOpenedToday = 'рабочий день начат сегодня';
    };
    if ($TimemanUser->isDayPaused()) {
        $isDayPaused = 'установлен перерыв';
    };
    if ($TimemanUser->isDayOpen()) {
        $isDayOpen = 'рабочий день начат';
    };
    if ($TimemanUser->isDayExpired()) {
        $isDayExpired = 'рабочий день ИСТЕК';
    };
}
$start = (intdiv($userSettings['UF_TM_MAX_START'], 3600)) . 'ч:' . ($userSettings['UF_TM_MAX_START'] % 3600 === 0 ? '00' : (($userSettings['UF_TM_MAX_START'] % 3600) / 3600) * 60) . 'м';
$finish = (intdiv($userSettings['UF_TM_MIN_FINISH'], 3600)) . 'ч:' . ($userSettings['UF_TM_MIN_FINISH'] % 3600 === 0 ? '00' : (($userSettings['UF_TM_MIN_FINISH'] % 3600) / 3600) * 60) . 'м';
$minimal_duration = (intdiv($userSettings['UF_TM_MIN_DURATION'], 3600)) . 'ч:' . ($userSettings['UF_TM_MIN_DURATION'] % 3600 === 0 ? '00' : (($userSettings['UF_TM_MIN_DURATION'] % 3600) / 3600) * 60) . 'м';

$result = [
    'Парметры рабочего дня' => [
        'Рабочий день начат сегодня' => $isDayOpenedToday ? 'Да' : 'Нет',
        'Установлен перерыв' => $isDayPaused ? 'Да' : 'Нет',
        'Рабочий день начат' => $isDayOpen ? 'Да' : 'Нет',
        'Рабочий день истек' => $isDayExpired ? 'Да' : 'Нет',
    ],
    'Начало' => $start,
    'Окончание' => $finish,
    'Минимальная продолжительность' => $minimal_duration
];
