<?php

function getPhoto($entityCode, $entityName, $addedN = '')
{
    $sectionId = getSectionEntityId($entityCode, $entityName);
    $dialogsWithPhoto = getCurrentDialogData($entityCode, $entityName, $sectionId);

    $photo = [];

    foreach ($dialogsWithPhoto['result'] as $keys) {
        foreach ($keys['PROPERTY_VALUES']['file_ids'] as $val) {
            if (!empty($val)) {
                $photo[] = $addedN . $val;
            }
        }
    }

    return $photo;
}

function get_list_fields($id, $field_id)
{
    $bitrix = restCommand('lists.field.get', array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => $id,
        'FIELD_ID' => $field_id,
    ), $_REQUEST["auth"]);
    return $bitrix['result']['L']['DISPLAY_VALUES_FORM'];
}

function update_message($botId, $id_message, $message = '', $keyboard = '')
{
    if ($message) {
        $array_fields = array(
            'BOT_ID' => $botId,
            "MESSAGE_ID" => $id_message,
            "MESSAGE" => $message,
            //"ATTACH" => array($listClients),
            "KEYBOARD" => $keyboard,
        );
    } else {
        $array_fields = array(
            'BOT_ID' => $botId,
            "MESSAGE_ID" => $id_message,
            "MESSAGE" => $message,
            //"ATTACH" => array($listClients),
            "KEYBOARD" => $keyboard,
        );
    }

    $bitrix = restCommand('imbot.message.update', $array_fields, $_REQUEST["auth"]);
    //     setEntityProperty($entityCode, $dialogID, 'fio');
    return $bitrix;
}

function delete_message($botId, $id_message)
{
    $result = restCommand('imbot.message.delete', array(
        'BOT_ID' => $botId,
        'MESSAGE_ID' => $id_message,
        'COMPLETE' => 'Y', //  If message is required to be deleted completely, without a trace, then specify 'Y' (optional parameter)
    ), $_REQUEST["auth"]);
    return $result;
}

function addNewBusinessProcess($fieldsProperty)
{
    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => 324,
        'ELEMENT_CODE' => 'element_' . time(),
        'FIELDS' => $fieldsProperty,
    );

    $bitrix = restCommand(
        'lists.element.add',
        $iBlockParams,
        $_REQUEST["auth"]
    );

    return $bitrix;
}

function addNewEntity($specialist, $dep, $name, $description = '', $id_files = '')
{
    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => 324,
        "ELEMENT_CODE" => 'element_' . time(),
        'FIELDS' => array(
            'NAME' => $name,
            'CREATED_BY' => $specialist,
            'PREVIEW_TEXT' => $description,
            'PROPERTY_2861' => $id_files,
        ),
    );

    $bitrix = restCommand(
        'lists.element.add',
        $iBlockParams,
        $_REQUEST["auth"]
    );

    return $bitrix['result'];
}

function add_message($message_text, $history = '', $key = '')
{
    $bitrix = restCommand('imbot.message.add', array(
        "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
        "MESSAGE" => $message_text,
        "ATTACH" => $history,
        "KEYBOARD" => $key,
    ), $_REQUEST["auth"]);
    return $bitrix;
}

function registerCommand($botId, $command, $hidden = 'N', $title = '', $handlerBackUrl)
{
    $result = restCommand('imbot.command.register', array(
        'BOT_ID' => $botId, // Идентификатор чат-бота владельца команды
        'COMMAND' => $command, // Текст команды, которую пользователь будет вводить в чатах
        'COMMON' => 'N', // Если указан Y, то команда доступна во всех чатах, если N - то доступна только в тех, где присутствует чат-бот
        'HIDDEN' => $hidden, // Скрытая команда или нет - по умолчанию N
        'EXTRANET_SUPPORT' => 'N', // Доступна ли команда пользователям Экстранет, по умолчанию N
        'CLIENT_ID' => '', // строковый идентификатор чат-бота, используется только в режиме Вебхуков
        'LANG' => array( // Массив переводов, обязательно указывать, как минимум, для RU и EN
            array('LANGUAGE_ID' => 'ru', 'TITLE' => $title, 'PARAMS' => ''), // Язык, описание команды, какие данные после команды нужно вводить.
        ),
        'EVENT_COMMAND_ADD' => $handlerBackUrl, // Ссылка на обработчик для команд
    ), $_REQUEST["auth"]);

    return $result;
}

