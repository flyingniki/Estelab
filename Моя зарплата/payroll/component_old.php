<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$redirect_location = '/estelab/payroll/';
$is_mobile = false;

if(strpos($APPLICATION->GetCurDir(), '/mobile/') !== false) 
 {
	//require($_SERVER["DOCUMENT_ROOT"] . "/mobile/headers.php");
	$is_mobile = true;
	$redirect_location = '/mobile'.$redirect_location;
 }

// $APPLICATION->AddHeadString('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.28/css/uikit.min.css" />');
$APPLICATION->SetAdditionalCSS('/bitrix/components/estelab/payroll/lib/uikit.min.css', true);
$APPLICATION->AddHeadString('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>');
$APPLICATION->AddHeadString('<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.28/js/uikit.min.js"></script>');
$APPLICATION->AddHeadString('<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.28/js/uikit-icons.min.js"></script>');

$APPLICATION->SetTitle('Моя зарплата');

if (!$USER->IsAuthorized())
 {	
	ShowError(GetMessage("USER_AUTH_ERROR"));
	return;
 }
 
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/estelab/payroll/lib/Mustache/Autoloader.php');
Mustache_Autoloader::register();

$mustache = new Mustache_Engine;

if(isset($_POST) && !empty($_POST))
 {
	if(isset($_POST['pin']) && !empty($_POST['pin'])) 
	{
		$rsUser = $USER->GetByID($USER->GetID());
		$arUser = $rsUser->Fetch(); 
		
		if(($arUser['UF_PAYROLL_HASH'] != '') && password_verify($_POST['pin'], $arUser['UF_PAYROLL_HASH'])) 
		 {
			$_SESSION['estelab_payroll_access_granted'] = 'Y';
		 }
		else
		 {
			$_SESSION['estelab_payroll_access_denied'] = 'Y';
		 }
		
		header('Location: '.$redirect_location);
	}
 }	

// logout handler
if(isset($_GET) && !empty($_GET))
 {
	if(isset($_GET['payroll_logout'])) 
	 {
		unset($_SESSION['estelab_payroll_access_granted']);
		header('Location: '.$redirect_location);
	 }
 }	

// main 
if(!isset($_SESSION['estelab_payroll_access_granted']))
 {
	$rsUser = $USER->GetByID($USER->GetID());
	$arUser = $rsUser->Fetch(); 
	
	if($arUser['UF_PAYROLL_HASH'] == '')
	 {
?>
<div class="uk-alert-danger" uk-alert>
    <p class="uk-text-medium">Вашей учетной записи не присвоен код доступа. Обратитесь к Степану Сурнину</p>
</div>

<?
	 }
	else
	 {
?>
<div class="uk-alert-primary" uk-alert>
    <p class="uk-text-medium">Данные скрыты. Введите код доступа для просмотра</p>
</div>
<form method="post">
	<div class="uk-inline">
		<input name="pin" class="uk-input" type="text" autocomplete="off" placeholder="Код доступа">
		
	</div>
	<button type="submit" class="uk-button uk-button-primary">Войти</button>
</form>
<?
		if(isset($_SESSION['estelab_payroll_access_denied'])) 
		 {
			?>
			<div class="uk-alert-danger" uk-alert>
				<a class="uk-alert-close" uk-close></a>
				<p>Неправильный код</p>
			</div>
			<?
		 }
	 }
 }
