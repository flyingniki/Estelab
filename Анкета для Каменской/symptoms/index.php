<?php

$client_id = intval(filter_input(INPUT_GET, 'client', FILTER_SANITIZE_SPECIAL_CHARS));

$rows_info = [
  1 => ['name' => 'Быстрое или сильное сердцебиение', 'label_and_id' => 'flexCheck', 'input_name' => '1',],
  2 => ['name' => 'Чувство напряженности, нервозности', 'label_and_id' => 'flexCheck', 'input_name' => '2',],
  3 => ['name' => 'Нарушения сна', 'label_and_id' => 'flexCheck', 'input_name' => '3',],
  4 => ['name' => 'Возбудимость', 'label_and_id' => 'flexCheck', 'input_name' => '4',],
  5 => ['name' => 'Приступы тревоги, паники', 'label_and_id' => 'flexCheck', 'input_name' => '5',],
  6 => ['name' => 'Трудности в концентрации внимания', 'label_and_id' => 'flexCheck', 'input_name' => '6',],
  7 => ['name' => 'Чувство усталости или недостатка энергии', 'label_and_id' => 'flexCheck', 'input_name' => '7',],
  8 => ['name' => 'Потеря интереса ко многим вещам', 'label_and_id' => 'flexCheck', 'input_name' => '8',],
  9 => ['name' => 'Чувство недовольства или депрессия', 'label_and_id' => 'flexCheck', 'input_name' => '9',],
  10 => ['name' => 'Плаксивость', 'label_and_id' => 'flexCheck', 'input_name' => '10',],
  11 => ['name' => 'Раздражительность', 'label_and_id' => 'flexCheck', 'input_name' => '11',],
  12 => ['name' => 'Чувство головокружения или обморок', 'label_and_id' => 'flexCheck', 'input_name' => '12',],
  13 => ['name' => 'Давление или напряжение в голове, теле', 'label_and_id' => 'flexCheck', 'input_name' => '13',],
  14 => ['name' => 'Чувство онемения и дрожь в различных частях тела', 'label_and_id' => 'flexCheck', 'input_name' => '14',],
  15 => ['name' => 'Головные боли', 'label_and_id' => 'flexCheck', 'input_name' => '15',],
  16 => ['name' => 'Мышечные и суставные боли', 'label_and_id' => 'flexCheck', 'input_name' => '16',],
  17 => ['name' => 'Слабость в руках или ногах', 'label_and_id' => 'flexCheck', 'input_name' => '17',],
  18 => ['name' => 'Затрудненное дыхание', 'label_and_id' => 'flexCheck', 'input_name' => '18',],
  19 => ['name' => 'Приливы', 'label_and_id' => 'flexCheck', 'input_name' => '19',],
  20 => ['name' => 'Ночная потливость', 'label_and_id' => 'flexCheck', 'input_name' => '20',],
  21 => ['name' => 'Потеря интереса к сексу', 'label_and_id' => 'flexCheck', 'input_name' => '21',],
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
  <title>Симптомы пациента</title>
</head>

<body>
  <div class="container-fluid main">
    <form action="handler.php" class="form" method="post">
      <input type="hidden" name="client" value="<?= $client_id ?>">
      <div class="image row mb-3">
        <div class="col">
          <img src="images/body.png" alt="body" class="img" />
        </div>
      </div>

      <table class="table table-bordered table-sm table-responsive align-middle text-center">
        <thead>
          <tr class="align-middle thead">
            <th scope="col">Симптомы</th>
            <th scope="col">нет симптома</th>
            <th scope="col">слабое проявление симптома</th>
            <th scope="col">умеренное проявление симптома</th>
            <th scope="col">тяжелое проявление симптома</th>
          </tr>
        </thead>

        <tbody>
          <? foreach ($rows_info as $key => $row) { ?>
            <tr>
              <th scope="row" class="row-head"><?= $key . '.' . $row['name'] ?></th>
              <? for ($i = 0; $i < 4; $i++) { ?>
                <td>
                  <div class="form-check">
                    <label class="form-check-label" for="<?= $row['label_and_id'] . $key . '_' . $i ?>"></label>
                    <input class="form-check-input" name="<?= "question[{$row['input_name']}]" ?>" type="checkbox" value="<?= $i ?>" id="<?= $row['label_and_id'] . $key . '_' . $i ?>" />
                  </div>
                </td>
              <? } ?>
            </tr>
          <? } ?>
        </tbody>
      </table>

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