function current_process($id)
{
    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => '324',
        'ELEMENT_ID' => $id,
    );

    $result_el = restCommand(
        'lists.element.get',
        $iBlockParams,
        $_REQUEST["auth"]
    );
    return $result_el;
}

function get_department_name($id_dep)
{

    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'lists_socnet',
        'IBLOCK_ID' => 185,
        'ELEMENT_ID' => $id_dep,
        'FILTER' => array(
            //      '=PROPERTY_3017' => 2960,
        ),
    );
    $bitrix = restCommand(
        'lists.element.get',
        $iBlockParams,
        $_REQUEST["auth"]
    );
    //        writeToLog($bitrix, ' массив департамента');
    return $bitrix['result'][0]['NAME'];
}

function get_department_list()
{

    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'lists_socnet',
        'IBLOCK_ID' => 185,
        //       'ELEMENT_ID' => $ID,
        'FILTER' => array(
            //      '=PROPERTY_3017' => 2960,
        ),
    );
    $bitrix = restCommand(
        'lists.element.get',
        $iBlockParams,
        $_REQUEST["auth"]
    );
    //        writeToLog($bitrix, ' полный массив направлений');
    return $bitrix;
}

// function key_departments()
// {
//     $array_dep = [];
//     $get_department_list = get_department_list()[result];
//     $count = count($get_department_list);
//     foreach ($get_department_list as $key => $value) {
//         $array_dep[] = array(
//             "TEXT" => $value['NAME'],
//             "COMMAND" => $value['NAME'],
//             "BG_COLOR" => "#e6e6e6",
//             "TEXT_COLOR" => "#FFF",
//             "COMMAND_PARAMS" => "T" . $value['ID'],
//             "DISPLAY" => "LINE",
//         );
//         /*
//     if (++$i == 1) $keybord_department = "array( \n";
//     $keybord_department .=         "Array( \n
//     \"TEXT\" => \'".$value['NAME']."\',  \n
//     \"COMMAND\" => \"".$value['NAME']."\",  \n
//     \"BG_COLOR\" => \"#e6e6e6\", \n
//     \"TEXT_COLOR\" => \"#FFF\", \n
//     \"COMMAND_PARAMS\" => \"T".$value['NAME']."\",  \n
//     \"DISPLAY\" => \"LINE\" \n
//     )";
//     if (--$count != 0) $keybord_department .= ",  \n";
//      */
//     }
//     // $keybord_department .= ")";
//     return $keybord_department;
// }

function updateElementBP($ID, $fields)
{
    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => '196',
        'ELEMENT_ID' => $ID,
        'FIELDS' => $fields,
    );

    $result = restCommand(
        'lists.element.update',
        $iBlockParams,
        $_REQUEST["auth"]
    );

    return $result;
}

function getFileUrlElementBP($ID, $fields)
{
    $iBlockParams = array(
        'IBLOCK_TYPE_ID' => 'bitrix_processes',
        'IBLOCK_ID' => '196',
        'ELEMENT_ID' => $ID,
        'FIELD_ID' => $fields,
    );

    $result = restCommand(
        'lists.element.get.file.url',
        $iBlockParams,
        $_REQUEST["auth"]
    );

    return $result;
}

function getCurrentDialogData($entityCode, $entityName, $sectionId = '')
{

    $result = restCommand('entity.item.get', array(
        'ENTITY' => $entityCode,
        'SORT' => array('ID' => 'ASC'),
        'FILTER' => array(
            '=NAME' => $entityName,
            '=SECTION' => $sectionId,
        ),
    ), $_REQUEST["auth"]);

    return $result;
}

function getEntityItemProperty($entityCode, $entityProperty = '*')
{

    $result = restCommand('entity.item.property.get', array(
        'ENTITY' => $entityCode,
        'PROPERTY' => $entityProperty,
    ), $_REQUEST["auth"]);

    return $result;
}


function setEntityProperty($entityCode, $dialogID, $val, $key)
{

    $result = restCommand('entity.item.update', array(
        "ENTITY" => $entityCode,
        "ID" => $dialogID,
        "PROPERTY_VALUES" => array(
            $key => $val,
        ),
    ), $_REQUEST["auth"]);

    return $result;
}

