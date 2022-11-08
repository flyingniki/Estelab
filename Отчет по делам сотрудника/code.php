<?php

CModule::IncludeModule("tasks");
$user_id = '{{Сотрудник > INT}}';
$query_sort = array("DEADLINE" => "ASC");
$query_filter = array("CHECK_PERMISSIONS" => "N");


$deadline_list = [];
$report_stopped_list = [];
$control_list = [];
$doing_list = [];

$stopped_count = 0;

$res = CTasks::GetList(
    $query_sort,
    array_merge($query_filter, array("RESPONSIBLE_ID" => $user_id, "STATUS" => array("-1", 6)))
);

$check_id = [];
while ($arTask = $res->GetNext()) {

    if (in_array($arTask['ID'], $check_id)) continue;
    $check_id[] = $arTask['ID'];
    $str = htmlspecialchars($arTask['TITLE'], ENT_QUOTES);
    $str = str_replace(chr(38), "", $str);
    $str = str_replace("#039;", "", $str);
    $str = str_replace("quot;", "", $str);
    $str = str_replace("amp;", "", $str);
    if ($arTask['STATUS'] == "-1") {
        $deadline_list[] = [
            'title' => $str,
            'url' => '/company/personal/user/' . $arTask['RESPONSIBLE_ID'] . '/tasks/task/view/' . $arTask['ID'] . '/',
            'date' => $arTask['DEADLINE'],
        ];
    } elseif ($arTask['STATUS'] == "6") {
        $report_stopped_list[] = [
            'title' => $str,
            'url' => '/company/personal/user/' . $arTask['RESPONSIBLE_ID'] . '/tasks/task/view/' . $arTask['ID'] . '/',
            'date' => $arTask['DEADLINE'],
        ];
        ++$stopped_count;
    }
}

if ($stopped_count) {
    $report_stopped = 'Отложенных задач [URL=https://corp.estelab.ru/services/lists/276/element/0/{{ID элемента}}/][b]' . $stopped_count . '[/b][/URL]';
}

// Подсчет текущих задач
$res1 = CTasks::GetList($query_sort, array_merge($query_filter, array("RESPONSIBLE_ID" => $user_id, "REAL_STATUS" => 2)));
$check_id = [];
while ($arTask = $res1->GetNext()) {
    if (in_array($arTask['ID'], $check_id)) continue;
    $check_id[] = $arTask['ID'];
    $str = htmlspecialchars($arTask['TITLE'], ENT_QUOTES);
    $str = str_replace(chr(38), "", $str);
    $str = str_replace("#039;", "", $str);
    $str = str_replace("quot;", "", $str);
    $str = str_replace("amp;", "", $str);
    $doing_list[] = [
        'title' => $str,
        'url' => '/company/personal/user/' . $arTask['RESPONSIBLE_ID'] . '/tasks/task/view/' . $arTask['ID'] . '/',
        'date' => $arTask['DEADLINE'],
    ];
}

// Подсчет задач которые необходимо проконтролировать

$res2 = CTasks::GetList(
    $query_sort,
    array_merge($query_filter, array("CREATED_BY" => $user_id, "STATUS" => 4))
);
$check_id = [];
while ($arTask = $res2->GetNext()) {
    if (in_array($arTask['ID'], $check_id)) continue;
    $check_id[] = $arTask['ID'];
    $str = htmlspecialchars($arTask['TITLE'], ENT_QUOTES);
    $str = str_replace(chr(38), "", $str);
    $str = str_replace("#039;", "", $str);
    $str = str_replace("quot;", "", $str);
    $str = str_replace("amp;", "", $str);
    $control_list[] = [
        'title' => $str,
        'url' => '/company/personal/user/' . $arTask['RESPONSIBLE_ID'] . '/tasks/task/view/' . $arTask['ID'] . '/',
        'date' => $arTask['DEADLINE'],
    ];
}
$report_bb = '';
$report_html = '';
$items = array();
$items = $control_list;
$title = 'Контроль выполения задач';

if (count($items) != 0) {
    $report_html .= '<b>' . $title . ':</b> <br>';
    $i = 0;
    foreach ($items as $item) {
        $report_html .= ++$i . '. <a href=' . $item['url'] . ' target="_blank">' . $item['title'] . '</a> ' . $item['date'] . ' <br>';
    }
    $report_bb .= '[b]' . $title . ':[/b] ' . "\n";
    $i = 0;
    foreach ($items as $item) {
        $report_bb .= ++$i . '.  [URL=corp.estelab.ru' . $item['url'] . ']' . $item['title'] . '[/URL] ' . $item['date'] . " \n";
    }
}

$items = $deadline_list;
$title = 'Просроченные задачи';
if (count($items) != 0) {
    $report_html .= '<b>' . $title . ':</b> <br>';
    $i = 0;
    foreach ($items as $item) {
        $report_html .= ++$i . '. <a href=' . $item['url'] . ' target="_blank">' . $item['title'] . '</a> ' . $item['date'] . ' <br>';
    }
    $report_bb .= '[b]' . $title . ':[/b] ' . "\n";
    $i = 0;
    foreach ($items as $item) {
        $report_bb .= ++$i . '.  [URL=corp.estelab.ru' . $item['url'] . ']' . $item['title'] . '[/URL] ' . $item['date'] . " \n";
    }
}

$items = $doing_list;
$title = 'Текущие задачи';
if (count($items) != 0) {
    $report_html .= '<b>' . $title . ':</b> <br>';
    $i = 0;
    foreach ($items as $item) {
        $report_html .= ++$i . '. <a href=' . $item['url'] . ' target="_blank">' . $item['title'] . '</a> ' . $item['date'] . ' <br>';
    }

    $report_bb .= '[b]' . $title . ':[/b] ' . "\n";
    $i = 0;
    foreach ($items as $item) {
        $report_bb .= ++$i . '.  [URL=corp.estelab.ru' . $item['url'] . ']' . $item['title'] . '[/URL] ' . $item['date'] . " \n";
    }
}

$items = $report_stopped_list;
$title = 'Отложенные задачи';
if (count($items) != 0) {
    $report_html .= '<b>' . $title . ':</b> <br>';
    $i = 0;
    foreach ($items as $item) {
        $report_html .= ++$i . '. <a href=' . $item['url'] . ' target="_blank">' . $item['title'] . '</a> ' . $item['date'] . ' <br>';
    }
}

if ($stopped_count) $report_bb .= $report_stopped;

$this->SetVariable("report", $report_bb);
$this->SetVariable("report_html", addslashes($report_html));

$this->SetVariable("report_doing_n", count($doing_list));
$this->SetVariable("report_control", count($control_list));
$this->SetVariable("report_stopped", $stopped_count);
$this->SetVariable("report_deadline", count($deadline_list));
