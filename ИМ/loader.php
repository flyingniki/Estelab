<?php

if($_FILES['userfile']){
	if ($_FILES['userfile']['tmp_name']){
		echo "ID - ARTIKUL - PRICE - RESULT<br>";
		$row = 1;
		$prop_artic = 275; // ID свойства товара, отвечающего за артикул, у вас будет другое число, см. в настройках инфоблока
			if (($handle = fopen($_FILES['userfile']['tmp_name'], "r")) !== FALSE){
				while(($data = fgetcsv($handle, 4000, ";")) !== FALSE){
 
					$num = count($data);
					$row++;
					$artikul = trim($data[0]);				// артикул из файла
					$price = trim($data[1]);
					$price = str_replace(" ", "", $price);
					$price = str_replace(",", ".", $price);	// цена из файла, убираем пробелы, а копейки отделяем через точку
 
					$iblock_id = $_POST['find_section_section'];
 
					$res_hl = $DB-&gt;Query("SELECT * FROM b_iblock_element_property WHERE IBLOCK_PROPERTY_ID=".$prop_artic." AND VALUE='".$artikul."'");	// для декорико
 
					$result_db = $res_hl-&gt;result-&gt;num_rows;
 
					if($result_db&gt;0){
						while ($this_val = $res_hl-&gt;Fetch())
						{
							$ELEMENT_ID = $this_val["IBLOCK_ELEMENT_ID"];
						}
 
						// Ищем цены в базе
						$res_chek = $DB-&gt;Query("SELECT * FROM b_catalog_price WHERE PRODUCT_ID=".$ELEMENT_ID);
						if($res_chek-&gt;result-&gt;num_rows == 0){
							// Добавляем если нет в базе
							$arFields = Array(
								"PRODUCT_ID" =&gt; $ELEMENT_ID,
								"CATALOG_GROUP_ID" =&gt; 1,
								"PRICE" =&gt; $price,
								"CURRENCY" =&gt; "RUB"
							);
							CPrice::Add($arFields);
						} else {
							if($res_chek-&gt;result-&gt;num_rows == 2 || $res_chek-&gt;result-&gt;num_rows == 3 || $res_chek-&gt;result-&gt;num_rows == 4){
							// если цены задвоились, то их сначала удаляем старые данные, потом добавляем/обновляем цены
								$res_del = $DB-&gt;Query("SELECT MIN(ID) FROM b_catalog_price WHERE PRODUCT_ID=".$ELEMENT_ID."");
								while ($pr = $res_del -&gt;Fetch())
								{
									$min_id = (int)$pr["MIN(ID)"];
									$DB-&gt;Query("DELETE FROM b_catalog_price WHERE ID=".$min_id."");
								}
							}
							// Изменяем цены, если есть в базе
							$DB-&gt;Query("UPDATE b_catalog_price SET PRICE='".$price."',PRICE_SCALE='".$price."' WHERE PRODUCT_ID=".$ELEMENT_ID);
						}
 
						// ДАЛЕЕ РАБОТАЕТ С ТОРГОВЫМИ ПРЕДЛОЖЕНИЯМИ
						$arSKU = 0;
						$arSKU = CCatalogSKU::getOffersList($ELEMENT_ID, $iblock_id, array('ACTIVE' =&gt; 'Y'), array(), array());	// получаем торговые предложения товара, для нашего инфоблока $iblock_id по ID товара $ELEMENT_ID
						if(count($arSKU)&gt;0)
						{	// если есть торг. предложения
							foreach($arSKU as $item_s){
								foreach($item_s as $item_sku)
								{
									$ar_res = CCatalogProduct::GetByID($item_sku['ID']);	// получаем свойства торг. предложения
 
									// получаем цену и id цены
									$db_res = CPrice::GetList(array(),array("PRODUCT_ID" =&gt; $item_sku["ID"],"CATALOG_GROUP_ID" =&gt; "1"));
									if($ar_res_sku = $db_res-&gt;Fetch()){
										$ar_res_sku["PRICE"];
									}
 
 
									// делаем новую цену
 
									$res_chek = $DB-&gt;Query("SELECT * FROM b_catalog_price WHERE PRODUCT_ID=".$item_sku["ID"]);
									if($res_chek-&gt;result-&gt;num_rows == 0){
										// Добавляем, если нет
										$arFields = Array(
											"PRODUCT_ID" =&gt; $item_sku["ID"],
											"CATALOG_GROUP_ID" =&gt; 1,
											"PRICE" =&gt; $price,
											"CURRENCY" =&gt; "RUB"
										);
										CPrice::Add($arFields);
									} else {
										if($res_chek-&gt;result-&gt;num_rows == 2 || $res_chek-&gt;result-&gt;num_rows == 3 || $res_chek-&gt;result-&gt;num_rows == 4){
											$res_del = $DB-&gt;Query("SELECT MIN(ID) FROM b_catalog_price WHERE PRODUCT_ID=".$item_sku["ID"]."");
											while ($pr = $res_del -&gt;Fetch())
											{
												$min_id = (int)$pr["MIN(ID)"];
												$DB-&gt;Query("DELETE FROM b_catalog_price WHERE ID=".$min_id."");
											}
										}
										// Изменяем, если есть
										$DB-&gt;Query("UPDATE b_catalog_price SET PRICE='".$price."',PRICE_SCALE='".$price."' WHERE PRODUCT_ID=".$item_sku["ID"]);
									}
								}
							}
						}
 
						echo $ELEMENT_ID." - ".$artikul." - новая цена: ".$price." <br>";
					}
				}
				fclose($handle);
			}
		//echo "Файл корректен и был успешно загружен.\n";
	} else {
		echo "Файл не выбран !\n";
	}
}