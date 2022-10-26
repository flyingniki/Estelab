<?php

CModule::IncludeModule('iblock');
$yesterday = date("d.m.Y", strtotime("-1 day"));
$arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_1903", "PROPERTY_2044", "DATE_CREATE");

$arFilter = array("IBLOCK_ID" => 276, "PROPERTY_1903" => '{{Сотрудник > INT}}', "CHECK_PERMISSIONS" => "N");

$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, array(), $arSelect);
while ($arFields = $res->fetch()) {
    if ($yesterday == date("d.m.Y", strtotime($arFields['DATE_CREATE']))) {
        $yesterday_doings = $arFields['PROPERTY_2044_VALUE'];
        break;
    }
}

$this->SetVariable("yesterday_doings", $yesterday_doings);
