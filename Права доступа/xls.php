<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("PHP to Excel");

include($_SERVER['DOCUMENT_ROOT'] . '/php2excel/simplexlsxgen-master/src/SimpleXLSXGen.php');
include($_SERVER['DOCUMENT_ROOT'] . '/crm/configs/perms/class.php');

$obj = new crmRules();
$perms = $obj->crmPerms();

//разбиваем массив в строки
foreach ($perms as $groupRoleGroupName => $arRolesNames) {
    foreach ($arRolesNames as $roleName => $arEntities) {
        foreach ($arEntities as $entityName => $arStages) {
            if (count($arStages) == 6) {
                foreach ($arStages as $operation => $perm) {
                    $result[] = [$groupRoleGroupName, $roleName, $entityName, '', $operation, $perm];
                }
            } else {
                $arStages = array_slice($arStages, 6);
                foreach ($arStages as $stageName => $stageRules) {
                    foreach ($stageRules as $operation => $perm) {
                        $result[] = [$groupRoleGroupName, $roleName, $entityName, $stageName, $operation, $perm];
                    }
                }
            }
        }
    }
}
$resultChunked = array_chunk($result, 6);
foreach ($resultChunked as $arRes) {
    $arResMerged[] = array_merge($arRes[0], $arRes[1], $arRes[2], $arRes[3], $arRes[4], $arRes[5]);
}
foreach ($arResMerged as $arRes) {
    $arXls[] = [$arRes[0], $arRes[1], $arRes[2], $arRes[3], $arRes[5], $arRes[11], $arRes[17], $arRes[23], $arRes[29], $arRes[35]];
}
array_unshift($arXls, ['<b>Группа/Отдел</b>', '<b>Роль</b>', '<b>Сущность</b>', '<b>Стадия</b>', '<b>READ</b>', '<b>ADD</b>', '<b>WRITE</b>', '<b>DELETE</b>', '<b>EXPORT</b>', '<b>IMPORT</b>']);
$xlsx = Shuchkin\SimpleXLSXGen::fromArray($arXls, 'Права доступа');
$title = 'Права доступа ' . date("m.d.Y H.i.s");
$xlsx->downloadAs($title . '.xlsx');
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
