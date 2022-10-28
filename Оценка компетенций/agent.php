<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

$by = "id";
$order = "ASC";
$clinics_id = [344, 4551, 5073, 4967, 7676, 7389, 4959, 7371, 6531, 5377, 6381, 76777, 7678];

foreach ($clinics_id as $clinic_id) {
    $filter = array("ACTIVE" => "Y", "GROUPS_ID" => array(11), "UF_DEPARTMENT" => $clinic_id);
    $arParams = array("SELECT" => array('UF_DEPARTMENT'));

    $rsUsers = CUser::GetList($by, $order, $filter, $arParams);

    while ($user = $rsUsers->Fetch()) {
        $users_clinic[] = ['ID' => $user['ID'], 'FULL_NAME' => $user['NAME'] . ' ' . $user['LAST_NAME'], 'DEPARTMENT' => $user['UF_DEPARTMENT'][0]];
    }
}
print_r($users_clinic);
