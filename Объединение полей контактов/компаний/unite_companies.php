<?php

CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
CModule::IncludeModule('crm');

$arOrder  = array('ID' => 'DESC');
$arFilter = array(
    'CHECK_PERMISSIONS' => 'N' // Данный ключ необходим для того чтобы получить всех пользоватей,
    // иначе, будет найден только если ответственным за него является тот,
    // под кем запускается скрипт в битриксе
);
$arSelect = array(
    'ID'
);

$res = CCrmCompany::GetList($arOrder, $arFilter, $arSelect);

$workflowTemplateId = 2239;
$arErrorsTmp = array();
while ($arCompany = $res->fetch()) {
    $company_ids[] = $arCompany['ID'];
}

$companies_slice = array_slice($company_ids, 1000, 1000);

foreach ($companies_slice as $company) {
    CBPDocument::StartWorkflow(
        $workflowTemplateId,
        array('crm', 'CCrmDocumentContact', 'CONTACT_' . $company),
        array(),
        $arErrorsTmp
    );
}
