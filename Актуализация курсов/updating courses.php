<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");

$container = Service\Container::getInstance();

// реестр курсов обучения
$arOrderCourse = array("ID" => "ASC");
$arFilterCourse = array("IBLOCK_ID" => 425);
$arSelectCourse = array("ID", "NAME", "PROPERTY_3350", "PROPERTY_3353", "PROPERTY_3420");
$resCourse = CIBlockElement::GetList($arOrderCourse, $arFilterCourse, false, false, $arSelectCourse);

while ($obCourse = $resCourse->GetNextElement()) {
    $arFieldsCourse = $obCourse->GetFields();
    $element_id = $arFieldsCourse['ID'];
    $course_name = $arFieldsCourse['NAME'];
    $course_id = $arFieldsCourse['PROPERTY_3350_VALUE'];
    $course_link = 'https://corp.estelab.ru/services/learning/course.php?COURSE_ID=' . $course_id;
    $job_id = $arFieldsCourse['PROPERTY_3420_VALUE'];
    $job_ids[] = $arFieldsCourse['PROPERTY_3420_VALUE'];
    $courses[$job_id][] = ['ELEM_ID' => $element_id, 'NAME' => $course_name, 'COURSE_ID' => $course_id, 'LINK' => $course_link];
}

$job_ids = array_unique($job_ids);

foreach ($job_ids as $job_id) {
    // реестр мотивации
    $arOrderMotivation = array("ID" => "ASC");
    $arFilterMotivation = array("IBLOCK_ID" => 136, "PROPERTY_3355_VALUE" => $job_id, "PROPERTY_1327_VALUE" => "Активно");
    $arSelectMotivation = array("ID", "NAME", "PROPERTY_3355", "PROPERTY_1327");
    $resMotivation = CIBlockElement::GetList($arOrderMotivation, $arFilterMotivation, false, false, $arSelectMotivation);

    while ($obMotivation = $resMotivation->GetNextElement()) {
        $arFieldsMotivation = $obMotivation->GetFields();
        $motivation_name = $arFieldsMotivation['NAME'];
        $motivation_id = $arFieldsMotivation['ID'];

        // Ч2 путь сотрудника
        $Smart_Ch2_ID = 141;
        $factoryCh2 = $container->getFactory($Smart_Ch2_ID);
        $items = $factoryCh2->getItems();
        foreach ($items as $item) {
            $scheme_id = $item->getUfCrm_7_1627122629();
            $contact_id = $item->getUfCrm_7_1627123415();
            $department_id = $item->getUfCrm_7_1627124323();
            if ($motivation_id == $scheme_id) {
                foreach ($courses[$job_id] as $course) {
                    //создаем элемент СП "Актуализация курсов"
                    $Smart_Courses_ID = 135;
                    $factoryCourse = $container->getFactory($Smart_Courses_ID);

                    $title = $course['NAME'] . ' @ ' . date('d.m.Y');
                    $id = $course['ELEM_ID'];
                    $link = $course['LINK'];

                    $data = [
                        'TITLE' => $title,
                        'UF_CRM_65_1663863305' => $id,
                        // 'UF_CRM_65_1663863450' => $department_id,
                        // 'UF_CRM_65_1663863841' => $link,
                        'UF_CRM_65_1663866048' => $contact_id
                    ];
                    $item = $factoryCourse->createItem($data);

                    $res = $item->save();
                    $item_id = $res->getId();
                    // запускаем робота
                    $workflowTemplateId = 2094;
                    $arErrorsTmp = array();
                    CBPDocument::StartWorkflow(
                        $workflowTemplateId,
                        array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Courses_ID . "_" . $item_id),
                        array(),
                        $arErrorsTmp
                    );
                }
            }
        }
    }
}