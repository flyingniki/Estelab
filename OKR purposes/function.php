<?php

use Bitrix\Crm\Service;
use Bitrix\Crm\ItemIdentifier;
use Bitrix\Crm\Service\Container;

CModule::includeModule('crm');
$container = Service\Container::getInstance();

$Parent_Smart_Type_ID = 161; //смарт процесс ОКР цели
$parent_factory = $container->getFactory($Parent_Smart_Type_ID);

if ($parent_factory->isStagesEnabled()) {
    $items = $parent_factory->getItems();
    foreach ($items as $item) {
        $stageId = $item->getStageId(); //вывод статуса элемента
        $itemTitle = $item->getTitle();
        $itemId = $item->getId();
        $assignedById = $item->getAssignedById();
        //echo "<pre>";
        //print_r($stageId);
        //echo " - ";
        //print_r($itemTitle);
        //echo "</pre>";
        if (!(strpos($stageId, 'SUCCESS') || strpos($stageId, 'FAIL'))) {
            //echo "<pre>";
            //print_r($stageId);
            //echo " - ";
            //print_r($itemTitle);
            //echo "</pre>";
            $New_Smart_Type_ID = 174; //смарт процесс ОКР выравнивание
            $newItemTitle = $itemTitle;
            $new_factory = $container->getFactory($New_Smart_Type_ID);
            if (!$new_factory) {
                echo 'new_factory not found';
            }

            $data = [
                'TITLE' => $newItemTitle,
                'ASSIGNED_BY_ID' => $assignedById
            ];
            $new_item = $new_factory->createItem($data); //можем добавить пустой, далее заполнить минимальные поля. Многие поля сами подтянутся.

            $res = $new_item->save(); // обязательно сохраним

            $childId =  $new_item->getId();
            $parent = new ItemIdentifier(161, $itemId);
            $child = new ItemIdentifier(174, $childId);

            //$result = Container::getInstance()->getRelationManager()->bindItems($parent, $child);
            $result = $container->getRelationManager()->bindItems($parent, $child);

            break;
        }
    }
}
