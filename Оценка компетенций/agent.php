<?php

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

$by = "id";
$order = "ASC";
$ar_ids = [
    'clinic' => [344, 4551, 5073, 4967, 7676, 7389, 4959, 7371, 6531, 5377, 6381, 76777, 7678],
    'finance' => [4982]
];
foreach ($ar_ids as $dep_name => $ids) {
    foreach ($ids as $id) {
        $filter = array("ACTIVE" => "Y", "GROUPS_ID" => array(11), "UF_DEPARTMENT" => $id);
        $arParams = array("SELECT" => array('UF_DEPARTMENT'));

        $rsUsers = CUser::GetList($by, $order, $filter, $arParams);

        while ($user = $rsUsers->Fetch()) {
            $users_info[$dep_name][] = ['ID' => $user['ID'], 'FULL_NAME' => $user['NAME'] . ' ' . $user['LAST_NAME'], 'DEPARTMENT' => $user['UF_DEPARTMENT'][0]];
        }
    }
}
print_r($users_info);
