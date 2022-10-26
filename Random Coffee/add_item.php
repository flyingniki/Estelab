<?php

//выбирает случайных пользователей, создает элемент смарт-процесса, запускает БП
use Bitrix\Crm\Service;
\Bitrix\Main\Loader::includeModule('crm');

$container = Service\Container::getInstance();

$by="id";
$order="ASC";
$filter=Array("ACTIVE"=>"Y", "GROUPS_ID" => Array(11));
$arParams = Array("SELECT" => Array('UF_DEPARTMENT'));

$rsUsers = CUser::GetList($by, $order, $filter, $arParams);

while($user = $rsUsers->Fetch()) {
	//print_r('ID польз: '.$user['ID']);
	//print_r('-----------------');
	//print_r('ID отдела: '.$user['UF_DEPARTMENT'][0]);
	//echo '<br>';
	$user_ids[] = $user['ID'];
	$dep_ids[]  = $user['UF_DEPARTMENT'][0];
	$users_info[] = ['ID' => $user['ID'], 'FULL_NAME' => $user['NAME'].' '.$user['LAST_NAME'], 'DEPARTMENT' => $user['UF_DEPARTMENT'][0]];
}
//echo 'User Info';
//print_r($users_info);

$rand_users = array_rand($users_info, 2);
//echo 'Rand Users';
//print_r($rand_users);
$rand_user_1 = $users_info[$rand_users[0]];
$rand_user_2 = $users_info[$rand_users[1]];
if ($rand_user_1['ID'] !== $rand_user_2['ID'] && $rand_user_1['DEPARTMENT'] !== $rand_user_2['DEPARTMENT']) {

	echo 'User1 ';
	print_r ($rand_user_1);
	
	echo 'User2 ';
	print_r ($rand_user_2);
	
	
	$Smart_Type_ID = 154;
	$title = 'Кофе пьют '.$rand_user_1['FULL_NAME'].' и '.$rand_user_2['FULL_NAME'];
	
	$factory = $container->getFactory($Smart_Type_ID);
	if (!$factory)
	{
		echo 'factory not found' ;
	}

	$data = [
		'TITLE' => $title,
		'UF_CRM_30_1646398494' => $rand_user_1['ID'],
		'UF_CRM_30_1646398508' => $rand_user_2['ID']
		];
	$item = $factory->createItem($data); //можем добавить пустой, далее заполнить минимальные поля. Многие поля сами подтянутся.

	
	
	$res = $item->save(); // обязательно сохраним, иначе не будет магии.
	//echo "<pre>";
	//print_r($res);
	//echo "</pre>";
	$item_id = $res->getId();
	echo 'ID элемента смарт-процесса: '.$item_id;
}
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");

$workflowTemplateId = 1748;
$arErrorsTmp = array();
CBPDocument::StartWorkflow( 
$workflowTemplateId, 
	array("crm","Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_".$Smart_Type_ID."_".$item_id),
array(),
$arErrorsTmp
);
//echo 'Ошибки: ';
//print_r($arErrorsTmp);