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

$data = [
    'TITLE' => $title
];

$item = $factory->createItem($data);

$res = $item->save();

$item_id = $res->getId();
$workflowTemplateId = 2071;
$arErrorsTmp = array();
CBPDocument::StartWorkflow(
    $workflowTemplateId,
    array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Type_ID . "_" . $item_id),
    array(),
    $arErrorsTmp
);
