// <?
    \Bitrix\Main\Loader::includeModule('crm');

    use Bitrix\Crm\Service\Container;
    use Bitrix\Crm\Service;
    use Bitrix\Crm\ItemIdentifier;
    use Bitrix\Crm\Relation;

    $period = '{{Период}}';
    $text = "# OKR компании " . '{{Период > printable}}' . " на " . date("d.m.Y") . "\n";

    $Smart_OKR_purposes = 161;
    $factory_purposes = Container::getInstance()->getFactory($Smart_OKR_purposes);
    $items_purposes = $factory_purposes->getItems(array(
        'select' => array('*'),
        'filter' => array('UF_CRM_21_1650838358' => $period),
        'order' => array('UF_CRM_21_1638372363' => 'asc')
    ));
    foreach ($items_purposes as $item_purposes) {
        if (strpos($item_purposes->getStageId(), "FAIL")) continue;
        $okr_department = CIBlockSection::GetByID($item_purposes->get('UF_CRM_21_1638372363'))->GetNext()['NAME'];
        if ($okr_previous_department != $okr_department) {
            $text .= "## " . $okr_department . "\n";
            $okr_previous_department = $okr_department;
        }

        $okr_name = trim($item_purposes->get('UF_CRM_21_1638550318'));
        $okr_progress = $item_purposes->get('UF_CRM_21_1646848227');
        $okr_period = $item_purposes->get('UF_CRM_21_1650838358');
        $okr_id = $item_purposes->getId();
        $text .= "### ";
        if (!$okr_progress) $text .= $okr_name;
        else $text .= $okr_name . " = " . $okr_progress . "%";
        $text .= "\n";

        $parent = new ItemIdentifier($Smart_OKR_purposes, $okr_id);
        $childs = Container::getInstance()->getRelationManager()->getChildElements($parent);
        if ($childs) {
            foreach ($childs as $child_item) {
                $text .= "#### ";
                $KR_entityId = $child_item->getEntityId();
                $KR_entityTypeId = $child_item->getEntityTypeId();
                if ($KR_entityTypeId !== 173) continue;
                $factory_child = Container::getInstance()->getFactory($KR_entityTypeId);
                $item = $factory_child->getItem($KR_entityId);
                $KR_name = trim($item->get('UF_CRM_22_1638390586'));
                $KR_progress = $item->get('UF_CRM_22_1650834937');
                if ($KR_progress)    $text .= $KR_name . " = " . $KR_progress . "%";
                else   $text .= $KR_name;
                $text .= "\n";
            }
        }
    }
    $this->SetVariable("report", $text);
