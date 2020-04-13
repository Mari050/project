<?php
session_start();


require 'db.php'; 
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}


if (isset($_POST['create']))  {
	$errors = array();
	if (empty(trim($_POST['name']))) {
		$errors[0] = 'Не введено название задачи!';
	}
	if (isset($_POST['start_date']) AND isset($_POST['finish_date'])) {
		if ($_POST['start_date'] > $_POST['finish_date']) {
			$errors[0] = 'Дата начала не может быть позже даты завершения!';
		} elseif (isset($_POST['start_time']) AND isset($_POST['finish_time'])) {
			if ($_POST['start_time'] > $_POST['finish_time']) {
				$errors[0] = 'Время начала не может быть позже времени завершения!';
			}
		}
	} elseif (isset($_POST['start_time']) AND isset($_POST['finish_time'])) {
		if ($_POST['start_time'] > $_POST['finish_time']) {
			$errors[0] = 'Время начала не может быть позже времени завершения!';
		}
	}

	if (empty($errors)) {
		$priority = [];
		if (isset($_POST['priority'])) {
			foreach ($_POST['priority'] as $value) {
				if ($_POST['priority'][0]) {
					$priority[0] = 'urgent';
				} else {
					$priority[0] = 'notUrgent';
				}
				if ($_POST['priority'][1]) {
					$priority[1] = 'important';
				} else {
					$priority[1] = 'notImportant';
				}
			}
			$priority = implode('|', $priority);
		}

		$categories = '';
		if (isset($_POST['category'])) {
			foreach ($_POST['category'] as $value) {
				$categories = implode('|', $_POST['category']);
			}
		}

		
		$task = R::dispense('tasks');
			$task->login = $_SESSION['login'];
			$task->name = trim($_POST['name']); 
			if (trim($_POST['description'])) {
				$task->description = trim($_POST['description']);
			}
			$task->created = date('Y-m-d', time());
			if ($_POST['start_date']) {
				$task->start_date = $_POST['start_date'];
			}
			if ($_POST['start_time']) {
				$task->start_time = $_POST['start_time'];
			}
			if ($_POST['finish_date']) {
				$task->finish_date = $_POST['finish_date'];
			}
			if ($_POST['finish_time']) {
				$task->start_time = $_POST['finish_time'];
			}
			if (isset($_POST['repeat'])) {
				$task->repeat = $_POST['repeat'];
			}
			if ($priority) {
				$task->priority = $priority;
			}
			if ($categories) {
				$task->categories = $categories;
			}
			$task->ready = 'inProgress';
			R::store($task);
			if ($_SESSION['parent_goal']) {
				$task->goal_id = $_SESSION['parent_goal'];
				$goal = R::findOne('goals', '`id` = ?', array($_SESSION['parent_goal']));
				if (isset($goal->tasks_ids)) {
					$goal->tasks_ids .= '|' . $task->id;
				}
				$_SESSION['parent_goal'] = NULL;
			}
			if ($task->categories) {
				$categoriesIDs = explode('|', $task->categories);
				foreach ($categoriesIDs as $categoryID) {
					$category = R::findOne('categories', '`id` = ?', array($categoryID));
					if ($category->tasks) {
						$categoryTasks = explode('|', $category->tasks);
						$isRepeat = array_search($task->id, $categoryTasks);
						if (! $isRepeat) {
							continue;
						} else {
							$category->tasks .= '|' . $task->id;
						}
					} else {
						$category->tasks = $task->id;
				}
				R::store($category);
			}
		}

		$_SESSION['task_id'] = $task->id;
		redirectToAnotherPage('task_page.php');
	} else {
		$error = array_shift($errors);
		if (isset($_POST['name'])) {
			$name = $_POST['name'];
		} else {
			$name = NULL;
		}
		if (isset($_POST['description'])) {
			$description = $_POST['description'];
		} else {
			$description = NULL;
		}
		if (isset($_POST['start_date'])) {
			$startDate = $_POST['start_date'];
		} else {
			$startDate = NULL;
		}
		if (isset($_POST['start_time'])) {
			$startTime = $_POST['start_time'];
		} else {
			$startTime = NULL;
		}
		if (isset($_POST['finish_date'])) {
			$finishDate = $_POST['finish_date'];
		} else {
			$finishDate = NULL;
		}
		if (isset($_POST['finish_time'])) {
			$finishTime = $_POST['finish_time'];
		} else {
			$finishTime = NULL;
		}
		if (isset($_POST['repeat'])) {
			if ($_POST['repeat'] == 'd') {
				$repeatEveryDay = 'checked';
				$repeatEveryWeek = NULL;
				$repeatEveryMonth = NULL;
			} elseif ($_POST['repeat'] == 'w') {
				$repeatEveryDay = NULL;
				$repeatEveryWeek = 'checked';
				$repeatEveryMonth = NULL;
			} elseif ($_POST['repeat'] == 'm') {
				$repeatEveryDay = NULL;
				$repeatEveryWeek = NULL;
				$repeatEveryMonth = 'checked';
			}
		} else {
			$repeatEveryDay = NULL;
			$repeatEveryWeek = NULL;
			$repeatEveryMonth = NULL;
		}
		if (isset($_POST['priority'])) {
			if ($_POST['priority'][0] == 'urgent' AND $_POST['priority'][1] == 'important') {
				$urgent = 'checked';
				$important = 'checked';
			} elseif ($_POST['priority'][0] == 'urgent') {
				$urgent = 'checked';
				$important = '';
			} elseif ($_POST['priority'][0] == 'important') {
				$urgent = '';
				$important = 'checked';
			}
		} else {
			$urgent = '';
			$important = '';
		}

	}
} else {
	$name = NULL;
	$description = NULL;
	$startDate = NULL;
	$startTime = NULL;
	$finishDate = NULL;
	$finishTime = NULL;
	$repeatEveryDay = NULL;
	$repeatEveryWeek = NULL;
	$repeatEveryMonth = NULL;
	$urgent = '';
	$important = '';
}