else
 {
	unset($_SESSION['estelab_payroll_access_denied']);
	$month_names = array("", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
	//echo '<pre>'; print_r(); echo '</pre>';
	
	if(isset($_GET['m']))
	 {
		$date_arr = explode('-', $_GET['m']);
		$month = intval($date_arr[1]);
		if($month <= 0 || $month > 12) $month = date('n');
		
		$year = intval($date_arr[0]);
		if(!$year || $year < 2017 || $year > 2099) $year = date('Y');
	 }
	else 
	 {
		$month = date('n');
		$year = date('Y');
	 }
	


	function prepare_number($num)
	 {
		return number_format($num, 2, ',', ' ');
	 }

	function prepare_date($date)
	 {
		return date('d.m.Y', strtotime($date));
	 }
 
	function pad_month($month, $year)
	 {
		return $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT);
	 }
	 
	function get_next_month($month, $year)
	 {
		$new_m = intval($month) + 1;
		$new_y = intval($year);
		
		if($new_m == 13) 
		 { 
			$new_m = 1;
			$new_y++;
		 }
		
		return pad_month($new_m, $new_y);
	 }
	
	function get_prev_month($month, $year)
	 {
		$new_m = intval($month) - 1;
		$new_y = intval($year);
		
		if($new_m == 0) 
		 { 
			$new_m = 12;
			$new_y--;
		 }
		
		return pad_month($new_m, $new_y);
	 }
	
	$render_data = array(
		'have_data' => false, 		
		'cur_month' => pad_month($month, $year),
		'max_month' => date('Y-m', strtotime('+1 month')),
		'month_name' => $month_names[$month].' '.$year,
		'next_month' => get_next_month($month, $year),
		'prev_month' => get_prev_month($month, $year),
		'is_mobile' => $is_mobile,
	);
	
	$m_results = $DB->Query('SELECT * FROM `estelab_payroll_sheet_month` WHERE `user_id`="'.$USER->GetID().'" AND `month`="'.date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)).'"');
 
	if($m_row = $m_results->Fetch())
	 {
		//echo '<pre>'; print_r($m_row); echo '</pre>';
		
		$income_sum = 0;
		$outcome_sum = 0;
		
		$results = $DB->Query('SELECT * FROM `estelab_payroll_sheet_doc` WHERE `parent_id`="'.$m_row['id'].'" ORDER BY `date` ASC');
		
		while($row = $results->Fetch())
		 {
			
			$row['str_value'] = prepare_number($row['value']);
			
			$row['date'] = prepare_date($row['date']);
			
			if($row['type'] == 'income')
			{
				$row['indicator_text'] = 'начисление';
				$row['tr_class'] = 'success';
				$row['icon_name'] = 'plus';
				$row['income_value'] = $row['str_value'];
				
				$income_sum +=  $row['value'];
			}
			elseif($row['type'] == 'outcome')
			{
				$row['indicator_text'] = 'выплата';
				$row['tr_class'] = 'danger';
				$row['icon_name'] = 'minus';
				$row['outcome_value'] = $row['str_value'];
				
				$outcome_sum +=  $row['value'];
			}
			
			$row['extra_data'] = json_decode($row['extra_data'], true);
			//if(isset($_GET['dev'])) var_dump($row['extra_data']);
			
			if(!empty($row['extra_data'])) 
			{
				foreach($row['extra_data'] as $key => $erow)
				 {
					if($erow['value'] !== '') 
					{
						$row['extra_data'][$key]['value'] = prepare_number(floatval($erow['value']));
					}
					
					//echo '<pre>'; var_dump($erow); echo '</pre>';
				 }
			} 
			
			$row['have_extra_data'] = !empty($row['extra_data']);
			
			$row['container_id'] = 'extra_'.$row['id'].'_wrap';
			
			$render_data['rows'][] = $row;
			
			
			//echo '<pre>'; print_r($row); echo '</pre>';
		 }
		 
		$render_data['rows'][] = array(
			'indicator_text' => 'итого',
			'tr_class' => 'muted',
			'icon_name' => '',
			'income_value' => prepare_number($income_sum),
			'outcome_value' => prepare_number($outcome_sum),
		
		);
		 
		//if($income_sum != 0) 
		$render_data['income_sum'] = prepare_number($income_sum);
		//if($outcome_sum != 0) 
		$render_data['outcome_sum'] = prepare_number($outcome_sum);
		
		$render_data['initial_balance'] = prepare_number($m_row['initial_balance']);
		$render_data['final_balance'] = prepare_number($m_row['final_balance']);
		
		$render_data['initial_balance_negative'] = $m_row['initial_balance'] < 0;
		$render_data['final_balance_negative'] = $m_row['final_balance'] < 0;
		
		$render_data['export_date'] = date('d.m.Y H:i:s', strtotime($m_row['export_date']));
		
		$render_data['have_data'] = true;
		
	 }
	 
	$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views'),));
	$tpl = $mustache->loadTemplate('sheet');
	echo $tpl->render($render_data);

	
 }
?>
