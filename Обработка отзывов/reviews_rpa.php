<?php

CModule::IncludeModule('iblock');
CModule::IncludeModule("bizproc");
\Bitrix\Main\Loader::includeModule('rpa');
$arSelect = array(
    "IBLOCK_ID", "ID", "NAME", "PROPERTY_1081", "PROPERTY_1373", "PROPERTY_1421",
    "PROPERTY_2149"
);
$arFilter = array("IBLOCK_ID" => 176, "PROPERTY_1373_ENUM_ID" => 1439);
$res = CIBlockElement::GetList(
    array('PROPERTY_2149' => 'DESK', 'NAME' => 'ASC'),
    $arFilter,
    false,
    array(),
    $arSelect
);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();

    $res1 = CIBlockElement::GetByID($arFields['PROPERTY_2149_VALUE']);
    $ar_res = $res1->fetch();
    if ($ar_res['NAME'] != $brand) {
        $brand = $ar_res['NAME'];
        $text = $text . "\n" . $brand . "\n";
        $i = 0;
    }

    $link = $arFields['PROPERTY_1421_VALUE'];

    $text_link = $arFields['NAME'];
    $text = $text . ++$i . ". [url=$link]" . $text_link . "[/url] \n";
}

// создаем элемент RPA
$typeId = 17;
$fieldName = 'UF_RPA_17_NAME';
$fieldList = 'UF_RPA_17_1666864440';
$item = Bitrix\Rpa\Driver::getInstance()->getType($typeId)->createItem();
$item->set($fieldName, "Отзыв от " . date('d-m-Y'));
$item->set($fieldList, $text);
$item->save();
