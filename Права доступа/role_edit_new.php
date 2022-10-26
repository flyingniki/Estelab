 <?php

    $rolePerms = array(
        '' => 'Нет доступа',
        'A' => 'Свои',
        'D' => 'Свои + своего отдела',
        'F' => 'Свои + своего отдела + подотделов',
        'O' => 'Все открытые',
        'X' => 'Все',
    );

    foreach ($arResult['ENTITY'] as $entityType => $entityName) :
        echo '<b>Сущность:</b> ' . $entityType . ' => ' . $entityName . '<br>';

        if (in_array('READ', $arResult['ENTITY_PERMS'][$entityType])) :

            foreach ($arResult['ROLE_PERM'][$entityType] as $rolePermAtr => $rolePermName) :
                if (array_key_exists($rolePermAtr, $rolePerms)) :
                    $rolePermName = $rolePerms[$rolePermAtr];
                endif;
                echo ($rolePermAtr == $arResult['ROLE_PERMS'][$entityType]['READ']['-'] ? '<b>Атрибут для READ:</b> ' . $rolePermName . '<br>' : '');
            endforeach;
        endif;

        if (in_array('ADD', $arResult['ENTITY_PERMS'][$entityType])) :
            foreach ($arResult['ROLE_PERM'][$entityType] as $rolePermAtr => $rolePermName) :
                if (array_key_exists($rolePermAtr, $rolePerms)) :
                    $rolePermName = $rolePerms[$rolePermAtr];
                endif;
                echo ($rolePermAtr == $arResult['ROLE_PERMS'][$entityType]['ADD']['-'] ? '<b>Атрибут для  ADD:</b> ' . $rolePermName . '<br>' : '');
            endforeach;
        endif;

        if (in_array('WRITE', $arResult['ENTITY_PERMS'][$entityType])) :
            foreach ($arResult['ROLE_PERM'][$entityType] as $rolePermAtr => $rolePermName) :
                if (array_key_exists($rolePermAtr, $rolePerms)) :
                    $rolePermName = $rolePerms[$rolePermAtr];
                endif;
                echo ($rolePermAtr == $arResult['ROLE_PERMS'][$entityType]['WRITE']['-'] ? '<b>Атрибут для  WRITE:</b> ' . $rolePermName . '<br>' : '');
            endforeach;
        endif;

        if (in_array('DELETE', $arResult['ENTITY_PERMS'][$entityType])) :
            foreach ($arResult['ROLE_PERM'][$entityType] as $rolePermAtr => $rolePermName) :
                if (array_key_exists($rolePermAtr, $rolePerms)) :
                    $rolePermName = $rolePerms[$rolePermAtr];
                endif;
                echo ($rolePermAtr == $arResult['ROLE_PERMS'][$entityType]['DELETE']['-'] ? '<b>Атрибут для  DELETE:</b> ' . $rolePermName . '<br><br>' : '');
            endforeach;
        endif;
    endforeach;
