<?php

use Bitrix\Crm\Service;

CModule::includeModule('crm');
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");

$entityTypeId = 133;
$container = Service\Container::getInstance();
$factory = $container->getFactory($entityTypeId);
$items = $factory->getItems();
// проверяем СП ППК на существование элементов
foreach ($items as $item) {
    $stage = $item->getStageId();
    $GetData =  $item->GetData();
    $dateCompletion = $GetData['UF_CRM_33_1669558869'] ? $GetData['UF_CRM_33_1669558869']->toString() : NULL;
    $arPpk[$GetData['UF_CRM_33_1669558942']] = [
        'STAGE' => $GetData['STAGE_ID'],
        'ID_SMART_ITEM' => $GetData['ID'],
        'DATE_COMPLETION' => $dateCompletion,
    ];
}

$arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_3432", "PROPERTY_3433", "PROPERTY_3434");
$arFilter = array(
    "IBLOCK_ID" => 440
);
$res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);

while ($ob = $res->fetch()) {
    $ppkList[$ob['ID']]['INDICATORS'] = trim($ob['PROPERTY_3432_VALUE']);
    $ppkList[$ob['ID']]['TITLE'] = $ob['NAME'];
    $ppkList[$ob['ID']]['REPEATING'] = $ob['PROPERTY_3433_VALUE'];
    $ppkList[$ob['ID']]['OBJECT'] = $ob['PROPERTY_3434_VALUE'];
}

foreach ($ppkList as $listItemId => $listItem) {
    if (!isset($arPpk[$listItemId])) {
        // создаем элемент СП            
        $arData = [
            'TITLE' => $listItem['INDICATORS'],
            'UF_CRM_33_1669558869' => date('d.m.Y H:i:s'), // текущая дата
            'UF_CRM_33_1669558942' => $listItemId, // статья контроля
            'UF_CRM_33_1672059807' => $listItem['REPEATING'], // периодичность контроля
        ];
        $newItem = $factory->createItem($arData);
        $res = $newItem->save();
        $item_id = $res->getId();
        $workflowTemplateId = 1782;
        $arErrorsTmp = array();
        CBPDocument::StartWorkflow(
            $workflowTemplateId,
            array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $entityTypeId . "_" . $item_id),
            array(),
            $arErrorsTmp
        );
    } else {
        if (($arPpk[$listItemId]['STAGE'] !== 'DT133_57:FAIL' && $arPpk[$listItemId]['STAGE'] !== 'DT133_57:SUCCESS') && isset($arPpk[$listItemId]['DATE_COMPLETION'])) {
            $dateCompletion = $arPpk[$listItemId]['DATE_COMPLETION'];
            $now = strtotime(date('d.m.Y'));
            $date = strtotime($dateCompletion);
            $dateDiff = $now - $date;
            $dateDiff = $dateDiff / 86400;
            switch ($listItem['REPEATING']) {
                case '1 раз в неделю':
                    $interval = 7;
                    $listItem['INTERVAL'] = '+ 1 week';
                    break;
                case '1 раз в месяц':
                    $interval = 30;
                    $listItem['INTERVAL'] = '+ 1 month';
                    break;
                case '2 раза в год':
                    $interval = 182;
                    $listItem['INTERVAL'] = '+ 6 month';
                    break;
                case '1 раз в год':
                    $interval = 365;
                    $listItem['INTERVAL'] = '+ 1 year';
                    break;
                case '1 раз в 5 лет':
                    $interval = 1825;
                    $listItem['INTERVAL'] = '+ 5 years';
                    break;
                default:
                    unset($ppkList[$listItemId]);
            }
            if ($dateDiff >= $interval) {
                $arData = [
                    'TITLE' => $listItem['INDICATORS'],
                    'UF_CRM_33_1669558869' => date("d.m.Y H:i:s", strtotime($arPpk[$listItemId]['DATE_COMPLETION'] . $listItem['INTERVAL'])), // текущая дата
                    'UF_CRM_33_1669558942' => $listItemId, //статья контроля
                    'UF_CRM_33_1672059807' => $listItem['REPEATING'], // периодичность контроля
                ];
                $newItem = $factory->createItem($arData);
                $res = $newItem->save();
                $item_id = $res->getId();
                $workflowTemplateId = 1782;
                $arErrorsTmp = array();
                CBPDocument::StartWorkflow(
                    $workflowTemplateId,
                    array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $entityTypeId . "_" . $item_id),
                    array(),
                    $arErrorsTmp
                );
            }
        }
    }
}
