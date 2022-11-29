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

$arSelect = array("ID", "NAME", "PROPERTY_3503", "PROPERTY_3504");
$arFilter = array("IBLOCK_ID" => 443);
$res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $grades[] = $arFields['PROPERTY_3503_VALUE'];
    $comments[] = $arFields['PROPERTY_3504_VALUE'];
    $elements[] = $ob;
}
if (count($elements) === 3) {
    $gradesSum = array_sum($grades);
    $averageGrade = $gradesSum / count($elements);
}
print_r($averageGrade);
print_r($comments);
