<?php

$entityName = 'bot_assistant';

$entity = restCommand('entity.add', array(
    "ENTITY" => $entityCode,
    'NAME' => $entityName,
    'ACCESS' => array(
        'U1' => 'W',
        'AU' => 'R',
    )
), $_REQUEST["auth"]);

$arProperty = [
    ['create_task', 'Шаг действия', 'Y'],
    ['delete', 'Удаление диалога', 'N'],
];

foreach ($arProperty as $property) {
    restCommand('entity.item.property.add', array(
        "ENTITY" => $entityCode, // кодовое имя хранилища, в которое добавляется свойство
        'PROPERTY' => $property[0], // кодовое имя свойства
        'NAME' => $property[1], // человеческое название
        'TYPE' => $property[2], // Тип
    ), $_REQUEST["auth"]);
}
