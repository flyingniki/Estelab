<?php

// жесткая привязка к id пользователя
$users = [32896, 74295, 500];

$element = new CIBlockElement;
foreach ($users as $user) {
    $properties = array();
    $properties[3502] = array("VALUE" => $user);
    $arFields = array(
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID"      => 443,
        "NAME"           => "Оценка от " . date('d.m.Y'),
        "ACTIVE"         => "Y",
        "PROPERTY_VALUES" => $properties
    );

    if ($element_id = $element->Add($arFields))
        echo "New ID: " . $element_id;
    else
        echo "Error: " . $element->LAST_ERROR;
    $workflowTemplateId = 2237;
    $arErrorsTmp = array();
    CBPDocument::StartWorkflow(
        $workflowTemplateId,
        array("lists", "Bitrix\Lists\BizprocDocumentLists", $element_id),
        array(),
        $arErrorsTmp
    );
}

CModule::IncludeModule('im');
foreach ($users as $user) {
    $arFields = array(
        "MESSAGE_TYPE" => "P",
        "TO_USER_ID" => $user,
        "FROM_USER_ID" => 610,
        "MESSAGE" => "Зайди на эту [url=https://corp.estelab.ru/company/otsenka-kachestva-raboty-s-sotssetyami/]страничку[/url] для анализа оценок качества"
    );
    CIMMessenger::Add($arFields);
}
