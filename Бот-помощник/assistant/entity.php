<?php

$entityCode = 'bot_assistant';
$entityName = 'Assistant Bot';

$entity = restCommand('entity.add', array(
    "ENTITY" => $entityCode, // уникальное в пределах бота кодовое имя (используется для обращения к хранилищу
    'NAME' => $entityName, // человеческое название
    'ACCESS' => array(
        'U1' => 'W',
        'AU' => 'R',
    )
), $_REQUEST["auth"]);

$arProperty = [
    ['create_task', 'Шаг действия', 'Y'],
    ['delete', 'Удаление диалога', 'N'], //Тип свойства (S - строка, N - число, F - файл)
];

foreach ($arProperty as $property) {
    restCommand('entity.item.property.add', array(
        "ENTITY" => $entityCode, // кодовое имя хранилища, в которое добавляется свойство
        'PROPERTY' => $property[0], // кодовое имя свойства
        'NAME' => $property[1], // человеческое название
        'TYPE' => $property[2], // Тип
    ), $_REQUEST["auth"]);
}
