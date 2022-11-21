<?php

/**
$item = addEntityItem($entityCode, $itemName);
$itemId = $item['result'];    

$arItemsInfo = getEntityItems($entityCode);
$itemsInfo = $arItemsInfo['result'];

$arItemProperties = getEntityItemProperties($entityCode);
$itemProperties = $arItemProperties['result'];
 */

// check the event - register this application or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
    return false;
}
writeToLog($_REQUEST, '$_REQUEST');
// writeToLog($_REQUEST['data']['PARAMS']['DIALOG_ID'], $title = 'DIALOG_ID');
$messageFromUser = trim(mb_strtolower($_REQUEST['data']['PARAMS']['MESSAGE']));
$userId = $_REQUEST['data']['USER']['ID'];
// info about types of absence
$iBlockFields = restCommand('lists.field.get', array(
    'IBLOCK_TYPE_ID' => 'bitrix_processes',
    'IBLOCK_ID' => 119,

), $_REQUEST["auth"]);

$types = $iBlockFields['result']['PROPERTY_854']['DISPLAY_VALUES_FORM'];
$typeInfo = '';
foreach ($types as $typeId => $typeValue) {
    $typeInfo .= '[send=' . $typeId . ']' . $typeValue . '[/send] [BR]';
}
// info about departments
$iBlockFields = restCommand('lists.field.get', array(
    'IBLOCK_TYPE_ID' => 'bitrix_processes',
    'IBLOCK_ID' => 119,

), $_REQUEST["auth"]);

$departments = $iBlockFields['result']['PROPERTY_855']['DISPLAY_VALUES_FORM'];
$departmentInfo = '';
foreach ($departments as $departmentId => $departmentValue) {
    $departmentInfo .= '[send=' . $departmentId . ']' . $departmentValue . '[/send] [BR]';
}

switch ($messageFromUser) {
    case 'привет':
        $attach[] = array("MESSAGE" => 'Если хочешь узнать, что я могу, набери в сообщении или нажми [send=меню]меню[/send]');
        $arResult = array(
            'title' => '[b]Я чат-бот помощник, создан для удобства в работе на нашем портале. Пока что мой функционал ограничен, но я учусь :)[/b]',
            'attach' => $attach,
        );
        break;
    case 'меню':
        $arResult = array(
            'title' => '[b]Мои функции[/b]',
            'report' => 'Для вызова, нажми кнопку ниже',
            'keyboard' => $keyboardMain,
        );
        break;
    case 'заполняем отсутствие:':
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'title' => '[b]Всегда можно вернуться в начало[/b]',
            'attach' => $attach,
        );
        // $item = addEntityItem($entityCode, $messageFromUser);
        // $itemId = $item['result'];
        $arItemsInfo = getEntityItems($entityCode);
        $itemsInfo = $arItemsInfo['result'];
        writeToLog($arItemsInfo, 'Item');
        break;
    case 'вносим данные на оплату счета:':
        $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
        $arResult = array(
            'title' => '[b]Всегда можно вернуться в начало[/b]',
            'attach' => $attach,
        );
        break;
    default:
        $arResult = array(
            'title' => '[b]Туплю-с[/b]',
            'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
        );
}

// if ($messageFromUser == 'привет') {
//     $attach[] = array("MESSAGE" => 'Если хочешь узнать, что я могу, набери в сообщении или нажми [send=меню]меню[/send]');
//     $arResult = array(
//         'title' => '[b]Я чат-бот помощник, создан для удобства в работе на нашем портале. Пока что мой функционал ограничен, но я учусь :)[/b]',
//         'attach' => $attach,
//     );
// } elseif ($messageFromUser == 'меню') {
//     $arResult = array(
//         'title' => '[b]Мои функции[/b]',
//         'report' => 'Для вызова, нажми кнопку ниже',
//         'keyboard' => $keyboardMain,
//     );
// } elseif ($messageFromUser == 'заполняем отсутствие:') {
//     $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
//     $arResult = array(
//         'title' => '[b]Всегда можно вернуться в начало[/b]',
//         'attach' => $attach,
//     );
// } elseif ($messageFromUser == 'вносим данные на оплату счета:') {
//     $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
//     $arResult = array(
//         'title' => '[b]Всегда можно вернуться в начало[/b]',
//         'attach' => $attach,
//     );
// } else {
//     $arResult = array(
//         'title' => '[b]Туплю-с[/b]',
//         'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
//     );
// }

$answerParams = array(
    "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
    "MESSAGE" => $arResult['title'] . "\n" . $arResult['report'] . "\n",
    "KEYBOARD" => $arResult['keyboard'],
    "ATTACH" => array_merge(
        $arResult['attach']
    ),
);
$messageId = restCommand('imbot.message.add', $answerParams, $_REQUEST["auth"]);
