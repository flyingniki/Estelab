<?php

$doctor_id = intval(filter_input(INPUT_GET, 'doctor', FILTER_SANITIZE_SPECIAL_CHARS));

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/select2.css" />
  <title>Анкета врача</title>
</head>

<body>
  <main>
    <form class="decor" action="doctor_form.php" method="post">
      <input type="hidden" name="doctor" value="<?= $doctor_id ?>" />
      <div class="form-left-decoration"></div>
      <div class="form-right-decoration"></div>
      <div class="circle"></div>
      <div class="form-inner">
        <div class="header">
          <h3>Анкета врача</h3>
          <img src="form-logo.png" alt="form-logo" class="logo" />
        </div>

        <div class="form-1">

          <label for="question_1">1. Морфотип старения лица</label>
          <select name="question[morphotype]" id="question_1" class="dropdown-content js-example-basic-single">
            <option value="" title="1. Морфотип старения лица1" selected></option>
            <option value="Деформационный">Деформационный</option>
            <option value="Мелкоморщинистый">Мелкоморщинистый</option>
            <option value="Усталый">Усталый</option>
          </select>

          <label for="question_2">2. Фототип кожи по Фицпатрику</label>
          <select name="question[skin_photo_type]" id="question_2" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
          </select>

          <label for="question_3">3. Длина шеи</label>
          <input type="text" id="question_3" name="question[neck_length]">

          <label for="question_4">4. Обхват шеи</label>
          <input type="text" id="question_4" name="question[neck_width]">

          <label for="question_5">5. Тип телосложения</label>
          <select name="question[body_type]" id="question_5" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="Астеник">Астеник</option>
            <option value="Нормостеник">Нормостеник</option>
            <option value="Гиперстеник">Гиперстеник</option>
          </select>

          <label for="question_6">6. Тип фигуры</label>
          <select name="question[fit_type]" id="question_6" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="Гиноидная">Гиноидная</option>
            <option value="Андроидная">Андроидная</option>
            <option value="Смешанная">Смешанная</option>
          </select>

          <label for="question_7">7. Мерц НСГ</label>
          <select name="question[merts_nsg]" id="question_7" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>

          <label for="question_8">8. Мерц ММ</label>
          <select name="question[merts_mm]" id="question_8" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>

          <label for="question_9">9. Горизонт морщины шеи</label>
          <select name="question[neck_wrinkle]" id="question_9" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>

          <label for="question_10">10. Тяжи платизмы статика</label>
          <select name="question[platysma_statics]" id="question_10" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>

          <button class="btn-forward">Далее</button>
        </div>

        <div class="form-2 hidden">

          <label for="question_11">11. Тяжи платизмы динамика</label>
          <select name="question[platysma_dynamics]" id="question_11" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>

          <label for="question_12">12. Толщ подподбородочной складки (мм)</label>
          <input type="text" id="question_12" name="question[submental_crease]">

          <label for="question_13">13. Степень СЖО</label>
          <select name="question[sgo_level]" id="question_13" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
          </select>

          <label for="question_14">14. Оценка состояния кожи в области процедуры ДО (в баллах)</label>
          <select name="question[skin_condition_before]" id="question_14" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>

          <label for="question_15">15. Оценка состояния кожи в области процедуры ПОСЛЕ</label>
          <select name="question[skin_condition_after]" id="question_15" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>

          <label for="question_16">16. Оценка жировых отложений в области процедуры ДО</label>
          <select name="question[fat_before]" id="question_16" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>

          <label for="question_17">17. Оценка жировых отложений в области процедуры ПОСЛЕ</label>
          <select name="question[fat_after]" id="question_17" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>

          <label for="question_18">18. Оценка сосудистых/пигментных нарушений кожи в области процедуры ДО</label>
          <select name="question[pigment_disorders_before]" id="question_18" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>

          <label for="question_19">19. Оценка сосудистых/пигментных нарушений кожи в области процедуры ПОСЛЕ</label>
          <select name="question[pigment_disorders_after]" id="question_19" class="dropdown-content js-example-basic-single">
            <option value="" selected></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
          </select>

          <button class="btn-back">Назад</button>
        </div>

        <input type="submit" class="btn-submit hidden" value="Отправить" />
      </div>
    </form>
  </main>
  <div class="success hidden">
    <p>Анкета успешно отправлена</p>
  </div>
</body>
<script src="js/form.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/select2.min.js"></script>

</html>