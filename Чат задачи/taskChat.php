<?php

CModule::IncludeModule('im');

$taskId = $data['ID'];
$taskTitle = $data['TITLE'];
$taskDescription = $data['DESCRIPTION'];
$createdById = $data['CREATED_BY'];
$responsibleId = $data['RESPONSIBLE_ID'];
$arAccomplices = $data['ACCOMPLICES'];
$arAuditors = $data['AUDITORS'];

$chat = new \CIMChat;
$chatId = $chat->Add(array(
    'TITLE' => $taskTitle,
    'DESCRIPTION' => $taskDescription,
    'COLOR' => 'AQUA', //цвет
    'TYPE' => IM_MESSAGE_OPEN, //тип чата
    'AUTHOR_ID' => $createdById, //владелец чата
    'ENTITY_TYPE' => 'TASKS',
    'ENTITY_ID' => $taskId,
));
$arUsers = array_merge_recursive($arAccomplices, $arAuditors);
array_push($arUsers, $createdById, $responsibleId);
$arUsersUnique = array_unique($arUsers);
foreach ($arUsersUnique as $user) {
    $chat->AddUser($chatId, $user, false, true, true);
}

$ar = array(
    "TO_CHAT_ID" => $chatId, // ID чата
    "FROM_USER_ID" => $createdById,
    "SYSTEM" => Y,
    "MESSAGE"  => "Чат задачи [url=https://corp.estelab.ru/company/personal/user/{$createdById}/tasks/task/view/{$taskId}/]{$taskTitle}[/url]"
);

CIMChat::AddMessage($ar);
