<?php

CModule::IncludeModule('im');
$arFields = array(
    "MESSAGE_TYPE" => "P",
    "TO_USER_ID" => 62263,
    "FROM_USER_ID" => 610,
    "MESSAGE" => "Готов [url=https://corp.estelab.ru/company/otsenka-kompetentsiy/analytics.php]отчет[/url] по оценке компетенций Модель 360"
);

CIMMessenger::Add($arFields);
