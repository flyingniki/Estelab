<?php

use Bitrix\Crm\Service;
\Bitrix\Main\Loader::includeModule('crm');

$container = Service\Container::getInstance();
$Smart_Type_ID = 154;
$factory = $container->getFactory($Smart_Type_ID);
if (!$factory)
{
    echo 'factory not found' ;
}
$categories = $factory->getItems(array('select' => array('*')));
foreach ($categories as $value) {
    echo ($value->getTitle().' ');
}

//$item = $factory->createItem([]); //можем добавить пустой, далее заполнить минимальные поля. Многие поля сами подтянутся.

if ($item) {
    $item->setTitle('Test'); // добавим заголовок
}

//$res = $item->save(); // обязательно сохраним, иначе не будет магии.
//echo "<pre>";
//print_r($res);
//echo "</pre>";
 
