<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Рабочий день сотрудника");

CModule::IncludeModule('timeman');
$USER_ID = $_POST['userID'];
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

// $result = [
//     'Парметры рабочего дня' => [
//         'Рабочий день начат сегодня' => $isDayOpenedToday ? 'Да' : 'Нет',
//         'Установлен перерыв' => $isDayPaused ? 'Да' : 'Нет',
//         'Рабочий день начат' => $isDayOpen ? 'Да' : 'Нет',
//         'Рабочий день истек' => $isDayExpired ? 'Да' : 'Нет',
//     ],
//     'Начало' => $start,
//     'Окончание' => $finish,
//     'Минимальная продолжительность' => $minimal_duration
// ];

$result = [
    'PARAMS' => [
        'isDayOpenedToday' => $isDayOpenedToday ? 'Да' : 'Нет',
        'isDayPaused' => $isDayPaused ? 'Да' : 'Нет',
        'isDayOpen' => $isDayOpen ? 'Да' : 'Нет',
        'isDayExpired' => $isDayExpired ? 'Да' : 'Нет',
    ],
    'START' => $start,
    'FINISH' => $finish,
    'MIN_DURATION' => $minimal_duration
];
?>
<style>
    .user-workday h1,
    h2 {
        color: red;
    }

    .user-workday-params {
        color: blue;
    }

    .user-workday-continue {
        color: green;
    }
</style>
<div class="user-workday">
    <h1>Рабочий день сотрудника</h1>
    <form action="employee-day.php" method="post">
        <p><input id="userID" type="text" name="userID">
            <label for="userID">Введите ID сотрудника</label>
        </p>
        <p><input type="submit"></p>
    </form>
    <h2>Парметры рабочего дня</h2>
    <div class="user-workday-params">
        <p>Рабочий день начат сегодня: <?= $result['PARAMS']['isDayOpenedToday'] ?></p>
        <p>Установлен перерыв: <?= $result['PARAMS']['isDayPaused'] ?></p>
        <p>Рабочий день начат: <?= $result['PARAMS']['isDayOpen'] ?></p>
        <p>Рабочий день истек: <?= $result['PARAMS']['isDayExpired'] ?></p>
    </div>
    <div class="user-workday-continue">
        <p>Начало: <?= $result['START'] ?></p>
        <p>Окончание: <?= $result['FINISH'] ?></p>
        <p>Минимальная продолжительность: <?= $result['MIN_DURATION'] ?></p>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>