function getListClients($specialist)
{

    $element = getElementBP(false, $specialist);

    if (!empty($element['result'])) {

        $processID = 0;

        foreach ($element['result'] as $value) {

            $client = restCommand(
                'crm.contact.get',
                array('ID' => current_field($value['ID'], 2304)),
                $_REQUEST["auth"]
            );

            $arrName = [
                $client['result']['LAST_NAME'],
                $client['result']['NAME'],
                $client['result']['SECOND_NAME'],
            ];

            $fullName = implode(' ', (array_filter($arrName)));

            $result['MESSAGE'] .= "[send=/secondVisit " . $value['ID'] . "]" . $fullName . "[/send] " . $value["TIMESTAMP_X"] . "[BR]";
        }
    } else {
        $result['MESSAGE'] = "Ничего не найдено";
    }

    return $result;
}

function checkFieldsInput($array)
{
    $flag = true;

    foreach ($array as $value) {
        if (strstr($value["MESSAGE"], "Не заполнено") !== false) {
            $flag = false;
        }
    }

    return $flag;
}

function getFinalHistory($entityCode, $entityName)
{
    $currentDialog = getCurrentDialogData($entityCode, $entityName);
    $propertyValues = $currentDialog['result'][0]['PROPERTY_VALUES'];
    //    $element = getElementBP($propertyValues["processID"], USER_ID);
    // $fields = $element['result'][0];

    $result = [];

    $result[] = array("MESSAGE" => "Название заявки: [B]"
        . ($propertyValues['user_request']
            ? $propertyValues['user_request']
            : 'не заполнено') . "[/B][BR]");
    $result[] = array("DELIMITER" => array(
        'SIZE' => 300,
        'COLOR' => "#c6c6c6"
    ));

    writeToLog($propertyValues['photo_info'], 'какие есть фото');

    if (empty($propertyValues['photo_info'])) {
        $result[] = array("MESSAGE" => "Фото: [B]не заполнено[/B]");
    }
    return $result;
}

function getHistory($entityCode, $entityName)
{
    $currentDialog = getCurrentDialogData($entityCode, $entityName);
    $propertyValues = $currentDialog['result'][0]['PROPERTY_VALUES'];
    //    $element = getElementBP($propertyValues["processID"], USER_ID);
    //    $fields = $element['result'][0];

    $result = [];

    $result[] = array("MESSAGE" => "Название заявки (" . get_department_name($propertyValues['department']) . "): [B][BR]"
        . ($propertyValues['user_request']
            ? $propertyValues['user_request']
            : "не заполнено") . "[/B][BR]");
    $result[] = array("DELIMITER" => array(
        'SIZE' => 300,
        'COLOR' => "#c6c6c6"
    ));
    $result[] = array("MESSAGE" => "Описание: [B][BR]"
        . ($propertyValues['description']
            ? $propertyValues['description']
            : "не заполнено") . "[/B][BR]");
    $result[] = array("DELIMITER" => array(
        'SIZE' => 300,
        'COLOR' => "#c6c6c6"
    ));
    $result[] = array("MESSAGE" => "Фотографии: [B]"
        . ($propertyValues['photo_info']
            ? "заполнено"
            : "не заполнено") . "[/B][BR]");
    return $result;
}

function clearDialogs($entityCode, $entityName, $sectionId = '')
{
    $currentDialog = getCurrentDialogData($entityCode, $entityName, $sectionId);

    foreach ($currentDialog['result'] as $row) {
        $result = restCommand('entity.item.delete', array(
            "ENTITY" => $row['ENTITY'],
            'ID' => $row['ID'],
        ), $_REQUEST["auth"]);
    }
}

function getSectionEntityId($entityCode, $entityName)
{
    $res = restCommand('entity.section.get', array(
        'ENTITY' => $entityCode,
        'FILTER' => array(
            'NAME' => $entityName . '_PHOTO',
        ),
    ), $_REQUEST["auth"]);

    return $res['result'][0]['ID'];
}

function clearSectionEntity($entityCode, $entityName)
{
    $res = restCommand('entity.section.get', array(
        'ENTITY' => $entityCode,
        'FILTER' => array(
            'NAME' => $entityName . '_PHOTO',
        ),
    ), $_REQUEST["auth"]);

    foreach ($res['result'] as $row) {
        restCommand('entity.section.delete', array(
            "ENTITY" => $row['ENTITY'],
            'ID' => $row['ID'],
        ), $_REQUEST["auth"]);
    }
}

function getItemInSectionEntity($entityCode, $sectionId, $itemID)
{
    $res = restCommand('entity.item.get', array(
        'ENTITY' => $entityCode,
        'FILTER' => array(
            '=SECTION' => $sectionId,
            '=ID' => $itemID,
        ),
    ), $_REQUEST["auth"]);

    return $res;
}
