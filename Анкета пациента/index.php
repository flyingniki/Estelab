<?php

$client_id = intval(filter_input(INPUT_GET, 'client', FILTER_SANITIZE_SPECIAL_CHARS));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/select2.css">
    <title>Estelab Form</title>
</head>

<body>
    <main>
        <form class="decor" action="patient_form.php" method="post">
            <input type="hidden" name="client" value="<?= $client_id ?>">
            <div class="form-left-decoration"></div>
            <div class="form-right-decoration"></div>
            <div class="circle"></div>
            <div class="form-inner">
                <div class="header">
                    <h3>Анкета пациента</h3>
                    <img src="form-logo.png" alt="form-logo" class="logo">
                </div>

                <div class="form-1">
                    <input type="text" name="question[name]" placeholder="1. Имя">

                    <label for="question_2">2. Пол</label>
                    <select name="question[gender]" id="question_2" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <option value="М">М</option>
                        <option value="Ж">Ж</option>
                    </select>

                    <input type="text" name="question[age]" placeholder="3. Возраст">

                    <input type="text" name="question[height]" placeholder="4. Рост">

                    <input type="text" name="question[weight]" placeholder="5. Вес">

                    <label for="question_6">6. Тип кожи</label>
                    <select name="question[skin_type]" id="question_6" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <option value="Жирная">Жирная</option>
                        <option value="Сухая">Сухая</option>
                        <option value="Комбинированная">Комбинированная</option>
                    </select>

                    <label for="question_7">7. Чувствительность кожи к</label>
                    <select name="question[skin_sensitivity][]" id="question_7" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <option value="Ветру">Ветру</option>
                        <option value="Солнцу">Солнцу</option>
                        <option value="Холоду">Холоду</option>
                    </select>

                    <input type="text" name="question[last_tan]" placeholder="8. Дата последнего загара: чч.мм.гггг">
                    <label for="question_9">9. Время на солнце в детстве</label>
                    <select name="question[time_on_sun_in_childhood]" id="question_9" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <option value="Часто">Часто</option>
                        <option value="Редко">Редко</option>
                    </select>

                    <label for="question_10">10. Время на солнце в настоящее время</label>
                    <select name="question[time_on_sun_now]" id="question_10" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <option value="Часто">Часто</option>
                        <option value="Редко">Редко</option>
                    </select>

                    <button class="btn-1-forward">Далее</button>
                </div>

                <div class="form-2 hidden">
                    <label for="question_11">11. Наличие солнечных ожогов</label>
                    <select name="question[sunburns]" id="question_11" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <option value="Загараю быстро без солнечных ожогов">Загараю быстро без солнечных ожогов</option>
                        <option value="Загараю медленно иногда солнечные ожоги">Загараю медленно иногда солнечные ожоги</option>
                        <option value="Всегда солнечные ожоги, загар слабый, быстро исчезает">Всегда солнечные ожоги, загар слабый, быстро исчезает</option>
                    </select>

                    <label for="question_12">12. Наличие веснушек</label>
                    <select name="question[freckles][]" id="question_12" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <option value="Лицо">Лицо</option>
                        <option value="Декольте">Декольте</option>
                        <option value="Руки">Руки</option>
                        <option value="Спина">Спина</option>
                        <option value="Все тело">Все тело</option>
                    </select>

                    <label for="question_13">13. Склонность к образованию рубцов, поствосполительной гиперпигментации</label>
                    <select name="question[tendency_to_scarring]" id="question_13" class="dropdown-content js-example-basic-single">
                        <option value="" selected></option>
                        <option value="Любая травма заживает с рубцом">Любая травма заживает с рубцом</option>
                        <option value="Рубцы только после хирургических вмешательств">Рубцы только после хирургических вмешательств</option>
                        <option value="После любой травмы на долго остаются пигментные пятна">После любой травмы на долго остаются пигментные пятна</option>
                    </select>

                    <label for="question_14">14. Какие косметологические проблемы беспокоят лицо</label>
                    <select name="question[cosmetologic_problems_face][]" id="question_14" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <option value="Мимические морщины">Мимические морщины</option>
                        <option value="Потеря тонуса, тургора кожи">Потеря тонуса, тургора кожи</option>
                        <option value="Расширенные поры">Расширенные поры</option>
                        <option value="Пигментные пятна">Пигментные пятна</option>
                        <option value="Расширенные сосуды, покраснения лица">Расширенные сосуды, покраснения лица</option>
                        <option value="Потеря четкости овала лица">Потеря четкости овала лица</option>
                    </select>

                    <label for="question_15">15. Какие косметологические проблемы беспокоят шея, декольте</label>
                    <select name="question[cosmetologic_problems_neck_decollete][]" id="question_15" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <option value="Двойной подбородок">Двойной подбородок</option>
                        <option value="Дряблость">Дряблость</option>
                        <option value="Кольца Венеры (горизонтальные морщины шеи)">Кольца Венеры (горизонтальные морщины шеи)</option>
                        <option value="Тяжи платизмы">Тяжи платизмы</option>
                    </select>

                    <label for="question_16">16. Какие косметологические проблемы беспокоят тело:</label>
                    <select name="question[cosmetologic_problems_body][]" id="question_16" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <optgroup label="Локальные жировые отложения">
                            <option value="Живот">Живот</option>
                            <option value="Бедра">Бедра</option>
                            <option value="Руки">Руки</option>
                            <option value="Спина">Спина</option>
                        </optgroup>
                        <option value="Дряблость">Дряблость</option>
                        <option value="Целлюлит">Целлюлит</option>
                        <optgroup label="Нарушение мышечного тонуса">
                            <option value="Ягодицы">Ягодицы</option>
                            <option value="Бедра">Бедра</option>
                            <option value="Руки">Руки</option>
                        </optgroup>
                    </select>

                    <label for="question_17">17. Какие косметологические проблемы беспокоят кисти</label>
                    <select name="question[cosmetologic_problems_hands][]" id="question_17" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <option value="Пигментация">Пигментация</option>
                        <option value="Покраснения">Покраснения</option>
                        <option value="Визуализация сухожилий и вен">Визуализация сухожилий и вен</option>
                        <option value="Сухость">Сухость</option>
                        <option value="Дряблость">Дряблость</option>
                        <option value="Морщины">Морщины</option>
                    </select>

                    <input type="text" name="question[arterial_pressure]" placeholder="18. Артериальное давление рабочее, мм. рт. ст.">
                    <label for="question_19">19. Гипертоническая болезнь</label>
                    <select name="question[hypertonic_disease]" id="question_19" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Есть">Есть</option>
                    </select>

                    <label for="question_20">20. Сердечно-сосудистые заболевания</label>
                    <select name="question[cardiovascular_diseases]" id="question_20" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_cardiovascular]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <button class="btn-2-forward">Далее</button>
                    <button class="btn-2-back">Назад</button>
                </div>

                <div class="form-3 hidden">
                    <label for="question_21">21. Плохая свертываемость крови</label>
                    <select name="question[blood_clotting]" id="question_21" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_blood_clotting]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <label for="question_22">22. Заболевания аутоиммунного характера</label>
                    <select name="question[autoimmune_diseases]" id="question_22" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_autoimmune]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <label for="question_23">23. Онкологические заболевания</label>
                    <select name="question[oncological_diseases]" id="question_23" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_oncological]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <label for="question_24">24. Неврологические заболевания, эпилепсия</label>
                    <select name="question[neurological_diseases]" id="question_24" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_neurological]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <label for="question_25">25. Сахарный диабет</label>
                    <select name="question[diabetes]" id="question_25" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Компенсированный">Компенсированный</option>
                        <option value="Некомпенсированный">Некомпенсированный</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[blood_sugar]" class="dropdown-input" placeholder="Сахар крови">
                        <input type="text" name="question[glycated_hemoglobin]" class="dropdown-input" placeholder="Гликированный гемоглобин">
                    </div>

                    <label for="question_26">26. Заболевание печени и почек</label>
                    <select name="question[liver_kidney_disease]" id="question_26" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_liver_kidney_disease]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <label for="question_27">27. ВИЧ, Гепатит</label>
                    <select name="question[HIV_hepatitis]" id="question_27" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <label for="question_28">28. Наличие других хронический заболеваний</label>
                    <select name="question[chronic_diseases]" id="question_28" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[diagnosis_chronic diseases]" class="dropdown-input" placeholder="Диагноз">
                    </div>

                    <label for="question_29">29. Беременность, кормление грудью</label>
                    <select name="question[pregnancy_breastfeeding]" id="question_29" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <input type="text" name="question[number_of_pregnancies]" placeholder="30. Кол-во беременностей">
                    <button class="btn-3-forward">Далее</button>
                    <button class="btn-3-back">Назад</button>
                </div>

                <div class="form-4 hidden">
                    <input type="text" name="question[number_of_births]" placeholder="31. Кол-во родов">

                    <label for="question_32">32. Прием противозачаточных</label>
                    <select name="question[taking_contraceptives]" id="question_32" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[contraceptive_drug]" class="dropdown-input" placeholder="Препарат">
                    </div>

                    <label for="question_33">33. Менопауза в возрасте</label>
                    <select name="question[menopause]" id="question_33" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Укажите возраст менопаузы">Укажите возраст менопаузы</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[menopause_age]" class="dropdown-input" placeholder="Возраст менопаузы">
                    </div>

                    <label for="question_34">34. Заместительная гормональная терапия</label>
                    <select name="question[hormone_replacement_therapy]" id="question_34" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[hormone_replacement_therapy_drug]" class="dropdown-input" placeholder="Препарат">
                    </div>

                    <label for="question_35">35. Проводились ли пластические операции</label>
                    <select name="question[plastic_surgery]" id="question_35" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[plastic_surgery_name]" class="dropdown-input" placeholder="Название операции">
                    </div>

                    <label for="question_36">36. Наличие аллергических реакций</label>
                    <select name="question[allergic_reactions]" id="question_36" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[allergens]" class="dropdown-input" placeholder="Перечислите аллергены">
                    </div>

                    <label for="question_37">37. Холодовая аллергия, Криоглобулинемия</label>
                    <select name="question[cold_allergy]" id="question_37" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <label for="question_38">38. Анафилактический шок, отек Квинке, крапивница</label>
                    <select name="question[anaphylactic_shock]" id="question_38" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <input type="text" name="question[medications_taken]" placeholder="39. Принимаемые лекарственные препараты">

                    <label for="question_40">40. Прием антикоагулянтов, антибиотиков, гормонов</label>
                    <select name="question[anticoagulants]" id="question_40" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>
                    <div class="hidden">
                        <input type="text" name="question[anticoagulants_drugs]" class="dropdown-input" placeholder="Препараты">
                    </div>

                    <label for="question_41">41. Прием препаратов золота</label>
                    <select name="question[gold_preparations]" id="question_41" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <label for="question_42">42. Прием ретиноидов (роаккутан, акнекутан, сотрет, ретиноловый крем)</label>
                    <select name="question[taking_retinoids]" id="question_42" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <label for="question_43">43. Наличие имплантов и перманентных и полуперманентных филлеров (Артекол, Биогель) нитей (в т.ч. золотых)</label>
                    <select name="question[implants_fillers]" id="question_43" class="dropdown-content js-example-basic-single">
                        <option value="Нет" selected>Нет</option>
                        <option value="Да">Да</option>
                    </select>

                    <label for="question_44">44. Проводимые косметологические процедуры</label>
                    <select name="question[cosmetic procedures][]" id="question_44" class="dropdown-content js-example-basic-multiple" multiple>
                        
                        <option value="Ботулинотерапия">Ботулинотерапия</option>
                        <option value="Филлеры">Филлеры</option>
                        <option value="Лазерные процедуры">Лазерные процедуры</option>
                        <option value="Криолиполиз, SMAS-Лифтинг">Криолиполиз, SMAS-Лифтинг</option>
                        <option value="Биореовитализация">Биореовитализация</option>
                        <option value="RF-лифтинг">RF-лифтинг</option>
                        <option value="Фототерапия">Фототерапия</option>
                    </select>

                    <button class="btn-4-back">Назад</button>
                </div>

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