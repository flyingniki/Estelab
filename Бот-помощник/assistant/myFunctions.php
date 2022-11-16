<?php

/**
 * Формируем отчет по команде
 *
 * @param      string $text строка, которую отправил юзер
 * @param      int $user идентификатор пользователя, который нам написал
 *
 * @return     array
 */
function getAnswer($command = '', $user)
{

    switch (mb_strtolower($command)) {
        case 'привет':
            $arResult = array(
                'title' => 'Я чат-бот помощник, создан для удобства в работе на нашем портале. Пока что мой функционал ограничен, но я учусь :)',
                'report' => 'Для знакомства с моими возможностями напиши "помощь"',
            );
            break;
        case 'помощь':
            $arResult = array(
                'title' => '',
                'report' => '',
            );
            break;
        default:
            $arResult = array(
                'title' => 'Туплю-с',
                'report'  => 'Не соображу, что вы хотите узнать. А может вообще не умею...',
            );
    }

    return $arResult;
}

function showActionList($command)
{
    $keyboard = array(
        array("TEXT" => $command['COMMAND_PARAMS'] == 1 ? "· 1 ·" : "1", "COMMAND" => "actionList", "COMMAND_PARAMS" => "1", "DISPLAY" => "LINE", "BLOCK" => "Y"),
        array("TEXT" => $command['COMMAND_PARAMS'] == 2 ? "· 2 ·" : "2", "COMMAND" => "actionList", "COMMAND_PARAMS" => "2", "DISPLAY" => "LINE", "BLOCK" => "Y"),
        array("TEXT" => $command['COMMAND_PARAMS'] == 3 ? "· 3 ·" : "3", "COMMAND" => "actionList", "COMMAND_PARAMS" => "3", "DISPLAY" => "LINE", "BLOCK" => "Y"),
        array("TEXT" => $command['COMMAND_PARAMS'] == 4 ? "· 4 ·" : "4", "COMMAND" => "actionList", "COMMAND_PARAMS" => "4", "DISPLAY" => "LINE", "BLOCK" => "Y"),
        array("TEXT" => $command['COMMAND_PARAMS'] == 5 ? "· 5 ·" : "5", "COMMAND" => "actionList", "COMMAND_PARAMS" => "5", "DISPLAY" => "LINE", "BLOCK" => "Y"),
    );
    return $keyboard;
}
