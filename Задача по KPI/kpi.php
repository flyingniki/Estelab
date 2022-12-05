<?php

function getBitrixUserManager($user_id)
{
    $managers = array();
    $sections = CIntranetUtils::GetUserDepartments($user_id);
    foreach ($sections as $section) {
        $manager = CIntranetUtils::GetDepartmentManagerID($section);
        while (empty($manager)) {
            $res = CIBlockSection::GetByID($section);
            if ($sectionInfo = $res->GetNext()) {
                $manager = CIntranetUtils::GetDepartmentManagerID($section);
                $section = $sectionInfo['IBLOCK_SECTION_ID'];
                if ($section < 1) break;
            } else break;
        }
        if ($manager > 0) $managers[] = $manager;
    }
    foreach ($managers as $manager) {
        if ($manager != $user_id) {
            return $manager;
        } else return null;
    }
}

CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
CModule::IncludeModule("intranet");
CModule::IncludeModule("iblock");
CModule::IncludeModule('user');

$array_id_confirm = array();
$arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_1837", "PROPERTY_1327", "PROPERTY_3311");

$arFilter = array(
    "IBLOCK_ID" => 136,
    "CHECK_PERMISSIONS" => "N",
    "PROPERTY_1837_ENUM_ID" => 2039,
    "PROPERTY_1327_ENUM_ID" => 1390,
);
$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, array(), $arSelect);
while ($arFields = $res->fetch()) {
    // здесь создано условие для исключения элементов без файла KPI 
    if (!empty($arFields['PROPERTY_3311_VALUE'])) {
        array_push($array_id_confirm, $arFields['ID']);
    }
}

$cofirm_id_user = array();
$idUsers = array();

// массив групп исключений
$notSelect = [
    156, // Холдинг
    344, // ЭСТЕЛАБ
    5102, // Аутсорсинг
    6891, // Клининг
    6371, // Удаленные сотрудники маркетинга
]; //id групп которые необходимо исключить


// массив людей исключений
$notUser = [
    1,
    480
];

$arFullDepartments = [];

$filter = array(
    "UF_EMPLOYEE_MOT" => $array_id_confirm,
    "ACTIVE" => "Y",
);
$rsUsers = CUser::GetList($by = "id", $order = "asc", $filter);
while ($arUser = $rsUsers->fetch()) {
    array_push($idUsers, $arUser['ID']);
    array_push($cofirm_id_user, $arUser['ID']);
}

foreach ($idUsers as $idUser) {
    $rsUsers = CUser::GetList(array(), array(), array("ID" => $idUser), array("SELECT" => array("UF_DEPARTMENT")));

    while ($arUser = $rsUsers->Fetch()) {
        $idDepartments = $arUser['UF_DEPARTMENT'];
    }

    unset($rsUsers, $arUser);

    foreach ($idDepartments as $idDepartment) {
        if (array_key_exists($idDepartment, $arFullDepartments) || in_array($idDepartment, $notSelect)) {
            continue;
        }
    }

    $arFullDepartments[$idDepartment] = [];
    $arFullDepartments[$idDepartment]['ID'] = $idDepartment;

    $arResultHead = getHeadSenior($idDepartment, false);

    $arFullDepartments[$idDepartment]['HEAD'] = $arResultHead['HEAD'];
    $arFullDepartments[$idDepartment]['NAME'] = $arResultHead['NAME'];

    $rsUsers = CUser::GetList(array(), array(), array("UF_DEPARTMENT" => $idDepartment, "ACTIVE" => 'Y', "UF_IS_BOT" => !true), array("SELECT" => array("UF_DEPARTMENT", "UF_IS_BOT")));
    while ($arUser = $rsUsers->Fetch()) {
        if (!in_array($arUser['ID'], $notUser)) {
            // написать проверку на KPI
            // если ID подразделения в том элементе инфоблокка 136, где заполнено поле файл KPI, то создаем массив ниже
            $arFullDepartments[$idDepartment]['ITEMS'][$arUser['ID']]['ID'] = $arUser['ID'];
            $arFullDepartments[$idDepartment]['ITEMS'][$arUser['ID']]['NAME'] = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
        }
    }
}

// Руководители отдела
$headsNoshuffle = [];

