<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule('timeman');
CModule::IncludeModule('im');
$container = Service\Container::getInstance();

$Smart_Type_ID = 155;
$factory = $container->getFactory($Smart_Type_ID);
$items = $factory->getItems();

$titleDistribution = '[b]Акции дистрибьюции на ' . date('d.m.Y') . ':[/b] [BR][BR]';
$allActionsDistribution = '';
$actionsOneDayDistribution = '[BR] [b]Действуют только сегодня:[/b] [BR] ';
$actionsHotDistribution = '[BR][BR] [b]Действуют последний день: [/b] [BR]';

$link = '[BR] [url=https://beautiful-face-forever.ru/actions/]Текущие акции на сайте КЛВ[/url]';

foreach ($items as $item) {
    $stageId = $item->getStageId();
    if ($stageId == 'DT155_28:2') {
        // echo '<pre>';
        // print_r($stageId);
        $itemId = $item->getId();
        $title = $item->getUfCrm_15_1634318596049();
        $description = $item->getUfCrm_15_1634311135();
        $start = $item->getUfCrm_15_1634304482849();
        $startTime = $start->toString();
        $end = $item->getUfCrm_15_1634304505488();
        $endTime = $end->toString();
        $direction = $item->getUfCrm_15_1634306670();
        if ($direction == '129626') { // дистрибьюция
            // print_r($title);
            // print_r($direction);
            // print_r($description);
            //print_r(strtotime($startTime);
            //print_r($endTime);            
            $allActionsDistribution .= '[b]' . $title . '[/b][BR][i]Описание:[BR]' . $description . '[/i][BR]';
            if (strtotime($start) == strtotime($end)) {
                $actionsOneDayDistribution .= $title . '[BR]';
                $allActionsDistribution = str_replace($title, '', $allActionsDistribution);
            }
            if (strtotime($end) == strtotime(date('d.m.Y'))) {
                $actionsHotDistribution .= $title . '[BR]';
                $allActionsDistribution = str_replace($title, '', $allActionsDistribution);
            }
        }
    }
}

$messageDistribution = $titleDistribution . $allActionsDistribution . $actionsOneDayDistribution . $actionsHotDistribution;

$arDepartments = [
    'Дистрибьюция' => [376, 6453],
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
        // $obUser = new CTimeManUser($empId);

        // $state = $obUser->State(); // узнать статус рабочего дня сотрудника $USER_ID
        // $arInfo = $obUser->GetCurrentInfo(); // информация о рабочем дне сотрудника $USER_ID
        // echo '<pre>';
        // print_r($state);
        // print_r($arInfo);
        // if ($state == 'OPENED') {
        $openedIds[] = $empId;
        // }
    }
}

// $pic = $_SERVER['DOCUMENT_ROOT'] . '/upload/sale/sale.png';
// $avatarId = \CFile::SaveFile(\CFile::MakeFileArray($pic), 'im');
$chat = new \CIMChat;
// сначала выполняем в консоли для создания чата, так как из агента не отрабатывает, затем получаем ID чата и подставляем в код
// $chatId = $chat->Add(array(
//     'TITLE' => 'Текущие акции дистрибьюция',
//     'DESCRIPTION' => 'Описание...',
//     'COLOR' => 'AQUA', //цвет
//     'TYPE' => IM_MESSAGE_OPEN, //тип чата
//     'AUTHOR_ID' => $openedIds[array_rand($openedIds, 1)], //владелец чата
//     'AVATAR_ID' => $avatarId, //аватарка чата
// ));

$currentTime = strtotime(date('d.m.Y H:i:s'));
$startWork = '09:00';
$endWork = '21:00';
$startDateTime = strtotime(date('d.m.Y') . ' ' . $startWork);
$endDateTime = strtotime(date('d.m.Y') . ' ' . $endWork);
if (($currentTime >= $startDateTime) && ($currentTime <= $endDateTime)) {
    foreach ($openedIds as $empId) {
        $chat->AddUser(132175, $empId, false, true, true);
    }
    $ar = array(
        "TO_CHAT_ID" => 132175, // ID чата
        "FROM_USER_ID" => 0,
        "SYSTEM" => Y,
        "MESSAGE"  => $messageDistribution . $link, // Произвольный текст
    );
    CIMChat::AddMessage($ar);
} else {
    // удаляем пользователей из чата
    foreach ($openedIds as $empId) {
        $chat->DeleteUser(132175, $empId, false, true, true);
    }
}
