<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Оценка качества работы с соцсетями");
?>

<style>
  .table_blur {
    background: #f5ffff;
    border-collapse: collapse;
    text-align: center;
    margin: auto;
    min-width: 75%;
  }

  .table_blur th {
    border-top: 1px solid #777777;
    border-bottom: 1px solid #777777;
    box-shadow: inset 0 1px 0 #999999, inset 0 -1px 0 #999999;
    background: linear-gradient(#9595b6, #5a567f);
    color: white;
    padding: 10px 15px;
    position: relative;
  }

  .table_blur th:after {
    content: "";
    display: block;
    position: absolute;
    left: 0;
    top: 25%;
    height: 25%;
    width: 100%;
    background: linear-gradient(rgba(255, 255, 255, 0),
        rgba(255, 255, 255, 0.08));
  }

  .table_blur tr:nth-child(odd) {
    background: #ebf3f9;
  }

  .table_blur th:first-child {
    border-left: 1px solid #777777;
    border-bottom: 1px solid #777777;
    box-shadow: inset 1px 1px 0 #999999, inset 0 -1px 0 #999999;
  }

  .table_blur th:last-child {
    border-right: 1px solid #777777;
    border-bottom: 1px solid #777777;
    box-shadow: inset -1px 1px 0 #999999, inset 0 -1px 0 #999999;
  }

  .table_blur td {
    border: 1px solid #e3eef7;
    padding: 10px 15px;
    position: relative;
    transition: all 0.5s ease;
    max-width: 100%;
  }

  .table_blur tbody:hover td {
    color: transparent;
    text-shadow: 0 0 3px #a09f9d;
  }

  .table_blur tbody:hover tr:hover td {
    color: #444444;
    text-shadow: none;
  }

  .date {
    color: #444444;
    font-weight: 600;
  }

  .table_inside {
    margin: auto;
    min-width: 100%;
  }

  .table_inside td,
  .result {
    border: 1px solid #777777;
    color: #777777;
    font-weight: 600;
  }

  .caption {
    font-size: 30px;
    margin: 20px;
    color: #777777;
  }
</style>

<?
$arOrder = array("CREATED_DATE" => "DESC");
$arFilter = array("IBLOCK_ID" => 443);
$arSelect = array("ID", "NAME", "PROPERTY_3502", "PROPERTY_3503", "PROPERTY_3504", "CREATED_DATE");
$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
  $arFields = $ob->GetFields();
  $employeeId = $arFields['PROPERTY_3502_VALUE'];
  $grade = $arFields['PROPERTY_3503_VALUE'];
  $allGrades[] = $arFields['PROPERTY_3503_VALUE'];
  $comment = $arFields['PROPERTY_3504_VALUE'];
  $dateCreate = $arFields['CREATED_DATE'];
  $arGrades[$dateCreate][$employeeId] = ['GRADE' => $grade, 'COMMENT' => $comment];
  $elements[] = $ob;
}
if (count($elements) > 12) {
  $arGrades = array_slice($arGrades, 0, 12);
}
foreach ($arGrades as $dateGrades) {
  foreach ($dateGrades as $gradeInfo) {
    $grades[] = $gradeInfo['GRADE'];
  }
}
$gradeSum = array_sum($grades);
if (count($elements) != 0 && count($elements) % 3 == 0) {
  $averageGrade = round($gradeSum / count($elements), 2);
}
?>

<body>
  <div class="table">
    <table class="table_blur">
      <caption class="caption">Оценка качества работы с соцсетями</caption>
      <thead>
        <tr>
          <th>Дата</th>
          <th>Е. Сосевич</th>
          <th>В. Пугачева</th>
          <th>А. Гончарова</th>
        </tr>
      </thead>
      <tbody>
        <? foreach ($arGrades as $dateCreate => $arGrade) { ?>
          <tr>
            <td class="date"><?= $dateCreate ?></td>
            <? foreach ($arGrade as $grade) { ?>
              <td>
                <table class="table_inside">
                  <tr>
                    <th>Оценка</th>
                    <th>Комментарий</th>
                  </tr>
                  <tr>
                    <td><?= $grade['GRADE'] ?></td>
                    <td><?= $grade['COMMENT'] ?></td>
                  </tr>
                </table>
              </td>
            <? } ?>
          </tr>
        <? } ?>
        <tr class="result">
          <td>Итог</td>
          <td colspan="3"><?= $averageGrade ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>