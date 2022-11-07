<?php

require($_SERVER['DOCUMENT_ROOT'] . '/php2excel/simplexlsxgen-master/src/SimpleXLSXGen.php');

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");

$container = Service\Container::getInstance();

$Own_Smart_Type_ID = 158;
$ownFactory = $container->getFactory($Own_Smart_Type_ID);
$ownItems = $ownFactory->getItems();

foreach ($ownItems as $item) {
    $stageId = $item->getStageId();
    // print_r($stageId);
    if ($stageId === 'DT158_116:CLIENT') {
        $employee = $item->getUfCrm_75_1667471974();
        $strategy = $item->getUfCrm_75_1667472508();
        $innovation = $item->getUfCrm_75_1667472574();
        $relationship = $item->getUfCrm_75_1667472593();
        $customerFocus = $item->getUfCrm_75_1667472610();
        $management = $item->getUfCrm_75_1667472628();
        $responsibility = $item->getUfCrm_75_1667472645();
        $arOwnGrades[$employee] = [
            'strategy' => $strategy,
            'innovation' => $innovation,
            'relationship' => $relationship,
            'customerFocus' => $customerFocus,
            'management' => $management,
            'responsibility' => $responsibility
        ];
    }
}
// echo '<pre>';
// echo 'OwnGrades: ';
// print_r($arOwnGrades);

$Group_Smart_Type_ID = 130;
$groupFactory = $container->getFactory($Group_Smart_Type_ID);
$groupItems = $groupFactory->getItems();

foreach ($groupItems as $item) {
    $stageId = $item->getStageId();
    // print_r($stageId);
    if ($stageId === 'DT130_119:CLIENT') {
        $employee = $item->getUfCrm_78_1667470176();
        $strategy = $item->getUfCrm_78_1667726390();
        $innovation = $item->getUfCrm_78_1667726419();
        $relationship = $item->getUfCrm_78_1667726434();
        $customerFocus = $item->getUfCrm_78_1667726447();
        $management = $item->getUfCrm_78_1667726463();
        $responsibility = $item->getUfCrm_78_1667726474();
        $arGroupGrades[$employee][] = [
            'strategy' => $strategy,
            'innovation' => $innovation,
            'relationship' => $relationship,
            'customerFocus' => $customerFocus,
            'management' => $management,
            'responsibility' => $responsibility
        ];
    }
}

