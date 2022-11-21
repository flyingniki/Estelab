<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
$container = Service\Container::getInstance();

$Smart_Type_ID = 155;
$factory = $container->getFactory($Smart_Type_ID);
$items = $factory->getItems();
foreach ($items as $item) {
    $stageId = $item->getStageId();
    if ($stageId == 'DT155_28:2') {
        echo '<pre>';
        // print_r($stageId);
        $descriprion = $item->getUfCrm_15_1634311135();
        $start = $item->getUfCrm_15_1634304482849();
        $end = $item->getUfCrm_15_1634304505488();
        $department = $item->getUfCrm_15_1634306670();
        if ($department == '129383') {
            print_r($department);
            print_r($descriprion);
        }
    }
}
