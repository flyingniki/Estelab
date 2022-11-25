<?php

$entity = restCommand('entity.add', array(
    "ENTITY" => $entityCode, // уникальное в пределах бота кодовое имя (используется для обращения к хранилищу
    'NAME' => $entityName, // человеческое название
    'ACCESS' => array(
        'U1' => 'W',
        'AU' => 'R',
    )
), $_REQUEST["auth"]);

$arProperties = [
    'general' => [
        ['step', 'Шаг действия', 'N'],
        ['command', 'Команда', 'S'],
    ],
    'absence' => [
        ['case', 'Причина', 'S'],
        ['dateBegin', 'Дата начала', 'S'],
        ['dateEnd', 'Дата окончания', 'S'],
        ['employee', 'Сотрудник', 'N'],
        ['type', 'Тип', 'S'],
        ['department', 'Подразделение', 'S'],
    ],
    'businessTrip' => [
        ['where', 'Куда летим', 'S'],
        ['departingTime', 'Дата/время отправления', 'S'],
        ['arrivingTime', 'Дата/время прибытия', 'S'],
        ['purpose', 'Цель командировки', 'S'],
        ['tickets', 'Билеты', 'F'],
        ['booking', 'Бронь гостиницы', 'F'],
        ['boardingTickets', 'Посадочные талоны', 'F'],
    ],
];

foreach ($arProperties as $arProperty) {
    foreach ($arProperty as $property) {
        $entityProperties = restCommand('entity.item.property.add', array(
            "ENTITY" => $entityCode, // кодовое имя хранилища, в которое добавляется свойство
            'PROPERTY' => $property[0], // кодовое имя свойства
            'NAME' => $property[1], // человеческое название
            'TYPE' => $property[2], //Тип свойства (S - строка, N - число, F - файл)
        ), $_REQUEST["auth"]);
    }
}
