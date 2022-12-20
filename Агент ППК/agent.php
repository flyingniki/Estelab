//<?
    CModule::IncludeModule('iblock');

    use Bitrix\Crm\Service;

    \Bitrix\Main\Loader::includeModule('crm');
    echo "<pre>";
    $entityTypeId = 133; // ППК смарт процесс
    $container = Service\Container::getInstance();
    $factory = $container->getFactory($entityTypeId); //Получить фабрику - далее работа через нее
    $categories = $factory->getItems(
        array(
            'select' => array('*'), 'filter' => ['!STAGE_ID' => '%FAIL%'],
            'order' => ['ID' => 'desc']
        )
    );

    foreach ($categories as $value) { //поэтому перебираем массив
        $GetData =  $value->GetData();
        $array_smart_process_ppk[$GetData['UF_CRM_33_1669558942']] = [
            'Стадия' => $GetData['STAGE_ID'],
            'ID смарт элемента' => $GetData['ID']
        ];
        if ($GetData['UF_CRM_33_1669558869'])
            $array_smart_process_ppk[$GetData['UF_CRM_33_1669558942']]['дата выполнения'] =
                $GetData['UF_CRM_33_1669558869']->toString();
    }

    $arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_3432", "PROPERTY_3433", "PROPERTY_3434");
    $arFilter = array(
        "IBLOCK_ID" => 440
    );
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);

    while ($ob = $res->fetch()) {
        $array_smart_process_ppk[$ob['ID']]['Определяемые показатели'] = trim($ob['PROPERTY_3432_VALUE']);
        $array_smart_process_ppk[$ob['ID']]['Название'] = $ob['NAME'];
        $array_smart_process_ppk[$ob['ID']]['Частота повторения'] = $ob['PROPERTY_3433_VALUE'];
        $array_smart_process_ppk[$ob['ID']]['Объект контроля'] = $ob['PROPERTY_3434_VALUE'];
        if (
            !$array_smart_process_ppk[$ob['ID']]['дата выполнения']
            and $array_smart_process_ppk[$ob['ID']]['id смарт процесса']
        ) {
            unset($array_smart_process_ppk[$ob['ID']]);
            continue;
        }

        switch ($array_smart_process_ppk[$ob['ID']]['Частота повторения']) {
            case 'по мере необходимости':
                unset($array_smart_process_ppk[$ob['ID']]);
                break;
            case 'ежедневно':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 1;
                break;
            case 'при каждой загрузке':
                unset($array_smart_process_ppk[$ob['ID']]);
                break;
            case 'каждый цикл стерилизации':
                unset($array_smart_process_ppk[$ob['ID']]);
                break;
            case '2 раза в неделю':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 3;
                break;
            case '1 раз в неделю':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 7;
                break;
            case '1 раз в месяц':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 30;
                break;
            case '2 раза в год':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 182;
                break;
            case '1 раз в год':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 365;
                break;
            case '1 раз в 5 лет':
                $array_smart_process_ppk[$ob['ID']]['Повторить через, д'] = 1825;
                break;
        }

        //if (strtotime($array_smart_process_ppk[$ob['ID']]['дата выполнения']) >= strtotime())


    }

    print_r($array_smart_process_ppk);
