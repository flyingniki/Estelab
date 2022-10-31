<?php

$arDepartments = [
    'store_clinic' => [4551, 7675, 5073, 4967, 7676, 4959, 7389, 4959, 7371, 6531, 5377, 6381],
    'marketing_doctor_training' => [5271, 7409, 7381],
    'logistics_cosmetics' => [4963, 5080],
    'sales_department_cosmetics_studio_equipment' => [376, 5080, 4966, 7342, 6981, 4914],
    'finance' => [4982],
    // 'logistics_senior_administrator' => [4963, ], //внести id ст администратора
    'it_finance_hr_scrum_master' => [4962, 4982, 6163, 346], //внести id скрам-мастера    
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
foreach ($mergedIds as $depName => $mergedId) {
    $resIds[$depName][] = array_unique($mergedId);
}
// print_r($resIds);
