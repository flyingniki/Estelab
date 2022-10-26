<?php

//считаем разницу дат для сертификата
use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
$container = Service\Container::getInstance();

$Smart_Type_ID = 181;
$factory = $container->getFactory($Smart_Type_ID);
if (!$factory) {
    echo 'factory not found';
}

$user_fields = $factory->getUserFields();
//print_r($user_fields);
$items = $factory->getItems();

foreach ($items as $item) {
    $title = $item->getTitle();
    $date = $item->getUfCrm_64_1663837617();
    $company_id = $item->getUfCrm_64_1663837531();
    $contact_id = $item->getUfCrm_64_1663837556();
    //$contact_ids = \Bitrix\Crm\Binding\ContactCompanyTable::getCompanyContactIDs($company_id);
    //print_r($contact_ids);

    if (!empty($date)) {
        $date_string = $date->toString();
        $dateDiff = date_diff(new DateTime($date_string), new DateTime())->days;
        //echo $dateDiff;
        //$flag = 1;

        if ($dateDiff < 14) {
            //echo'Good!';
            //$flag = 0;
            //$contact = CCrmContact::GetByID($contact_id);
            $dbCont = CCrmFieldMulti::GetList(
                array('ID' => 'asc'), //сортировка
                array(
                    'ELEMENT_ID' => $contact_id,
                    'ENTITY_ID' => "CONTACT",
                    'TYPE_ID' => "EMAIL"
                )
            );
            while ($arCont = $dbCont->Fetch()) {
                $contact_email = $arCont["VALUE"];
            }
            //print_r($contact_email);
            //отправляем email функцией
            $to  = "<" . $contact_email . ">";

            $subject = "Компания Эстелаб";

            $message = 'Cертификат качества на <b>' . $title . '</b> истекает через <b>' . $dateDiff . '</b> дней. Просим Вас выслать действующий сертификат.';

            $headers  = "Content-type: text/html; charset=utf8 \r\n";
            $headers .= "From: От кого письмо <info@estelab.ru>\r\n";
            $headers .= "Reply-To: info@estelab.ru\r\n";

            mail($to, $subject, $message, $headers);
        }
    }
}
