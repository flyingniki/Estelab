<?php

// check the event - authorize this event or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
    return false;

$result = false;
// in case of some command
foreach ($_REQUEST['data']['COMMAND'] as $command) {
    if ($command['COMMAND'] == 'absence') {
        $commandText = 'Заполняем отсутствие:';
        $commandText = mb_strtolower($commandText);
        $result = restCommand('imbot.command.answer', array(
            "COMMAND_ID" => $command['COMMAND_ID'],
            "MESSAGE_ID" => $command['MESSAGE_ID'],
            "MESSAGE" => "[b]Тебе следует внести следующую информацию:[/b]\n Причина \n Дата начала \n Дата окончания \n Сотрудник \n Тип \n Подразделение
        \n Как будешь готов, [send={$commandText}]кликай![/send]",
        ), $_REQUEST["auth"]);
    } elseif ($command['COMMAND'] == 'billPayment') {
        $commandText = 'Вносим данные на оплату счета:';
        $commandText = mb_strtolower($commandText);
        $result = restCommand('imbot.command.answer', array(
            "COMMAND_ID" => $command['COMMAND_ID'],
            "MESSAGE_ID" => $command['MESSAGE_ID'],
            "MESSAGE" => "Оплата счета \n [send={$commandText}]кликай![/send]",
        ), $_REQUEST["auth"]);
    }

    // write debug log
    writeToLog($_REQUEST['data']['COMMAND'], 'AssistantBot command add');
}
