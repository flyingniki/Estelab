<?php

use Bitrix\Crm\Service;
use Bitrix\Crm\ItemIdentifier;
use Bitrix\Crm\Service\Container;

CModule::IncludeModule('crm');
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

//получить список требуемых документов с кол-вом дней для их изготовления
$Smart_Type_Ch6_ID = 169;
$factory_ch6 = $container->getFactory($Smart_Type_Ch6_ID);
if (!$factory_ch6) {
    echo 'factory not found';
}
$items_ch6 = $factory_ch6->getItems();
foreach ($items_ch6 as $item_ch6) {
    $item_ch6_title = $item_ch6->getTitle();
    $item_ch6_documentID = $item_ch6->getID();
    $item_ch6_documentIssueArray[$item_ch6_documentID] = $item_ch6->getUfCrm_46_1655476575();
}

//Получить список элементов Ч2 Путь сотрудников на всех стадиях 
//кроме элементов с воронок выбор кандидата, увольнение и трудоустройство - дочерний
$Smart_Type_Ch2_ID = 141;
$factory_ch2 = $container->getFactory($Smart_Type_Ch2_ID);
if (!$factory_ch2) {
    echo 'factory not found';
}
$items_ch2 = $factory_ch2->getItems();

foreach ($items_ch2 as $item_ch2) {
    $item_ch2_id = $item_ch2->getId();
    $item_ch2_category = $item_ch2->getCategoryId();
    if (!($item_ch2_category === 10 || $item_ch2_category === 11 || $item_ch2_category === 16)) {
        $item_ch2_employeeID = $item_ch2->getUfCrm_7_1627123415();
        $item_ch2_docsList = $item_ch2->getUfCrm_7_1655479716(); // сравниваем с $item_ch3_documentID
        $arNecessaryDocs_Ch2[$item_ch2_employeeID] = [$item_ch2_docsList, $item_ch2_id];
    }
}

//получаем список привязанных документов к элементу Ч3 - родитель
$Smart_Type_Ch3_ID = 148;
$factory_ch3 = $container->getFactory($Smart_Type_Ch3_ID);
if (!$factory_ch3) {
    echo 'factory not found';
}
$items_ch3 = $factory_ch3->getItems();
foreach ($items_ch3 as $item_ch3) {
    $item_ch3_id = $item_ch3->getId();
    $ch3_stageId = $item_ch3->getStageId(); //вывод статуса элемента
    if (!($ch3_stageId === 'DT148_14:SUCCESS' || $ch3_stageId === 'DT148_14:FAIL')) :
        $item_ch3_documentID = $item_ch3->getUfCrm_8_1655477679(); //сравниваем с тем, что есть в $item_ch2_docsList
        $item_ch3_employeeID = $item_ch3->getUfCrm_8_1628272435();
        $dateIssue = $item_ch3->getUfCrm_8_1627140409();
        if (!empty($dateIssue)) :
            $item_ch3_documentIssue = $dateIssue->toString();
            $dateDiff = date_diff(new DateTime($item_ch3_documentIssue), new DateTime())->days;
            //переводим в архив, если срок действия меньше указанного
            if ($dateDiff <= $item_ch6_documentIssueArray[$item_ch3_documentID] && $item_ch6_documentIssueArray[$item_ch3_documentID]) :
            $item_ch3->setStageId('DT148_14:SUCCESS');
            $result = $item_ch3->save();
            endif;
        endif;
        //массив со списком документов для каждого ID сотрудника
        $arCurrentDocs_Ch3[$item_ch3_employeeID][] = $item_ch3_documentID;
        $diff_docs_employees[$item_ch3_employeeID] = array_diff($arNecessaryDocs_Ch2[$item_ch3_employeeID][0], $arCurrentDocs_Ch3[$item_ch3_employeeID]);
        if (empty($diff_docs_employees[$item_ch3_employeeID])) {
            array_pop($diff_docs_employees);
        }
    endif;
}

//создаем элемент Ч3 исходя из недостающих и запускаем БП по сбору документов
foreach ($diff_docs_employees as $item_employeeID => $diff_docs) {
    foreach ($diff_docs as $docID) {
        $data = ['UF_CRM_8_1655477679' => $docID];
        $new_item_ch3 = $factory_ch3->createItem($data);
        $result = $new_item_ch3->save();
        $new_item_ch3_id = $result->getId();
        $child = new ItemIdentifier($Smart_Type_Ch3_ID, $new_item_ch3_id);
        $parent = new ItemIdentifier($Smart_Type_Ch2_ID, $arNecessaryDocs_Ch2[$item_employeeID][1]);
        $result = Container::getInstance()->getRelationManager()->bindItems($parent, $child);
        $workflowTemplateId = 1526;
        $arErrorsTmp = array();
        CBPDocument::StartWorkflow(
            $workflowTemplateId,
            array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Type_Ch3_ID . "_" . $new_item_ch3_id),
            array(),
            $arErrorsTmp
        );
    }
}

// echo '<pre>';
// echo 'Требуемые документы Ч2 (ID сотрудника => массив с ID документа): <br>';
// print_r($arNecessaryDocs_Ch2);
// echo '</pre>';

// echo '<pre>';
// echo 'Текущие документы Ч3 (ID сотрудника => массив с ID документа): <br>';
// print_r($arCurrentDocs_Ch3);
// echo '</pre>';

// echo '<pre>';
// echo 'Разница в документах Ч2 и Ч3 для каждого сотрудника: <br>';
// print_r($diff_docs);
// echo '</pre>';
