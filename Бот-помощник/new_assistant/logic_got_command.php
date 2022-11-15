<?php

// check the event - authorize this event or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
    return false;
}

foreach ($_REQUEST['data']['COMMAND'] as $command) {

    if ($command['COMMAND'] == 'cancel') {
        delete_message($bot_ID, $propertyValues['id_pre_message']);
        add_message("Вы отменили заявку. Если нужно создать новую заявку напишите [send=привет]привет[/send]");
        clearSectionEntity($entityCode, $entityName);
        clearDialogs($entityCode, $entityName);
    } elseif ($command['COMMAND'] == 'create') {
        $photoArray = getPhoto($entityCode, $entityName, 'n');

        $fieldsBP = [
            'NAME' => $propertyValues['name_request'], // описание
            'CREATED_BY'    => $specialist,
            'PROPERTY_2861' => $photoArray, // файлы
            'PROPERTY_3166' => $propertyValues['according_to'], // с чем связано
            'PROPERTY_3333' => $propertyValues['type_request'], // тип обращения
            'PROPERTY_2474' => $propertyValues['urgent'], // срочночть
            'PROPERTY_2478' => $propertyValues['who_involve'], // касается
            'PROPERTY_2475' => $propertyValues['description'], // описание
            'PROPERTY_2487' => 2643, // стадия создана задача
        ];

        $businessItem = addNewBusinessProcess($fieldsBP);

        if (!empty($businessItem['error'])) {
            add_message('Неудалось создать Заявку[BR] Ошибка: ' . $businessItem['error_description'] . '[BR] Начни заново написав [send=привет]привет[/send].');
            return false;
        }

        $getTaskId = current_process($businessItem['result']);
        $taskId = array_shift($getTaskId['result'][0]['PROPERTY_2479']);

        restCommand('tasks.task.update', array(
            'taskId' => $taskId,
            'fields' => array(
                "UF_TASK_WEBDAV_FILES" => $photoArray,
            )
        ), $_REQUEST["auth"]);

        $message = "[url=https://corp.estelab.ru/company/personal/user/1/tasks/task/view/" . $taskId . "/]Заявка[/url] создана 👍 Спасибо за обращение.[BR]Если нужно создать новую заявку, напиши [send=привет]привет[/send]";
        delete_message($bot_ID, $propertyValues['id_pre_message']);

        add_message($message);

        clearSectionEntity($entityCode, $entityName);
        clearDialogs($entityCode, $entityName);
    }
}
