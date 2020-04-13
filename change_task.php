<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';
    

if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}
if (empty($_SESSION['task_id'])) {
    redirectToAnotherPage('main.php');
}


if (isset($_POST['save_changes'])) {
    $errors = [];
	if (empty($_POST['name'])) {
		$errors[0] = 'Вы попытались удалить название задачи!<br>Не надо так =/';
	}


	if (empty($errors)) {
		if (isset($_POST['priority'])) {
			if ($_POST['priority'][0] == 'urgent' AND $_POST['priority'][1] == 'important') {
				$priority = 'urgent|important';
			} elseif ($_POST['priority'][0] == 'urgent') {
				$priority = 'urgent|notImportant';
			} elseif ($_POST['priority'][0] == 'important') {
				$priority = 'notUrgent|important';
			}
		} else {
			$priority = 'notUrgent|notImportant';
		}

		$categories = '';
		if (isset($_POST['category'])) {
			$categories = implode('|', $_POST['category']);
		}

		$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
			$task->name = $_POST['name'];
			if (trim($_POST['description'])) {
				$task->description = trim($_POST['description']);
			}
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
				$task->finish_time = $_POST['finish_time'];
			}
			if (isset($_POST['repeat'])) {
				$tast->repeat = $_POST['repeat'];
			}
			$task->priority = $priority;
			if ($categories) {
				$task->categories = $categories;
			}
		R::store($task);

		if ($task->categories) {
			$categoriesIDs = explode('|', $task->categories);
			foreach ($categoriesIDs as $categoryID) {
				$category = R::findOne('categories', '`id` = ?', array($categoryID));
				if ($category->tasks) {
					$categoryTasks = explode('|', $category->tasks);
					$isRepeat = array_search($task->id, $categoryTasks);
					if ($isRepeat) {
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
		
		redirectToAnotherPage('task_page.php');
    } else {
		$error = array_shift($errors);
		$taskID = $_SESSION['task_id'];
		if (isset($_POST['name'])) {
			$taskName = $_POST['name'];
		} else {
			$taskName = NULL;
		}
		if (isset($_POST['description'])) {
			$taskDescription = $_POST['description'];
		} else {
			$taskDescription = NULL;
		}
		if (isset($_POST['start_date'])) {
			$taskStartDate = $_POST['start_date'];
		} else {
			$taskStartDate = NULL;
		}
		if (isset($_POST['start_time'])) {
			$taskStartTime = $_POST['start_time'];
		} else {
			$taskStartTime = NULL;
		}
		if (isset($_POST['finish_date'])) {
			$taskFinishDate = $_POST['finish_date'];
		} else {
			$taskFinishDate = NULL;
		}
		if (isset($_POST['finish_time'])) {
			$taskFinishTime = $_POST['finish_time'];
		} else {
			$taskFinishTime = NULL;
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
		$categoriesIDs =  $_POST['category'];
	}
} else {
	$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
    	$taskID = $task->id;
    	$taskName = $task->name;
    	$taskDescription = $task->description;
    	$taskCreated = $task->created;
    	$taskStartDate = $task->start_date;
    	$taskStartTime = $task->start_time;
    	$taskFinishDate = $task->finish_date;
	$taskFinishTime = $task->finish_time;
	if ($task->repeat == 'd') {
		$repeatEveryDay = 'checked';
		$repeatEveryWeek = NULL;
		$repeatEveryMonth = NULL;
	} elseif ($task->repeat == 'w') {
		$repeatEveryDay = NULL;
		$repeatEveryWeek = 'checked';
		$repeatEveryMonth = NULL;
	} elseif ($task->repeat == 'm') {
		$repeatEveryDay = NULL;
		$repeatEveryWeek = NULL;
		$repeatEveryMonth = 'checked';
	} else {
		$repeatEveryDay = NULL;
		$repeatEveryWeek = NULL;
		$repeatEveryMonth = NUll;
	}
    	$taskPriority = $task->priority;
		$taskCategories = $task->categories;
		$taskGoalID = $task->goal_id;
		$taskReady = $task->ready;

	if ($taskPriority == 'urgent|important') {
		$urgent = 'checked';
		$important = 'checked';
	} elseif ($taskPriority == 'notUrgent|important') {
		$urgent = '';
		$important = 'checked';
	} elseif ($taskPriority == 'urgent|notImportant') {
		$urgent = 'checked';
		$important = '';
	} else {
		$urgent = '';
		$important = '';
	}
	$categoriesIDs = explode('|', $taskCategories);
}



$categories = [];
$categories = R::find('categories', '`login` = ?', array($_SESSION['login']));

if (isset($taskGoalID)) {
	$goal = R::findOne('goals', '`id` = ?', array($taskGoalID));
		$goalName = $goal->name;
}


writeHead("$taskName");
nav('tasks');
if (isset($error)) {
	print "<p class=\"error\">$error</p>\n";
}
print "\t<form action=\"\" method=\"POST\" class=\"createsmtnew\">\n";
print "\t\t<div>
        	<input type=\"submit\" name=\"save_changes\" value=\"Сохранить изменения\" class=\"createsmtnew create\">
        	<input type=\"hidden\" name=\"task\" value=\"$taskID\">
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
				<input type=\"text\" name=\"name\" placeholder=\"Наименование задачи\" value=\"$taskName\" class=\"createsmtnew name\">
			</p>
			<p>
				<textarea name=\"description\" placeholder=\"Описание задачи\" class=\"createsmtnew description\">$taskDescription</textarea>
			</p>
		</div>\n";
print "\t\t<div>
			<label>
            	<span class=\"cat\">Дата и время начала:</span><br>
				<input type=\"date\" name=\"start_date\" value=\"$taskStartDate\" class=\"createsmtnew date\">
				<input type=\"time\" name=\"start_time\" value=\"$taskStartTime\" class=\"createsmtnew time\">
			</label>
		</div><br>\n";
print "\t\t<div>
			<lable>
				<span class=\"cat\">Дата и время завершения:</span><br>
				<input type=\"date\" name=\"finish_date\" value=\"$taskFinishDate\" class=\"createsmtnew date\">
				<input type=\"time\" name=\"finish_time\" value=\"$taskFinishTime\" class=\"createsmtnew time\">
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
				<input type=\"radio\" name=\"repeat\" value=\"w\" class=\"repeat\" 
$repeatEveryWeek>
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
	$isActive =  in_array($value->id, $categoriesIDs);
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
