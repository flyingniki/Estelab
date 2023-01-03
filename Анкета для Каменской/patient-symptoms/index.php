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

$answers = ['нет симптома', 'слабое проявление симптома', 'умеренное проявление симптома', 'тяжелое проявление симптома'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/select2.css">
    <title>Симптомы пациента</title>
</head>

<body>
    <main>
        <form class="decor" action="handler.php" method="post">
            <input type="hidden" name="client" value="<?= $client_id ?>">
            <div class="form-left-decoration"></div>
            <div class="form-right-decoration"></div>
            <div class="circle"></div>
            <div class="form-inner">
                <div class="header">
                    <h3>Анкета пациента</h3>
                    <img src="form-logo.png" alt="form-logo" class="logo">
                </div>
                <? foreach ($rows_info as $key => $row) { ?>
                    <label for="<?= $row['label_and_id'] ?>"><?= $row['name'] ?></label>
                    <select name="<?= "question[{$row['input_name']}]" ?>" id="<?= $row['label_and_id'] ?>" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <? for ($i = 0; $i < 4; $i++) { ?>
                            <option value="<?= $i ?>"><?= $answers[$i] ?></option>
                        <? } ?>
                    </select>
                <? } ?>
                <input type="submit" class="btn-submit" value="Отправить">
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