foreach ($arFullDepartments as &$itemDerartment) {
    // Отбираем уникальных руководителей
    if (!in_array($itemDerartment['HEAD'], $headsNoshuffle) && !in_array($itemDerartment['HEAD'], $notUser)) {
        // написать проверку KPI
        $headsNoshuffle[$itemDerartment['HEAD']]['ID'] = $itemDerartment['HEAD'];
        $rsHead = CUser::GetList(array(), array(), array("ID" => $itemDerartment['HEAD']), array("SELECT" => array("UF_DEPARTMENT")))->Fetch();

        $headsNoshuffle[$itemDerartment['HEAD']]['NAME'] = $rsHead['NAME'] . ' ' . $rsHead['LAST_NAME'];
    }

    // Перемешиваем сотрудников
    if (isset($itemDerartment['ITEMS'])) {
        shuffle($itemDerartment['ITEMS']);
    } else {
        $itemDerartment['ITEMS'] = NULL;
    }
}

// Перемешаем руководителей
$heads = $headsNoshuffle;
shuffle($heads);

$keyUser;
$keyUserFriend;

$month = date("n", strtotime("-1 month"));
$year = date("Y", strtotime("-1 month"));

foreach ($cofirm_id_user as $user) {
    $idUser = $user;
    if (!in_array($idUser, $notUser)) {
        // Если id принадлежит руководителям
        if (array_key_exists($idUser, $headsNoshuffle)) {

            foreach ($heads as $key => $obj) {
                if ($obj['ID'] == $idUser) {
                    $keyUser = $key;
                }
            }

            if ($keyUser + 1 < count($heads)) {
                $keyUserFriend = $keyUser + 1;
            } else {
                $keyUserFriend = 0;
            }

            $usr1 = $heads[$keyUser];
            $usr2 = $heads[$keyUserFriend];
        } else {
            $rsUsers = CUser::GetList(array(), array(), array("ID" => $idUser), array("SELECT" => array("UF_DEPARTMENT")))->Fetch();

            if (isset($arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['ITEMS'])) {
                $countUsersDepartments = count($arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['ITEMS']);
            } else {
                $countUsersDepartments = false;
            }


            if ($countUsersDepartments === 1) {
                $usr1 = $arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['ITEMS'][0];
                $idHead = $arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['HEAD'];

                $rsUser = CUser::GetList(array(), array(), array("ID" => $idHead), array("SELECT" => array("UF_DEPARTMENT")))->Fetch();

                $usr2 = [
                    "ID" => $rsUser['ID'],
                    "NAME" => $rsUser['NAME'] . ' ' . $rsUser['LAST_NAME']
                ];
            } else {
                foreach ($arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['ITEMS'] as $key => $obj) {
                    if ($obj['ID'] == $idUser) {
                        $keyUser = $key;
                    }
                }


                if ($keyUser + 1 < $countUsersDepartments) {
                    $keyUserFriend = $keyUser + 1;
                } else {
                    $keyUserFriend = 0;
                }


                $usr1 = $arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['ITEMS'][$keyUser];
                $usr2 = $arFullDepartments[$rsUsers['UF_DEPARTMENT'][0]]['ITEMS'][$keyUserFriend];
            }
        }
    }

    $el = new CIBlockElement;
    $PROP = array();
    $PROP[1834] = 2032;
    $PROP[1827] = $user;
    $PROP[1828] = $month;
    $PROP[1829] = $year;


    if (isset($usr2['ID']) && !empty($usr2['ID'])) {
        $PROP[3378] = $usr2['ID'];
    }

    $arLoadProductArray = array(
        "IBLOCK_ID" => 270,
        "NAME"           => "Ежемесячное премия за выполнение показателей",
        "DETAIL_TEXT"           => "",
        "PREVIEW_TEXT_TYPE" => "html",
        "PREVIEW_TEXT"      => "",
        "PROPERTY_VALUES"    => $PROP
    );
    $ELEMENT_ID = $el->Add($arLoadProductArray);

    $id_elements_array[] = $ELEMENT_ID;

    $error = array();


    CBPDocument::StartWorkflow(
        639,
        array("lists", "BizprocDocument", $ELEMENT_ID),
        array(),
        $error
    );
}

$this->SetVariable("month", $month);
$this->SetVariable("year", $year);
$this->SetVariable("cofirm_id_user", $cofirm_id_user);
$this->SetVariable("id_elements", $id_elements_array);
$this->SetVariable("first_user", $first_user);
