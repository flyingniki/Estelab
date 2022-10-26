<?php

//работа с отзывами
use \Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

$Smart_Type_ID = 156;
$title = 'Отзыв от ' . date("m.d.Y H.i.s");
$factory = $container->getFactory($Smart_Type_ID);
if (!$factory) {
    echo 'factory not found';
}

$data = [
    'TITLE' => $title
];

$item = $factory->createItem($data); //можем добавить пустой, далее заполнить минимальные поля. Многие поля сами подтянутся.

$res = $item->save(); // обязательно сохраним

$item_id = $res->getId();
$workflowTemplateId = 2077;
$arErrorsTmp = array();
CBPDocument::StartWorkflow(
    $workflowTemplateId,
    array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Type_ID . "_" . $item_id),
    array(),
    $arErrorsTmp
);
