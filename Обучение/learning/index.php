<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:learning.course.list", 
	"template1", 
	array(
		"SORBY" => "NAME",
		"SORORDER" => "ASC",
		"COURSE_DETAIL_TEMPLATE" => "course.php?COURSE_ID=#COURSE_ID#&INDEX=Y",
		"CHECK_PERMISSIONS" => "Y",
		"COURSES_PER_PAGE" => "150",
		"SET_TITLE" => "Y",
		"COMPONENT_TEMPLATE" => "template1"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>