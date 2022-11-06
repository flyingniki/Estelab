<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

$Own_Smart_Type_ID = 158;
$ownFactory = $container->getFactory($Own_Smart_Type_ID);
$ownItems = $ownFactory->getItems();

$Group_Smart_Type_ID = 130;
$groupFactory = $container->getFactory($Group_Smart_Type_ID);
$groupItems = $groupFactory->getItems();

foreach ($groupItems as $item) {
    $stageId = $item->getStageId();
    print_r($stageId);
    if ($stageId === 'DT130_119:CLIENT') {
        $employee = $item->getUfCrm_78_1667470176();
        $strategy = $item->getUfCrm_78_1667726390();
        $innovation = $item->getUfCrm_78_1667726419();
        $relationship = $item->getUfCrm_78_1667726434();
        $customer_focus = $item->getUfCrm_78_1667726447();
        $management = $item->getUfCrm_78_1667726463();
        $responsibility = $item->getUfCrm_78_1667726474();
        $arGroupGrades[$employee][] = [
            'strategy' => $strategy,
            'innovation' => $innovation,
            'relationship' => $relationship,
            'customer_focus' => $customer_focus,
            'management' => $management,
            'responsibility' => $responsibility
        ];
    }
}
foreach ($arGroupGrades as $employee => $arGrades) {
    $countGrades = count($arGrades);
    print_r($countGroupGrades);
    foreach ($arGrades as $gradesInfo) {
        
    }
}

print_r($arGroupGrades);
