<?php
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (CModule::IncludeModule('crm')) {
    $arFilter = array(
        "STAGE_ID" => "C4:WON",
        "CHECK_PERMISSIONS" => "N"
    );
    $arSelect = array(
        "ID",
        "NAME",
        "CONTACT_ID",
        "OPPORTUNITY",
        "CURRENCY_ID",
        "DATE_CREATE",
        "UF_CRM_1661951124305", //Дата платежа
        "UF_CRM_1661172496031", // Куда потратятся деньги,
        "UF_CRM_1661172383" // Детский дом
    );

    $res = CCrmDeal::GetList(array('UF_CRM_1661951124305' => 'desc'), $arFilter, $arSelect);
    while ($row = $res->Fetch()) {
        if (isset($row['CONTACT_ID']) && !empty($row['CONTACT_ID'])) {
            $contactId = $row['CONTACT_ID'];

            $dbCont = CCrmFieldMulti::GetList(
                array('ID' => 'asc'),
                array(
                    'ELEMENT_ID' => $contactId,
                    'ENTITY_ID' => "CONTACT",
                    'TYPE_ID' => "PHONE"
                )
            );

            if ($arCont = $dbCont->Fetch()) {
                $phone = preg_replace('~[+\d-](?=[\d-]{4})~', '*', translate_phone($arCont['VALUE']));
            }
        }

        if (isset($row['UF_CRM_1661172383']) && !empty($row['UF_CRM_1661172383'])) {
            $id_element = $row['UF_CRM_1661172383'];

            $arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_1459");
            $arFilter = array("IBLOCK_ID" => 237, "ID" => $id_element);
            $resO = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
            while ($ob = $resO->GetNextElement()) {
                $arFields = $ob->GetFields();
                $companyId = $arFields['PROPERTY_1459_VALUE'];
            }

            $dbResMultiFields = CCrmCompany::GetList(array(), array('ID' => $companyId, "CHECK_PERMISSIONS" => "N"), array(), array());
            while ($arMultiFields = $dbResMultiFields->Fetch()) {
                $nameTarget = $arMultiFields['TITLE'];
            }
        }
?>
        <style>
            .report-item h4,
            .report-item p {
                display: inline-block;
            }

            .item {
                line-height: 0.2px;
            }

            .report-item:nth-of-type(odd) {
                background-color: rgba(106, 184, 238, 0.3);
            }
        </style>
        <div class="report">
            <div class="report-item">
                <div class="report-item-phone item">
                    <h4>Последние цифры номера телефона:</h4>
                    <p><?= $phone ?></p>
                </div>
                <div class="report-item-date item">
                    <h4>Дата и время:</h4>
                    <p><?= $DB->FormatDate($row['UF_CRM_1661951124305'], "DD.MM.YYYY", "DD.MM.YYYY"); ?></p>
                </div>
                <div class="report-item-sum item">
                    <h4>Сумма:</h4>
                    <p><?= CurrencyFormat($row['OPPORTUNITY'], $row['CURRENCY_ID']) ?></p>
                </div>
                <div class="report-item-orphanage item">
                    <h4>Детский дом:</h4>
                    <p><?= $nameTarget ?></p>
                </div>
                <div class="implementation item">
                    <h4>Реализация помощи:</h4>
                    <p><?= $row['UF_CRM_1661172496031'] ?></p>
                </div>
            </div>
        </div>
<? }
}
?>
</table>

<style>
    table {
        border: 1px solid grey;
        border-collapse: collapse;
    }

    th {
        padding: 10px;
        border: 1px solid grey;
        background-color: rgba(106, 184, 238, 0.3);
    }

    td {
        padding: 10px;
        border: 1px solid grey;
    }


    tr:nth-child(odd) {
        background-color: rgba(106, 184, 238, 0.3);
    }
</style>

<?
function translate_phone($phone)
{
    $result = preg_replace('/[^0-9,.]/', '', $phone);
    if (strlen($result) > 10) {
        $result = substr($result, (strlen($result) - 10));
    }
    return $result;
}
?>