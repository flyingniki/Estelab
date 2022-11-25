<?php

$entityAbsence = restCommand('entity.add', array(
    "ENTITY" => $entityAbsenceCode, // уникальное в пределах бота кодовое имя (используется для обращения к хранилищу
    'NAME' => $entityAbsenceName, // человеческое название
    'ACCESS' => array(
        'U1' => 'W',
        'AU' => 'R',
    )
), $_REQUEST["auth"]);

$entityBusinessTrip = restCommand('entity.add', array(
    "ENTITY" => $entityBusinessCode, // уникальное в пределах бота кодовое имя (используется для обращения к хранилищу
    'NAME' => $entityBusinessName, // человеческое название
    'ACCESS' => array(
        'U1' => 'W',
        'AU' => 'R',
    )
), $_REQUEST["auth"]);

$arProperties = [
    'absence' => [
        ['step', 'Шаг действия', 'N'],
        ['case', 'Причина', 'S'],
        ['dateBegin', 'Дата начала', 'S'],
        ['dateEnd', 'Дата окончания', 'S'],
        ['employee', 'Сотрудник', 'N'],
        ['type', 'Тип', 'S'],
        ['department', 'Подразделение', 'S'],
    ],
    'businessTrip' => [
        ['step', 'Шаг действия', 'N'],
        ['where', 'Куда летим', 'S'],
        ['departingTime', 'Дата/время отправления', 'S'],
        ['arrivingTime', 'Дата/время прибытия', 'S'],
        ['purpose', 'Цель командировки', 'S'],
        ['tickets', 'Билеты', 'F'],
        ['booking', 'Бронь гостиницы', 'F'],
        ['boardingTickets', 'Посадочные талоны', 'F'],
    ],
];

foreach ($arProperties['absence'] as $property) {
    $entityPropertiesAbsence = restCommand('entity.item.property.add', array(
        "ENTITY" => $entityAbsenceCode, // кодовое имя хранилища, в которое добавляется свойство
        'PROPERTY' => $property[0], // кодовое имя свойства
        'NAME' => $property[1], // человеческое название
        'TYPE' => $property[2], //Тип свойства (S - строка, N - число, F - файл)
    ), $_REQUEST["auth"]);
}

foreach ($arProperties['businessTrip'] as $property) {
    $entityPropertiesAbsence = restCommand('entity.item.property.add', array(
        "ENTITY" => $entityBusinessCode, // кодовое имя хранилища, в которое добавляется свойство
        'PROPERTY' => $property[0], // кодовое имя свойства
        'NAME' => $property[1], // человеческое название
        'TYPE' => $property[2], //Тип свойства (S - строка, N - число, F - файл)
    ), $_REQUEST["auth"]);
}
