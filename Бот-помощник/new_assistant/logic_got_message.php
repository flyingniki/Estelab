<?php

if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
    return false;
}

$step = $propertyValues['step'];
$messageFromUser = trim(mb_strtolower($_REQUEST['data']['PARAMS']['MESSAGE']));

if ($messageFromUser == "привет") {
    clearSectionEntity($entityCode, $entityName);
    clearDialogs($entityCode, $entityName);

    restCommand('entity.item.add', array(
        'ENTITY' => $entityCode,
        'NAME' => $entityName,
    ), $_REQUEST["auth"]);

    $currentDialog = getCurrentDialogData($entityCode, $entityName);
    $propertyValues = $currentDialog['result'][0]['PROPERTY_VALUES'];
    $dialogID = $currentDialog['result'][0]['ID'];
    setEntityProperty($entityCode, $dialogID, 1, 'step');
    $list_what_happen = get_list_fields(324, "PROPERTY_3166");

    $entitySectionId = restCommand('entity.section.add', array(
        "ENTITY" => $entityCode,
        'NAME' => $entityName . '_PHOTO',
    ), $_REQUEST["auth"]);

    foreach ($list_what_happen as $key => $value) {
        $text_what_happen .= ++$k . '. [send=' . $value . ']' . $value . '[/send][br]';
    }

    $last_bot_message_id = add_message('Выбери с чем связано ниже или нажми [b]ОТМЕНИТЬ[/b] заявку: [br]'
        . $text_what_happen, '', $keyboard["CANCEL"]);

    delete_message($bot_ID, $propertyValues['id_pre_message']);
    setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
} else if ($step == 1) {
    $list_what_happen = get_list_fields(324, "PROPERTY_3166");
    foreach ($list_what_happen as $key => $value) {
        if (trim(mb_strtolower($value)) == $messageFromUser) {
            setEntityProperty($entityCode, $dialogID, $key, 'according_to');
            setEntityProperty($entityCode, $dialogID, 2, 'step');
            $urgent_ticket = get_list_fields(324, "PROPERTY_3333");
            foreach ($urgent_ticket as $key => $value) {
                $text_urgent_ticket .= ++$k . '. [send=' . $value . ']' . $value . '[/send][br]';
            }

            //delete_message($bot_ID, $propertyValues['id_pre_message']);
            $last_bot_message_id = add_message('Тип обращения: [br] Заявка - это задача решаемая в течение 1 дня.[br]Задача предполагает разработку и согласование.[br]' . $text_urgent_ticket, '', $keyboard["CANCEL"]);
            update_message($bot_ID, $propertyValues['id_pre_message'], $message = 'Запустился процесс создания заявки', $keyboard = '');
            setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
        }
    }
} else if ($step == 2) {
    $list_accoding_to = get_list_fields(324, "PROPERTY_3333");
    foreach ($list_accoding_to as $key => $value) {
        if (trim(mb_strtolower($value)) == $messageFromUser) {
            setEntityProperty($entityCode, $dialogID, $key, 'type_request');
            setEntityProperty($entityCode, $dialogID, 3, 'step');
            $urgent_ticket = get_list_fields(324, "PROPERTY_2474");
            foreach ($urgent_ticket as $key => $value) {
                $text_urgent_ticket .= ++$k . '. [send=' . $value . ']' . $value . '[/send][br]';
            }
            $last_bot_message_id = add_message('Срочность: [br]' . $text_urgent_ticket, '', $keyboard["CANCEL"]);
            delete_message($bot_ID, $propertyValues['id_pre_message']);
            //update_message($bot_ID, $propertyValues['id_pre_message'], $message = 'Запустился процесс создания заявки', $keyboard = '');
            setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
        }
    }
} else if ($step == 3) {
    $list_accoding_to = get_list_fields(324, "PROPERTY_2474");
    foreach ($list_accoding_to as $key => $value) {
        if (trim(mb_strtolower($value)) == $messageFromUser) {
            setEntityProperty($entityCode, $dialogID, $key, 'urgent');
            setEntityProperty($entityCode, $dialogID, 4, 'step');
            $urgent_ticket = get_list_fields(324, "PROPERTY_2478");
            foreach ($urgent_ticket as $key => $value) {
                $text_urgent_ticket .= ++$k . '. [send=' . $value . ']' . $value . '[/send][br]';
            }
            $last_bot_message_id = add_message('Касается: [br]' . $text_urgent_ticket, '', $keyboard["CANCEL"]);
            delete_message($bot_ID, $propertyValues['id_pre_message']);
            //update_message($bot_ID, $propertyValues['id_pre_message'], $message = 'Запустился процесс создания заявки', $keyboard = '');
            setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
        }
    }
} else if ($step == 4) {
    $list_accoding_to = get_list_fields(324, "PROPERTY_2478");
    foreach ($list_accoding_to as $key => $value) {
        if (trim(mb_strtolower($value)) == $messageFromUser) {
            setEntityProperty($entityCode, $dialogID, $key, 'who_involve');
            setEntityProperty($entityCode, $dialogID, 5, 'step');
            $urgent_ticket = get_list_fields(324, "PROPERTY_2478");
            foreach ($urgent_ticket as $key => $value) {
                $text_urgent_ticket .= ++$k . '. [send=' . $value . ']' . $value . '[/send][br]';
            }
            $last_bot_message_id = add_message('Напишите суть заявки ([b]неболее 6-ти слов[/b]):', '', $keyboard["CANCEL"]);
            delete_message($bot_ID, $propertyValues['id_pre_message']);
            update_message($bot_ID, $propertyValues['id_pre_message'], $message = 'Запустился процесс создания заявки', $keyboard = '');
            setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
        }
    }
} else if ($step == 5) {
    setEntityProperty($entityCode, $dialogID, $messageFromUser, 'name_request');
    $last_bot_message_id = add_message(
        '[b]Подробно опишите проблему[/b]:
	1. В какой момент возникает проблема?
	2. Как ты считаешь с чем связана проблема?
	3. Прикрепи фото или скриншот
	4. Дай ссылку',
        '',
        $keyboard["CANCEL"]
    );
    delete_message($bot_ID, $propertyValues['id_pre_message']);
    update_message($bot_ID, $propertyValues['id_pre_message'], $message = 'Запустился процесс создания заявки', $keyboard = '');
    setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
    setEntityProperty($entityCode, $dialogID, 6, 'step');
} else if ($step == 6) {

    if (!empty($_REQUEST['data']['PARAMS']['FILES'])) {

        $fileId = $_REQUEST['data']['PARAMS']['PARAMS']['FILE_ID'][0];
        $sectionId = getSectionEntityId($entityCode, $entityName);

        $res = restCommand('entity.item.add', array(
            'ENTITY' => $entityCode,
            'NAME' => $entityName,
            'SECTION' => $sectionId,
            'PROPERTY_VALUES' => array(
                'file_ids' => $fileId,
            ),
        ), $_REQUEST["auth"]);

        $res = $res['result'];
        $checkLastElement = getItemInSectionEntity($entityCode, $sectionId, ++$res);

        if ($checkLastElement['total'] === 0) {
            $last_bot_message_id = add_message('Добавь еще описание, фото, ссылку или нажми [b]ОТМЕНИТЬ[/b]:', '', $keyboard["FINNALY"]);
            delete_message($bot_ID, $propertyValues['id_pre_message']);
            setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');

            //delete_message($bot_ID, $propertyValues['id_pre_message']);
            //update_message($bot_ID, $propertyValues['id_pre_message'], $message = 'Запустился процесс создания заявки', $keyboard = '');
        }
    } else if ($propertyValues['description']) {
        setEntityProperty($entityCode, $dialogID, $propertyValues['description'] . "\n" . $messageFromUser, 'description');
        delete_message($bot_ID, $propertyValues['id_pre_message']);
        $last_bot_message_id = add_message('Добавь еще описание, фото, ссылку или нажми [b]ОТМЕНИТЬ[/b]:', '', $keyboard["FINNALY"]);
    } else {
        setEntityProperty($entityCode, $dialogID, $messageFromUser, 'description');
        delete_message($bot_ID, $propertyValues['id_pre_message']);
        $last_bot_message_id = add_message('Добавь еще описание, фото, ссылку или нажми [b]ОТМЕНИТЬ[/b]:', '', $keyboard["FINNALY"]);
    }

    setEntityProperty($entityCode, $dialogID, $last_bot_message_id['result'], 'id_pre_message');
} else {
    delete_message($bot_ID, $propertyValues['id_pre_message']);
    add_message('Напиши [send=привет]привет[/send], чтобы начать', '', '');
}
