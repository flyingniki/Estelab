<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

$arDepartments = [
    'ИМ + Клиника' => [4551, 7675, 5073, 4967, 7676, 4959, 7389, 4959, 7371, 6531, 5377, 6381],
    'Маркетинг + Поддержка Гл.Врача + Тренинг' => [5271, 7409, 7381],
    'Логистика + Косметика и производство' => [4963, 5080],
    'Отдел продаж + Косметика + Студия + Оборудование' => [376, 5080, 4966, 7342, 6981, 4914],
    'Финансы' => [4982],
    // 'logistics_senior_administrator' => [4963, ], //внести id ст администратора
    'IT + Финансы + HR + Scrum master' => [4962, 4982, 6163, 346], //внести id скрам-мастера    
];

$arSelect = ['ID', 'NAME', 'LAST_NAME'];
foreach ($arDepartments as $depName => $depIds) {
    foreach ($depIds as $depId) {
        // echo '<pre>';
        $arEmployees = CIntranetUtils::GetDepartmentEmployees($depId, false, false, 'Y', $arSelect);

        while ($rsEmployees = $arEmployees->fetch()) {
            $usersIds[$depName][] = $rsEmployees['ID'];
            $usersIds[$depName] = array_filter($usersIds[$depName]);
        }
        $managerIds[$depName][] = CIntranetUtils::GetDepartmentManagerID($depId);
        $managerIds[$depName] = array_filter($managerIds[$depName]);
    }
}
//print_r($managerIds);
//print_r($usersIds);
$mergedIds = array_merge_recursive($managerIds, $usersIds);
foreach ($mergedIds as $depName => $arMergedId) {
    $arUniqueMergedId = array_unique($arMergedId);
    $resIds[$depName] = $arUniqueMergedId;
    $Smart_Type_ID = 168;
    $title = 'Опрос 360 @ ' . date('d-m-Y');

    $factory = $container->getFactory($Smart_Type_ID);

    $data = [
        'TITLE' => $title,
        'UF_CRM_74_1667223668' => $depName,
        'UF_CRM_74_1667295305' => $arUniqueMergedId
    ];
    $item = $factory->createItem($data);

    $res = $item->save();
    $item_id = $res->getId();
    $workflowTemplateId = 2201;
    $arErrorsTmp = array();
    CBPDocument::StartWorkflow(
        $workflowTemplateId,
        array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Type_ID . "_" . $item_id),
        array(),
        $arErrorsTmp
    );
}
print_r($resIds);
