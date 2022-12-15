<?php

// v1
$m_results = $DB->Query('SELECT * FROM `estelab_payroll_sheet_month` WHERE `user_id`="' . 79910 . '"');

$income_sum = 0;
$outcome_sum = 0;
$withheld_sum = 0;

while ($m_row = $m_results->Fetch()) {
    $results = $DB->Query('SELECT * FROM `estelab_payroll_sheet_doc` WHERE `parent_id`="' . $m_row['id'] . '" ORDER BY `date` ASC');

    while ($row = $results->Fetch()) {
        if ($row['type'] == 'income') {
            $income_sum +=  $row['value'];
        } elseif ($row['type'] == 'outcome') {
            if ($row['title'] == 'Удержано за покупку/процедуру в компании' || $row['title'] == 'прочие удержания') {
                $withheld_sum +=  $row['value'];
            } else {
                $outcome_sum +=  $row['value'];
            }
        }
    }
}
$debt = $income_sum - $outcome_sum - $withheld_sum;
// v2
$all_month_results = $DB->Query('SELECT * FROM `estelab_payroll_sheet_month` WHERE `user_id`="' . 79910 . '" AND `month` >= "2022-09-01" ');

$income_sum = 0;
$outcome_sum = 0;

while ($month_row = $all_month_results->Fetch()) {
    $doc_results = $DB->Query('SELECT * FROM `estelab_payroll_sheet_doc` WHERE `parent_id`="' . $month_row['id'] . '" ORDER BY `date` ASC');

    while ($doc_row = $doc_results->Fetch()) {

        if ($doc_row['type'] == 'income') {

            $income_sum +=  $doc_row['value'];
        } elseif ($doc_row['type'] == 'outcome') {
            $outcome_sum +=  $doc_row['value'];
        }
    }
}
$debt = $income_sum - $outcome_sum;
print($debt);
