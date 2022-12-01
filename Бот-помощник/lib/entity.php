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
        ['date_begin', 'Дата начала', 'S'],
        ['date_end', 'Дата окончания', 'S'],
        ['employee', 'Сотрудник', 'N'],
        ['type', 'Тип', 'S'],
        ['department', 'Подразделение', 'S'],
    ],
    'business_trip' => [
        ['employee', 'Сотрудник', 'N'],
        ['where', 'Куда летим', 'S'],
        ['departing_time', 'Дата/время отправления', 'S'],
        ['arriving_time', 'Дата/время прибытия', 'S'],
        ['purpose', 'Цель командировки', 'S'],
        ['tickets', 'Билеты', 'F'],
        ['booking', 'Бронь гостиницы', 'F'],
        ['boarding_tickets', 'Посадочные талоны', 'F'],
    ],
    'courier_call' => [
        ['title', 'Название', 'S'],
        ['from', 'Откуда', 'S'],
        ['to', 'Куда', 'S'],
        ['sender_contact', 'Контакт отправителя', 'S'],
        ['sender_phone', 'Телефон отправителя', 'S'],
        ['recipient_contact', 'Контакт получателя', 'S'],
        ['recipient_phone', 'Телефон получателя', 'S'],
        ['pickup_date', 'Дата забора', 'S'],
        ['weight', 'Вес', 'N'],
        ['dimensions', 'Габариты', 'S'],
        ['procuration', 'Доверенность', 'S'],
        ['declared_value', 'Объявленная стоимость', 'N'],
        ['comment', 'Комментарий', 'S'],
    ],
    'internal_training' => [
        ['title', 'Название', 'S'],
        ['task_description', 'Описание проблемы/задачи', 'S'],
        ['relation', 'К чему относится', 'N'],
        ['employee', 'Сотрудник', 'N'],
        ['link', 'Ссылка', 'S'],
    ],
];

foreach ($arProperties as $actionName => $arProperty) {
    foreach ($arProperty as $property) {
        $entityProperties = restCommand('entity.item.property.add', array(
            "ENTITY" => $entityCode, // кодовое имя хранилища, в которое добавляется свойство
            'PROPERTY' => $actionName . '_' . $property[0], // кодовое имя свойства
            'NAME' => $property[1], // человеческое название
            'TYPE' => $property[2], //Тип свойства (S - строка, N - число, F - файл)
        ), $_REQUEST["auth"]);
    }
}
