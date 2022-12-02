<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");

$container = Service\Container::getInstance();

$today = getdate();

if ($today['mday'] === 1) {
    $Smart_Type_ID = 136;
    $factory = $container->getFactory($Smart_Type_ID);

    $date = new DateTime('now');
    $date = $date->modify('last day of this month');
    $strDate = $date->format('d.m.Y');

    $data = [
        'UF_CRM_67_1667408909' => $strDate
    ];
    $item = $factory->createItem($data);

    $res = $item->save();
    $item_id = $res->getId();
    // запускаем робота
    $workflowTemplateId = 2206;
    $arErrorsTmp = array();
    CBPDocument::StartWorkflow(
        $workflowTemplateId,
        array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Type_ID . "_" . $item_id),
        array(),
        $arErrorsTmp
    );
}

$Smart_Type_ID = 171;
$factory = $container->getFactory($Smart_Type_ID);
if (!$factory) {
    echo 'factory not found';
}

$item = $factory->getItem();
$assigned = $item->get('ASSIGNED_BY_ID');
