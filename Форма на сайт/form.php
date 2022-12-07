<?php

$user_id = $_GET['id'];

for ($i = 1; $i <= 43; $i++) {
    $question = 'question_' . $i;
    $data[$question] = filter_input(INPUT_POST, $question, FILTER_SANITIZE_SPECIAL_CHARS) ?? NULL;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <title>Estelab Form</title>
</head>

<body>
    <form class="decor" action="form.php" method="post">
        <div class="form-left-decoration"></div>
        <div class="form-right-decoration"></div>
        <div class="circle"></div>
        <div class="form-inner">
            <h3>Написать нам</h3>
            
            <div class="form-1">
                <input type="text" name="question_1" placeholder="Имя">
                <select name="question_2" id="question_2" class="dropdown-content">
                    <option value="">Пол</option>
                    <option value="М">М</option>
                    <option value="Ж">Ж</option>
                </select>
                <input type="text" name="question_3" placeholder="Возраст">
                <input type="text" name="question_4" placeholder="Рост">
                <input type="text" name="question_5" placeholder="Вес">
                <select name="question_6" id="question_6" class="dropdown-content">
                    <option value="">Тип кожи</option>
                    <option value="Жирная">Жирная</option>
                    <option value="Сухая">Сухая</option>
                    <option value="Комбинированная">Комбинированная</option>
                </select>
                <select name="question_7" id="question_7" class="dropdown-content">
                    <option value="">Чувствительность кожи к</option>
                    <option value="Ветру">Ветру</option>
                    <option value="Солнцу">Солнцу</option>
                    <option value="Холоду">Холоду</option>
                </select>
                <input type="text" name="question_8" placeholder="Дата последнего загара: чч.мм.гггг">
                <select name="question_9" id="question_9" class="dropdown-content">
                    <option value="">Время на солнце в детстве</option>
                    <option value="Часто">Часто</option>
                    <option value="Редко">Редко</option>
                </select>
                <select name="question_10" id="question_10" class="dropdown-content">
                    <option value="">Время на солнце в настоящее время</option>
                    <option value="Часто">Часто</option>
                    <option value="Редко">Редко</option>
                </select>
                <button class="btn-1">Далее</button>
            </div>

            <div class="form-2 hidden">
                <select name="question_11" id="question_11" class="dropdown-content">
                    <option value="">Наличие солнечных ожогов</option>
                    <option value="Загараю быстро без солнечных ожогов">Загараю быстро без солнечных ожогов</option>
                    <option value="Загараю медленно иногда солнечные ожоги">Загараю медленно иногда солнечные ожоги</option>
                    <option value="Всегда солнечные ожоги, загар слабый, быстро исчезает">Всегда солнечные ожоги, загар слабый, быстро исчезает</option>
                </select>
                <select name="question_12" id="question_12" class="dropdown-content">
                    <option value="">Наличие солнечных ожогов</option>
                    <option value="Загараю быстро без солнечных ожогов">Загараю быстро без солнечных ожогов</option>
                    <option value="Загараю медленно иногда солнечные ожоги">Загараю медленно иногда солнечные ожоги</option>
                    <option value="Всегда солнечные ожоги, загар слабый, быстро исчезает">Всегда солнечные ожоги, загар слабый, быстро исчезает</option>
                </select>
                <button class="btn-2">Далее</button>
            </div>
            <input type="submit" value="Отправить">
        </div>
    </form>
</body>
<script src="form.js"></script>

</html>

<?
$file_name = 'id_' . $user_id . 'txt';
$file = fopen($file_name, "w");
foreach ($data as $key => $value) {
    fwrite($file, $key . ' => ' . $data[$key] . "\n");
}
fclose($file);
?>