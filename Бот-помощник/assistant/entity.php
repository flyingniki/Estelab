<?php

$entity = restCommand('entity.add', array(
    "ENTITY" => $entityCode, // уникальное в пределах бота кодовое имя (используется для обращения к хранилищу
    'NAME' => $entityName, // человеческое название
    'ACCESS' => array(
        'U1' => 'W',
        'AU' => 'R',
    )
), $_REQUEST["auth"]);

$arProperty = [
    ['case', 'Причина', 'S'],
    ['dateBegin', 'Дата начала', 'S'],
    ['dateEnd', 'Дата окончания', 'S'],
    ['employee', 'Сотрудник', 'N'],
    ['type', 'Тип', 'S'],
    ['department', 'Подразделение', 'S'],
];

foreach ($arProperty as $property) {
    $entityProperty = restCommand('entity.item.property.add', array(
        "ENTITY" => $entityCode, // кодовое имя хранилища, в которое добавляется свойство
        'PROPERTY' => $property[0], // кодовое имя свойства
        'NAME' => $property[1], // человеческое название
        'TYPE' => $property[2], //Тип свойства (S - строка, N - число, F - файл)
    ), $_REQUEST["auth"]);
}