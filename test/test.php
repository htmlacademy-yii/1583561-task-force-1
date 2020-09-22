<?php

include_once "../vendor/autoload.php";

use TaskForce\Task;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionRespond;

$task = new Task(1, 2);
assert($task->getAvaliableActions(Task::STATUS_NEW, Task::ROLE_CONTRACTOR, 1)[0] instanceof ActionRespond);
assert(count($task->getAvaliableActions(Task::STATUS_NEW, Task::ROLE_CLIENT, 3)) == 0);
assert($task->getAvaliableActions(Task::STATUS_NEW, Task::ROLE_CLIENT, 2)[0] instanceof ActionCancel);
assert(count($task->getAvaliableActions(Task::STATUS_COMPLETED, Task::ROLE_CONTRACTOR, 1)) == 0);
assert(count($task->getAvaliableActions(Task::STATUS_FAILED, Task::ROLE_CLIENT, 2)) == 0);


$task->setStatus(Task::STATUS_NEW);
assert($task->getNextStatus(new ActionCancel()) == [Task::STATUS_CANCELED]);
assert($task::READABLE_NAMES_MAP_STATUS[Task::STATUS_CANCELED] == 'Отменено');