foreach ($arGroupGrades as $employee => $arGrades) {
    $strategySum = 0;
    $innovationSum = 0;
    $relationshipSum = 0;
    $customerFocusSum = 0;
    $managementSum = 0;
    $responsibilitySum = 0;
    $countGrades = count($arGrades);
    // print_r($countGroupGrades);
    // сумма всех оценок
    foreach ($arGrades as $gradesInfo) {
        $resultSum[$employee] = [
            'strategy' => $strategySum += $gradesInfo['strategy'],
            'innovation' => $innovationSum += $gradesInfo['innovation'],
            'relationship' => $relationshipSum += $gradesInfo['relationship'],
            'customerFocus' => $customerFocusSum += $gradesInfo['customerFocus'],
            'management' => $managementSum += $gradesInfo['management'],
            'responsibility' => $responsibilitySum += $gradesInfo['responsibility']
        ];
    }
    // считаем среднюю оценку команды для каждого сотрудника
    $averageGroupGrades[$employee] = [
        'strategy' => round($resultSum[$employee]['strategy'] / $countGrades),
        'innovation' => round($resultSum[$employee]['innovation'] / $countGrades),
        'relationship' => round($resultSum[$employee]['relationship'] / $countGrades),
        'customerFocus' => round($resultSum[$employee]['customerFocus'] / $countGrades),
        'management' => round($resultSum[$employee]['management'] / $countGrades),
        'responsibility' => round($resultSum[$employee]['responsibility'] / $countGrades)
    ];
    // считаем разницу между командной и личной оценкой
    $diff[$employee] = [
        'strategy' => $averageGroupGrades[$employee]['strategy'] - $arOwnGrades[$employee]['strategy'],
        'innovation' => $averageGroupGrades[$employee]['innovation'] - $arOwnGrades[$employee]['innovation'],
        'relationship' => $averageGroupGrades[$employee]['relationship'] - $arOwnGrades[$employee]['relationship'],
        'customerFocus' => $averageGroupGrades[$employee]['customerFocus'] - $arOwnGrades[$employee]['customerFocus'],
        'management' => $averageGroupGrades[$employee]['management'] - $arOwnGrades[$employee]['management'],
        'responsibility' => $averageGroupGrades[$employee]['responsibility'] - $arOwnGrades[$employee]['responsibility']
    ];
    // собираем массив из переменных $arOwnGrades, $averageGroupGrades, $diff
    // $results[$employee] = [
    //     'strategy' => ['own' => $arOwnGrades[$employee]['strategy'], 'group' => $averageGroupGrades[$employee]['strategy'], 'diff' => $diff[$employee]['strategy']],
    //     'innovation' => ['own' => $arOwnGrades[$employee]['innovation'], 'group' => $averageGroupGrades[$employee]['innovation'], 'diff' => $diff[$employee]['innovation']],
    //     'relationship' => ['own' => $arOwnGrades[$employee]['relationship'], 'group' => $averageGroupGrades[$employee]['relationship'], 'diff' => $diff[$employee]['relationship']],
    //     'customerFocus' => ['own' => $arOwnGrades[$employee]['customerFocus'], 'group' => $averageGroupGrades[$employee]['customerFocus'], 'diff' => $diff[$employee]['customerFocus']],
    //     'management' => ['own' => $arOwnGrades[$employee]['management'], 'group' => $averageGroupGrades[$employee]['management'], 'diff' => $diff[$employee]['management']],
    //     'responsibility' => ['own' => $arOwnGrades[$employee]['responsibility'], 'group' => $averageGroupGrades[$employee]['responsibility'], 'diff' => $diff[$employee]['responsibility']]
    // ];
    $result[$employee] = [
        ['', 'Самооценка', 'Средняя оценка команды', 'Разница'],
        ['Стратегическое мышление', $arOwnGrades[$employee]['strategy'], $averageGroupGrades[$employee]['strategy'], $diff[$employee]['strategy']],
        ['Инновационность', $arOwnGrades[$employee]['innovation'], $averageGroupGrades[$employee]['innovation'], $diff[$employee]['innovation']],
        ['Построение отношений и влияние', $arOwnGrades[$employee]['relationship'], $averageGroupGrades[$employee]['relationship'], $diff[$employee]['relationship']],
        ['Клиентоориентированность', $arOwnGrades[$employee]['customerFocus'], $averageGroupGrades[$employee]['customerFocus'], $diff[$employee]['customerFocus']],
        ['Управление командой', $arOwnGrades[$employee]['management'], $averageGroupGrades[$employee]['management'], $diff[$employee]['management']],
        ['Ответственность', $arOwnGrades[$employee]['responsibility'], $averageGroupGrades[$employee]['responsibility'], $diff[$employee]['responsibility']]
    ];
}

// print_r($resultSum);
// echo 'AverageGroupGrades: ';
// print_r($averageGroupGrades);
// echo 'Diff: ';
// print_r($diff);
// echo 'Results: ';
// print_r($results);

$xlsx = new Shuchkin\SimpleXLSXGen();
foreach ($result as $employee => $grades) {
    $rsUser = CUser::GetByID($employee);
    $arUser = $rsUser->Fetch();
    $userName = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
    $xlsx->addSheet($grades, "Сотрудник {$userName}");
}
$title = 'Оценка компетенций ' . date("m.d.Y H.i.s");
$xlsx->downloadAs($title . '.xlsx');
