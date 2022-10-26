<?php

foreach ($arResult['RELATION'] as $arRelation) :
    echo '<b>RelationName:</b> ' . $arRelation['NAME'] . '; ';
    foreach ($arResult['ROLE'] as $arRole) :
        if ($arRole['ID'] == $arRelation['ROLE_ID']) :
            $rolesID[] = $arRole['ID'];
            echo '<b>ID роли:</b> ' . $arRole['ID'] . '<br>';
            echo '<b>Название роли:</b> ' . $arRole['NAME'] . '<br><br>';
        endif;
    endforeach;
endforeach;

$rolesID = array_unique($rolesID);
print_r($rolesID);
