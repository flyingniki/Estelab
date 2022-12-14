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
    'ID',
);
$nPageTop  =  '';
$res = CCrmContact::GetList($arOrder, $arFilter, $arSelect, $nPageTop);

$workflowTemplateId = 2238;
$arErrorsTmp = array();
while ($arContact = $res->fetch()) {
    $contact_ids[] = $arContact['ID'];
}

// echo '<pre>';
// print_r($contact_ids);

$contacts_slice = array_slice($contact_ids, 3000, 1000);
// print_r($contacts_slice);

foreach ($contacts_slice as $contact) {
    CBPDocument::StartWorkflow(
        $workflowTemplateId,
        array('crm', 'CCrmDocumentContact', 'CONTACT_' . $contact),
        array(),
        $arErrorsTmp
    );
}
