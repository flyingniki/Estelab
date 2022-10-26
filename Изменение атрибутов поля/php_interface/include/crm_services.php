<?php

use Bitrix\Crm\Service;
use Bitrix\Main\DI;

if (\Bitrix\Main\Loader::includeModule('crm')) {
    define('ENTITY_TYPE_ID', 150);

    $container = new class extends Service\Container
    {
        public function getFactory(int $entityTypeId): ?Service\Factory
        {
            if (defined('ENTITY_TYPE_ID') && $entityTypeId === ENTITY_TYPE_ID) {
                $type = $this->getTypeByEntityTypeId($entityTypeId);
                $factory = new class($type) extends Service\Factory\Dynamic
                {
                    // here some additional logic
                    public function getUserFieldsInfo(): array
                    {
                        $fields = parent::getUserFieldsInfo();
                        $fields['UF_CRM_34_1666597146']['ATTRIBUTES'][] = \CCrmFieldInfoAttr::Immutable;

                        return $fields;
                    }
                };
                return $factory;
            }

            return parent::getFactory($entityTypeId);
        }
    };
    DI\ServiceLocator::getInstance()->addInstance('crm.service.container', $container);
}
