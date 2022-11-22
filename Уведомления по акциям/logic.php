<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule('timeman');
CModule::IncludeModule('im');
$container = Service\Container::getInstance();

$Smart_Type_ID = 155;
$factory = $container->getFactory($Smart_Type_ID);
$items = $factory->getItems();
$message = '[b]Акции на ' . date('d.m.Y') . ':[/b] [BR][BR]';
$allActions = '';
$actionsOneDay = '[BR] [b]Действуют только сегодня:[/b] [BR] ';
$actionsHot = '[BR] [b]Действуют последний день: [/b] [BR]';
foreach ($items as $item) {
    $stageId = $item->getStageId();
    if ($stageId == 'DT155_28:2') {
        // echo '<pre>';
        // print_r($stageId);
        $itemId = $item->getId();
        $title = $item->getUfCrm_15_1634318596049();
        $descriprion = $item->getUfCrm_15_1634311135();
        $start = $item->getUfCrm_15_1634304482849();
        $startTime = $start->toString();
        $end = $item->getUfCrm_15_1634304505488();
        $endTime = $end->toString();
        $direction = $item->getUfCrm_15_1634306670();
        if ($direction == '129383') {
            // print_r($title);
            // print_r($direction);
            // print_r($descriprion);
            //print_r(strtotime($startTime);
            //print_r($endTime);            
            $allActions .= "[url=https://corp.estelab.ru/page/skram/m1_meropriyatiya/type/155/details/{$itemId}/]" . $title . "[/url][BR]";
            if (strtotime($start) == strtotime($end)) {
                $actionsOneDay .= "[url=https://corp.estelab.ru/page/skram/m1_meropriyatiya/type/155/details/{$itemId}/]" . $title . "[/url][BR]";
                $allActions = str_replace("[url=https://corp.estelab.ru/page/skram/m1_meropriyatiya/type/155/details/{$itemId}/]" . $title . "[/url][BR]", "", $allActions);
            }
            if (strtotime($end) == strtotime(date('d.m.Y'))) {
                $actionsHot .= "[url=https://corp.estelab.ru/page/skram/m1_meropriyatiya/type/155/details/{$itemId}/]" . $title . "[/url][BR]";
                $allActions = str_replace("[url=https://corp.estelab.ru/page/skram/m1_meropriyatiya/type/155/details/{$itemId}/]" . $title . "[/url][BR]", "", $allActions);
            }
        }
    }
}

$chat = new \CIMChat;
$chatId = $chat->Add(array(
    'TITLE' => 'Текущие акции',
    'DESCRIPTION' => 'Описание...',
    'COLOR' => 'AQUA', //цвет
    'TYPE' => IM_MESSAGE_OPEN, //тип чата
));

// ищем в нужном подразделении сотрудника с открытым рабочим днем в графике
$arDepartments = [
    'Клиника' => [5073, 4959, 7676, 5377, 7389]
];
$arSelect = ['ID', 'NAME', 'LAST_NAME'];
foreach ($arDepartments as $depName => $departmentIds) {
    foreach ($departmentIds as $departmentId) {
        $arEmployees = CIntranetUtils::GetDepartmentEmployees($departmentId, false, false, 'Y', $arSelect);

        while ($rsEmployees = $arEmployees->fetch()) {
            $usersIds[$depName][] = $rsEmployees['ID'];
            $usersIds[$depName] = array_filter($usersIds[$depName]);
        }
        $managerIds[$depName][] = CIntranetUtils::GetDepartmentManagerID($departmentId);
        $managerIds[$depName] = array_filter($managerIds[$depName]);
    }
}
// print_r($managerIds);
// print_r($usersIds);
$mergedIds = array_merge_recursive($managerIds, $usersIds);
foreach ($mergedIds as $depName => $arMergedId) {
    $arUniqueMergedId = array_unique($arMergedId);
    //print_r($arUniqueMergedId);
    $arEmployeeIds[$depName] = $arUniqueMergedId;
    foreach ($arUniqueMergedId as $empId) {
        $obUser = new CTimeManUser($empId);

        $state = $obUser->State(); // узнать статус рабочего дня сотрудника $USER_ID
        // $arInfo = $obUser->GetCurrentInfo(); // информация о рабочем дне сотрудника $USER_ID
        // echo '<pre>';
        // print_r($state);
        // print_r($arInfo);
        if ($state == 'OPENED') {
            $openedIds[] = $empId;
        }
    }
}
// print_r($openedIds);
// создаем чат
$chat->AddUser($chatId, $empId, null, true, true);
$ar = array(
    "TO_CHAT_ID" => $chatId, // ID чата
    "FROM_USER_ID" => 0,
    "SYSTEM" => Y,
    "MESSAGE"  => $message . $allActions . $actionsOneDay . $actionsHot, // Произвольный текст
);
CIMChat::AddMessage($ar);
// print_r($arEmployeeIds);
