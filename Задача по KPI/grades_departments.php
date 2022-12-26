<?php

$arDeps = [376, 4966, 7342, 6891, 5377, 4959, 4967, 4963, 4962, 4982, 6163, 5271];
foreach ($arDeps as $dep) {
    $arResultHead[] = getHeadSenior($dep, false);
}
print_r($arResultHead);
