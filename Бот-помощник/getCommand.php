<?php

// check the event - authorize this event or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
    return false;

$result = false;
// in case of some command
foreach ($_REQUEST['data']['COMMAND'] as $command) {
    if ($command['COMMAND'] == 'absence') {
        $commandText = 'Заполняем процесс отсутствие:';
        $commandText = mb_strtolower($commandText);
        $result = restCommand('imbot.command.answer', array(
            "COMMAND_ID" => $command['COMMAND_ID'],
            "MESSAGE_ID" => $command['MESSAGE_ID'],
            "MESSAGE" => "[b]Предстоит внести следующую информацию:[/b]\n Причина \n Дата начала \n Дата окончания \n Тип \n Подразделение
        \n Как будешь готов, [send={$commandText}]кликай![/send]",
        ), $_REQUEST["auth"]);
    } elseif ($command['COMMAND'] == 'businessTrip') {
        $commandText = 'Заполняем данные о предстоящей командировке:';
        $commandText = mb_strtolower($commandText);
        $result = restCommand('imbot.command.answer', array(
            "COMMAND_ID" => $command['COMMAND_ID'],
            "MESSAGE_ID" => $command['MESSAGE_ID'],
            "MESSAGE" => "[b]Внеси информацию о предстоящей командировке[/b]
             \n Если готов, [send={$commandText}]кликай![/send]",
        ), $_REQUEST["auth"]);
    } elseif ($command['COMMAND'] == 'courierCall') {
        $commandText = 'Заполняем данные для вызова курьера:';
        $commandText = mb_strtolower($commandText);
        $result = restCommand('imbot.command.answer', array(
            "COMMAND_ID" => $command['COMMAND_ID'],
            "MESSAGE_ID" => $command['MESSAGE_ID'],
            "MESSAGE" => "[b]Внеси данные для вызова курьера[/b]
             \n Если готов, [send={$commandText}]кликай![/send]",
        ), $_REQUEST["auth"]);
    } elseif ($command['COMMAND'] == 'internalTraining') {
        $commandText = 'Заполняем данные для внутреннего обучения:';
        $commandText = mb_strtolower($commandText);
        $result = restCommand('imbot.command.answer', array(
            "COMMAND_ID" => $command['COMMAND_ID'],
            "MESSAGE_ID" => $command['MESSAGE_ID'],
            "MESSAGE" => "[b]Внеси данные для внутреннего обучения[/b]
             \n Если готов, [send={$commandText}]кликай![/send]",
        ), $_REQUEST["auth"]);
    }

    // write debug log
    writeToLog($_REQUEST['data']['COMMAND'], 'AssistantBot command add');
}
