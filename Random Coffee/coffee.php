<?php

//выбирает случайных пользователей, создает элемент смарт-процесса, запускает БП
use Bitrix\Crm\Service;

CModule::IncludeModule("crm");
CModule::IncludeModule("workflow");
CModule::IncludeModule("bizproc");
$container = Service\Container::getInstance();

$by = "id";
$order = "ASC";
$filter = array("ACTIVE" => "Y", "GROUPS_ID" => array(11));
$arParams = array("SELECT" => array('UF_DEPARTMENT'));

$rsUsers = CUser::GetList($by, $order, $filter, $arParams);

while ($user = $rsUsers->Fetch()) {
	$user_ids[] = $user['ID'];
	$dep_ids[]  = $user['UF_DEPARTMENT'][0];
	$users_info[] = ['ID' => $user['ID'], 'FULL_NAME' => $user['NAME'] . ' ' . $user['LAST_NAME'], 'DEPARTMENT' => $user['UF_DEPARTMENT'][0]];
}

$rand_users = array_rand($users_info, count($users_info));
shuffle($rand_users);

$exceptions = [
	'DEPARTMENTS' => [5241, 6891, 6371, 5102, 5003, 6882],
	'USERS_ID' => [1, 480, 57070, 36866]
];

for ($i = 0; $i < count($rand_users); $i += 2) {
	$users_1[$i] = $users_info[$rand_users[$i]];
	if (in_array($users_1[$i]['ID'], $exceptions['USERS_ID']) || in_array($users_1[$i]['DEPARTMENT'], $exceptions['DEPARTMENTS'])) {
		unset($users_1[$i]);
	}
}

for ($j = 1; $j < count($rand_users); $j += 2) {
	$users_2[$j] = $users_info[$rand_users[$j]];
	if (in_array($users_2[$j]['ID'], $exceptions['USERS_ID']) || in_array($users_2[$j]['DEPARTMENT'], $exceptions['DEPARTMENTS'])) {
		unset($users_2[$j]);
	}
}


for ($k = 0; $k < count($rand_users); $k++) {
	$pairs[$k]['USER_1'] = array_shift($users_1);
	$pairs[$k]['USER_2'] = array_shift($users_2);
	if (empty($pairs[$k]['USER_1']) || empty($pairs[$k]['USER_2']) || $pairs[$k]['USER_1']['DEPARTMENT'] === $pairs[$k]['USER_2']['DEPARTMENT']) {
		unset($pairs[$k]);
	}
}

foreach ($pairs as $pair) {
	$rand_user_1 = $pair['USER_1'];
	$rand_user_2 = $pair['USER_2'];

	$Smart_Type_ID = 154;
	$title = 'Кофе пьют ' . $rand_user_1['FULL_NAME'] . ' и ' . $rand_user_2['FULL_NAME'];

	$factory = $container->getFactory($Smart_Type_ID);
	if (!$factory) {
		echo 'factory not found';
	}

	$data = [
		'TITLE' => $title,
		'UF_CRM_30_1646398494' => $rand_user_1['ID'],
		'UF_CRM_30_1646398508' => $rand_user_2['ID']
	];
	$item = $factory->createItem($data); //можем добавить пустой, далее заполнить минимальные поля. Многие поля сами подтянутся.



	$res = $item->save(); // обязательно сохраним
	$item_id = $res->getId();
	$workflowTemplateId = 1748;
	$arErrorsTmp = array();
	CBPDocument::StartWorkflow(
		$workflowTemplateId,
		array("crm", "Bitrix\Crm\Integration\BizProc\Document\Dynamic", "DYNAMIC_" . $Smart_Type_ID . "_" . $item_id),
		array(),
		$arErrorsTmp
	);
}