$categories = R::find('categories', '`login` = ?', array($_SESSION['login']));

if (isset($_SESSION['parent_goal'])) {
	$goal = R::findOne('goals', '`id` = ?', array($_SESSION['parent_goal']));
	$goalName = $goal->name;
}


writeHead('Новая задача');
nav('tasks');
if (isset($error)) {
	print "<p class=\"error\">$error</p>\n";
}
print "\t<form action=\"\" method=\"POST\" class=\"createsmtnew\">\n";
print "\t\t<div>
        	<input type=\"submit\" name=\"create\" value=\"Создать\" class=\"createsmtnew create\">
		</div>\n";
if (isset($goalName)) {
	print "\t\t<div>
			<span class=\"label_parameter\">Цель: </span>
			<span class=\"goal_name\">$goalName</span>
		</div>\n";
	/*
	 * После span добавить выпадающую менюшку:
	 * Удалить цель (т.е. из данной цели убрать id задачи, а из задачи удалить id цели, тем самым разделив их)
	 * Поменять цель (т.е. перетащить эту задачу в другую цель)
	 */
}
print "\t\t<div>
			<p>
				<input type=\"text\" name=\"name\" placeholder=\"Наименование задачи\" value=\"$name\" class=\"createsmtnew name\">
			</p>
			<p>
				<textarea name=\"description\" placeholder=\"Описание задачи\" class=\"createsmtnew description\">$description</textarea>
			</p>
		</div>\n";
print "\t\t<div>
			<label>
            	<span class=\"cat\">Дата и время начала:</span><br>
				<input type=\"date\" name=\"start_date\" value=\"$startDate\" class=\"createsmtnew date\">
				<input type=\"time\" name=\"start_time\" value=\"$startTime\" class=\"createsmtnew time\">
			</label>
		</div><br>\n";
print "\t\t<div>
			<lable>
				<span class=\"cat\">Дата и время завершения:</span><br>
				<input type=\"date\" name=\"finish_date\" value=\"$finishDate\" class=\"createsmtnew date\">
				<input type=\"time\" name=\"finish_time\" value=\"$finishTime\" class=\"createsmtnew time\">
			</lable>
		</div><br>\n";
print "\t\t<div>
			<span class=\"label_parameter\">Повтор:</span><br>
			<label>
				<span class=\"repeat\">Каждый день</span>
				<input type=\"radio\" name=\"repeat\" value=\"d\" class=\"repeat\" $repeatEveryDay>
			</label>
			<label>
				<span class=\"repeat\">Каждую неделю</span>
				<input type=\"radio\" name=\"repeat\" value=\"w\" class=\"repeat\" $repeatEveryWeek>
			</label>
			<label>
				<span class=\"repeat\">Каждый месяц</span>
				<input type=\"radio\" name=\"repeat\" value=\"m\" class=\"repeat\" $repeatEveryMonth>
			</label>
		</div>\n";
print "\t\t<div>
			<label>
				<span class=\"cat\">Приоритет:</span><br>
				<div class=\"value_parameter\">
					<label class=\"priority\">
						<input type=\"checkbox\" name=\"priority[]\" value=\"urgent\" class=\"createsmtnew priority\" $urgent>
						<span class=\"priority\">Срочно</span>
					</label>
					<label class=\"priority\">
						<input type=\"checkbox\" name=\"priority[]\" value=\"important\" class=\"createsmtnew priority\" $important>
						<span class=\"priority\">Важно</span>
					</label>
				</div>
			</label>
		</div><br>\n";
print "\t\t<div>
			<label>
				<span class=\"cat\">Категории:</span><br>
				<div class=\"near_cat\">\n";
foreach ($categories as $cat => $value) {
	if (isset($_POST['category'])) {
		$isActive =  in_array($value->id, $_POST['category']);
	} else {
		$isActive = NULL;
	}
	if ($isActive) {
		print "\t\t\t\t\t<div>
						<label>
							<input type=\"checkbox\" name=\"category[]\" value=\"$value->id\" class=\"createsmtnew category\" checked>
							<span class=\"category\">$value->name</span>
						</label>
					</div><br>\n";
	} else {
		print "\t\t\t\t\t<div>
						<label>
							<input type=\"checkbox\" name=\"category[]\" value=\"$value->id\" class=\"createsmtnew category\">
							<span class=\"category\">$value->name</span>
						</label>
					</div><br>\n";
	}
}
print "\t\t\t\t</div>
			</label>
		</div>
	</form>\n";
writeFoot();
?>
