<?php

$client_id = intval(filter_input(INPUT_GET, 'client', FILTER_SANITIZE_SPECIAL_CHARS));

$checks_info = [
  1 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Измерение роста, веса, окружности талии, артериального давления.',
    'input_name' => 'height_weight_pressure'
  ],
  2 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Определение уровня гормонов: ФСГ, ЛГ, Эстрадиол, ТТГ, Т4 свободный, Пролактин, общий тестостерон, ГСПГ, АМГ.',
    'input_name' => 'hormones'
  ],
  3 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Забор крови для определения половых стероидных гормонов проводится на 2-3 день менструального цикла при наличии регулярных менструаций, либо в любой день при отсутствии регулярных менструаций.',
    'input_name' => 'blood_taking'
  ],
  4 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Клинический анализ крови.',
    'input_name' => 'blood_test'
  ],
  5 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Биохимический анализ крови: уровень глюкозы/гликированного гемоглобина в крови натощак; общий белок; билирубин, креатинин, уровень общего холестерина крови, ЛПНП-ХС, ЛПВП-ХС, триглицериды, АЛТ, АСТ.',
    'input_name' => 'blood_chemistry'
  ],
  6 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Коагулограмма, Д-димер.',
    'input_name' => 'coagulogram'
  ],
  7 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Ультразвуковое исследование органов малого таза трансвагинальным доступом.',
    'input_name' => 'ultrasonic'
  ],
  8 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Маммография в двух проекциях (после 40 лет), либо УЗИ молочных желез (до 40 лет, либо при необходимости применения дополнительного метода визуализации после 40 лет).',
    'input_name' => 'mammography'
  ],
  9 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Цитологическое исследование препарата шейки матки (золотой стандарт – жидкостная цитология с одновременным определением ВПЧ высокоонкогенного типа).',
    'input_name' => 'cytological_examination'
  ],
  10 => [
    'label_and_id' => 'flexCheck',
    'label_text' => 'Рентгеноденситометрия поясничного отдела позвоночника и проксимального отдела бедренной кости с использованием ДЭРА с целью оценки МПК (минеральной плотности костной ткани).',
    'input_name' => 'x-ray_densitometry'
  ],
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/styles.css" />
  <title>Анкета врача</title>
</head>

<body>
  <div class="container-fluid main">
    <form action="handler.php" class="form" method="post">
      <h1 class="text-center mb-5">Обследование:</h1>
      <input type="hidden" name="client" value="<?= $client_id ?>">

      <? foreach ($checks_info as $key => $info) { ?>
        <div class="form-check p-3 m-1">
          <input class="form-check-input" name="<?= "question[{$info['input_name']}]" ?>" type="checkbox" value="checked" id="<?= $info['label_and_id'] . $key ?>" />
          <label class="form-check-label" for="<?= $info['label_and_id'] . $key ?>">
            <?= $info['label_text'] ?>
          </label>
        </div>
      <? } ?>

      <div class="info mt-4 ms-3 me-3">
        <p class="info-text">
          Предварительно можно самостоятельно пройти
          <a href="https://www.sheffield.ac.uk/FRAX/tool.aspx?lang=rs">опросник</a>
          FRAX, который позволяет провести оценку индивидуальной 10-летней
          вероятности переломов
        </p>
        <p class="info-text">
          Необходимый комплекс обсуждается индивидуально на консультации.
        </p>
        <p class="info-text">
          Рекомендации по подготовке к сдаче анализов крови:
        </p>
        <p class="info-text">
          Показатели крови могут существенно меняться в течение дня, поэтому
          рекомендуется сдавать анализы в утренние часы. Для данного периода
          рассчитаны референсные интервалы многих лабораторных показателей.
          Это особенно важно для гормональных исследований.
        </p>
        <ul class="info-list">
          <li class="info-list-item">
            Все анализы крови делают до проведения рентгенографии, УЗИ и
            физиотерапевтических процедур.
          </li>
          <li class="info-list-item">За 2 часа до сдачи крови не курить.</li>
          <li class="info-list-item">
            За 2-3 суток не переедать, особенно жирную пищу, исключить
            алкоголь, интенсивные физические нагрузки, а также не посещать
            баню и сауну.
          </li>
          <li class="info-list-item">
            Взятие крови проводится строго натощак: от момента последнего
            приема пищи должно пройти не менее 8 часов. Разрешается пить воду
            (не минеральная, негазированная). Запрещается употреблять сок,
            чай, кофе.
          </li>
          <li class="info-list-item">
            За сутки до сдачи анализов исключить половые контакты,
            мастурбацию, стимуляцию сосков.
          </li>
          <li class="info-list-item">
            За два часа до сдачи крови исключить курение.
          </li>
        </ul>
      </div>

      <div class="text-center mt-4 mb-4">
        <input type="submit" class="btn btn-outline-primary" value="Отправить">
      </div>
    </form>

    <div class="success hidden">
      <p>Анкета успешно отправлена</p>
    </div>


  </div>

  <script src="js/bootstrap.min.js"></script>
  <script src="js/form.js"></script>
</body>

</html>