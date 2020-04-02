<?php
function writeHead($pageName) {
	print "<!DOCTYPE html>
<html>
<head>
	<meta charset=\"utf-8\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
	<title>$pageName</title>
</head>
<body>\n";
}

function nav($pageName) {
	if ( $pageName == 'main' ) {
		$mainClass = 'active';
		$goalsClass = 'nav';
		$tasksClass = 'nav';
		$notesClass = 'nav';
		$categoriesClass = 'nav';
	} elseif ( $pageName == 'goals' ) {
		$mainClass = 'nav';
		$goalsClass = 'active';
		$tasksClass = 'nav';
		$notesClass = 'nav';
		$categoriesClass = 'nav';
	} elseif ( $pageName == 'tasks' ) {
		$mainClass = 'nav';
		$goalsClass = 'nav';
		$tasksClass = 'active';
		$notesClass = 'nav';
		$categoriesClass = 'nav';
	} elseif ( $pageName == 'notes' ) {
		$mainClass = 'nav';
		$goalsClass = 'nav';
		$tasksClass = 'nav';
		$notesClass = 'active';
		$categoriesClass = 'nav';
	} elseif ( $pageName == 'categories' ) {
		$mainClass = 'nav';
		$goalsClass = 'nav';
		$tasksClass = 'nav';
		$notesClass = 'nav';
		$categoriesClass = 'active';
	}

	echo "\t<header>
		<nav>
			<ul class=\"nav\">
				<li class=\"nav\"><a href=\"/project/main.php\" class=\"$mainClass\">Главная</a></li>
				<li class=\"nav\"><a href=\"/project/goals.php\" class=\"$goalsClass\">Цели</a></li>
				<li class=\"nav\"><a href=\"/project/tasks.php\" class=\"$tasksClass\">Задачи</a></li>
				<li class=\"nav\"><a href=\"/project/notes.php\" class=\"$notesClass\">Заметки</a></li>
				<!-- <li class=\"nav\"><a href=\"/trackers.php\" class=\"nav\">Трекеры</a></li> -->
				<li class=\"nav\"><a href=\"/project/categories.php\" class=\"$categoriesClass\">Категории</a></li>
				<ul class=\"account\">
					<li class=\"acc\">
						<button class=\"account\"></button>
						<ul class=\"submenuacc\">
							<li class=\"account\"><a href=\"#\" class=\"account\">Настройки</a></li>
							<li class=\"account\"><a href=\"#\" class=\"account\">Помощь</a></li>
							<li class=\"account\"><a href=\"exit.php\" class=\"account\">Выход</a></li>
						</ul>
					</li>					
				</ul>
			</ul>
		</nav>
	</header>\n";
	}

	function writeFoot() {
		echo "</body>
</html>";
	}

	function add() {
		echo "\t<ul class=\"addul\">
		<li class=\"addli\">
			<ul class=\"submenu\">
				<li class=\"addli\"><p class=\"addtext\">Добавить...</p></li>
				<li class=\"addli\"><p><a href=\"create_new_category.php\" class=\"add\">категорию</a></p></li>
				<!-- <li><p><a href=\"#\" class=\"add\">трекер</a></p></li> -->
				<li class=\"addli\"><p><a href=\"/project/create_new_note.php\" class=\"add\">заметку</a></p></li>
				<li class=\"addli\"><p><a href=\"/project/create_new_goal.php\" class=\"add\">цель</a></p></li>
				<li class=\"addli\"><p><a href=\"create_new_task.php\" class=\"add\">задачу</a></p></li>
			</ul>
			<button class=\"addbutton\">+</button>
		</li>
	</ul>\n";
	}

	function draw_calendar($month,$year){
 
		/* Начало таблицы */
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
	   
		/* Заглавия в таблице */
		$headings = array('ПН','ВТ','СР','ЧТ','ПТ','СБ','ВС');
		$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
	   
		/* необходимые переменные дней и недель... */
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$running_day = $running_day - 1;
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();
	   
		/* первая строка календаря */
		$calendar.= '<tr class="calendar-row">';
	   
		/* вывод пустых ячеек в сетке календаря */
		for($x = 0; $x < $running_day; $x++):
		  $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		  $days_in_this_week++;
		endfor;
		/* дошли до чисел, будем их писать в первую строку */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
	   
		  $calendar.= '<td class="calendar-day"><a href="add.php">D</a>  <a href="#">C</a>  <a href="#">R</a>  <a href="#">DEL</a>';
			/* Пишем номер в ячейку */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';
		  
			
			/** ЗДЕСЬ МОЖНО СДЕЛАТЬ MySQL ЗАПРОС К БАЗЕ ДАННЫХ! ЕСЛИ НАЙДЕНО СОВПАДЕНИЕ ДАТЫ СОБЫТИЯ С ТЕКУЩЕЙ - ВЫВОДИМ! **/
			$calendar.= str_repeat('<p>&nbsp;</p>',2);
			
		  $calendar.= '</td>';
		  if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
			  $calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		  endif;
		  $days_in_this_week++; $running_day++; $day_counter++;
		endfor;
	   
		/* Выводим пустые ячейки в конце последней недели */
		if($days_in_this_week < 8):
		  for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		  endfor;
		endif;
	   
		/* Закрываем последнюю строку */
		$calendar.= '</tr>';
	   
		/* Закрываем таблицу */
		$calendar.= '</table>';
		
		/* Все сделано, возвращаем результат */
		return $calendar;
	}

	function drawCalendar($month, $year) {
		$calendar = '<table>
		<tr>
			<th>ПН</th>
			<th>ВТ</th>
			<th>СР</th>
			<th>ЧТ</th>
			<th>ПТ</th>
			<th>СБ</th>
			<th>ВС</th>
		</tr>
		';

		//$running_week_day = date('w',mktime(0,0,0,$month,1,$year)); //порядковый номер дня недели
		//$days_in_month = date('t',mktime(0,0,0,$month,1,$year)); //кол-во дней в месяце
		//$days_in_this_week = 1;
		//$day_counter = 0;
		//$dates_array = array();
		
		
	}

	//function getCurrentMonth($month, $year) {
	//	$daysNumberPerMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
	//	//print $daysNumberPerMonth;
	//	$weekDayOfFirstDayInMonth = date('N', mktime(0, 0, 0, $month, 1, $year));
	//	//print $weekDayOfFirstDayInMonth;
//
//		
//		$displayedMonth = '<tr>
//		';
//
//		$numberDisplayedPreviousMonthDays = $weekDayOfFirstDayInMonth - 1;
//		for($i = 1, $i < $weekDayOfFirstDayInMonth, $i++) {
//			$displayedMonth .= '<td><td>
//			';
//			$j = $i;
//		}
//		$displayedMonth .= '</tr>
//		';
//		for($i = 0, $i < $daysNumberPerMonth, $i++) {
//			for($j++ , $j <= 7, $j++) {
//
//			}
//		}
//	}
?>