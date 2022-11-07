<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Шкала развития компетенций");

use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");

$container = Service\Container::getInstance();

$Smart_Type_ID = 144;
$factory = $container->getFactory($Smart_Type_ID);
$items = $factory->getItems();

?>

<style>
    h1 {
        text-align: center;
        color: green;
    }

    .questions {
        text-align: center;
    }
</style>
<h1>Шкала развития компетенций</h1>
<div class="questions">
    <?
    foreach ($items as $item) {
        $imgId = $item->getUfCrm_76_1667825809();
        $img = CFile::ShowImage($imgId);
        ?><div class="question-item"><?= $img ?></div><?
    }
    ?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
