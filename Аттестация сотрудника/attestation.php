<?php

CModule::IncludeModule("learning");

$STUDENT_ID = '{{Сотрудник на портале > INT}}'; //$this->GetVariable("id_user");
$TEST_ID = [];

$TEST_ID = $this->GetVariable("test_array_id");
$array_passed_tests = [];
foreach ($TEST_ID as $value) {
    $res = CTestAttempt::GetList(
        array("DATE_END" => "DESC"),
        array(
            "TEST_ID" => $value,
            "STUDENT_ID" => $STUDENT_ID,
            "COMPLETED" => "Y",
            "CHECK_PERMISSIONS" => "N"
        )
    );
    $arAttempt = $res->GetNext();
    if ($arAttempt['DATE_END']) $array_passed_tests[] = $arAttempt['DATE_END'];
}

if ($array_passed_tests)
    $this->SetVariable("pos_pass_test_data", $array_passed_tests);
