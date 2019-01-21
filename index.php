<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Задание 2</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
 <h2 style="text-align:center">Задание 2</h2><hr>
<form class="search2" style="margin:60px auto; width: 600px;" method="get">
	<div class="search2__input">
		<span class="input  input_size_ws-head input_theme_websearch">
			<span class="input__box">
				<input class="input__control input__input" name="str" value="<?php if(isset($_GET['str'])){ echo $_GET['str'];}?>">
			</span>
		</span>
	</div>
	<div class="search2__button">
		<button class="button  button_theme_websearch button_size_ws-head i-bem " type="submit">
			<span class="button__text">Поиск</span>
		</button>
	</div>
</form>
<div style="width:80%; margin:0 auto;">
<?php

	include ('zipf.php');		
	$dir  = 'files/';
	$files = array_diff(scandir($dir), array('..', '.'));
	$indexer = array();
	
	foreach($files as $nameFiles) {		
		$data1 =   file_get_contents('files/'. $nameFiles);			
		$data = Zipf::full_trim($data1); // обрезание лишних пробелов
		
		// разбиение слов на массивы
		$in = mb_strtolower($data, 'UTF-8');
		$in = preg_replace("'ё'u", "е", $in);
		$arrWorks = preg_match_all("'[a-zа-яё]+'u", $in, $m) ? $m[0] : array();
		//$arrWorks = explode(' ',$in);

		foreach($arrWorks as $key => $value){
			if($value=="") unset($arrWorks[$key]);
		}
		$data_count = count($arrWorks); // количество слов в тексте
		$collection = Zipf::createObjParap($arrWorks); // нахождение частоты
		$collection = Zipf::p($collection,$data_count); // нахождение вероятности
		$collection = Zipf::sorting($collection); // сортировка по количеству входжений слов в тексте
		$collection = Zipf::rank($collection); // находим (расчитывыем) ранг
		$collection = Zipf::c($collection,$data_count); // нахождение c 		
		$collection = Zipf::StopWorks($collection);
		$collection = Zipf::q($collection[0]);		
		if (array_search($_GET['str'], Zipf::getKeys($collection))!= false){
			echo '<hr>';
			echo '<p> <a href="files/'. mb_convert_encoding($nameFiles, "UTF-8", "cp1251").'">'. mb_convert_encoding($nameFiles, "UTF-8", "cp1251"). '</a> <br>';
			$indexer[$nameFiles] = Zipf::getKeys($collection);
			echo '<b>Список ключевых слов:</b> '.implode(', ',Zipf::getKeys($collection)) . '</p>'; // ключи			
			//echo array_search($_GET['str'], Zipf::getKeys($collection))!= false;
			echo substr($data1,0,1000). ' ........';
			echo '<hr>';
		}
		for($i=0; $i<count(Zipf::getKeys($collection)); $i++){
			$index[] = Zipf::getKeys($collection)[$i];
		}
	}		
	//print_r ($index);	
	
	echo '</div><hr><div style="width:90%; margin:0 auto;"><p><h3><b> Список ключевых слов обработанных текстов:</b></h3></br>';
	foreach(array_unique($index) as $value){echo $value . ', ';};
	echo '</p></div>';
	
?>
<div><p style="text-align:center "> &copy 2018, Равкович С.В. гр.581074</p></div>
</body>
</html>
