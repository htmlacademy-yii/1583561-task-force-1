<?php

include_once "../vendor/autoload.php";

use TaskForce\Task;

$task = new Task(null, null);	
assert($task->getAvaliableActions(Task::STATUS_NEW, Task::ROLE_CONTRACTOR)[0] == [Task::ACTION_RESPOND][0]);
assert($task->getAvaliableActions(Task::STATUS_NEW, Task::ROLE_CLIENT)[0] == [Task::ACTION_CANCEL][0]);

$task->setStatus(Task::STATUS_NEW);
assert($task->getNextStatus(Task::ACTION_CANCEL) == Task::STATUS_CANCELED);

assert($task->getReadableNamesMap()[Task::STATUS_CANCELED] == 'Отменено');