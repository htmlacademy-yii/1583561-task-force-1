<?php 

namespace TaskForce;

class Task {
  const STATUS_NEW = 'status_new'; // Новое - Задание опубликовано, исполнитель ещё не найден
  const STATUS_CANCELED = 'status_canceled'; // Отменено - Заказчик отменил задание
  const STATUS_IN_PROGRESS = 'status_in_progress'; // В работе - Заказчик выбрал исполнителя для задания
  const STATUS_COMPLETED = 'status_completed'; // Выполнено - Заказчик отметил задание как выполненное
  const STATUS_FAILED = 'status_failed'; // Провалено - Исполнитель отказался от выполнения задания

  const ACTION_CANCEL = 'action_cancel';
  const ACTION_RESPOND = 'action_respond';
  const ACTION_DECLINE = 'action_decline';
  const ACTION_COMPLETE = 'action_complete';

  const ROLE_CONTRACTOR = 'role_contractor';
  const ROLE_CLIENT = 'role_client';

  private $readableNamesMap = [
    self::STATUS_NEW => 'Новое',
    self::STATUS_CANCELED => 'Отменено',
    self::STATUS_IN_PROGRESS => 'В работе',
    self::STATUS_COMPLETED => 'Выполнено',
    self::STATUS_FAILED => 'Провалено',

    self::ACTION_CANCEL => 'Отменить',
    self::ACTION_RESPOND => 'Откликнуться',
    self::ACTION_DECLINE => 'Отказаться',
    self::ACTION_COMPLETE => 'Завершить',

    self::ROLE_CLIENT => 'Заказчик',
    self::ROLE_CONTRACTOR => 'Исполнитель'
  ];

  public function getReadableNamesMap() { return $this->readableNamesMap; }

  private $contractorId;
  private $clientId;

  private $status;

  public function setStatus($status) {
    $this->status = $status;
  }

  public function getNextStatus($action) {
    switch($action) {
      case self::ACTION_CANCEL:
        if ($this->status === self::STATUS_NEW) {
          return self::STATUS_CANCELED;
        } else {
        	throw new Exception('Cannot cancel from this status', 1);
        }
        break;
      case self::ACTION_RESPOND:
        if ($this->status === self::STATUS_NEW) {
          return self::STATUS_IN_PROGRESS;
        } else {
          throw new Exception('Cannot respond from this status', 1);
        }
        break;
      case self::ACTION_DECLINE: 
      	if ($this->status === self::STATUS_IN_PROGRESS) {
      		return self::STATUS_FAILED;
      	} else { 
          throw new Exception('Cannot fail from this status', 1);
      	}
        break;
      case self::ACTION_COMPLETE:
        if ($this->status === self::STATUS_IN_PROGRESS) {
        	return self::STATUS_COMPLETED;
        } else {
          throw new Exception('Cannot complete from this status', 1);
        }
        break;
      default:
         throw new Exception('Unknown action', 1);
        break;
    }
  }

  public function getAvaliableActions($status, $role) {
  	switch ($status) {
  		case self::STATUS_COMPLETED:
  		  return [];
  		case self::STATUS_FAILED:
  		  return [];
  		case self::STATUS_NEW: 
  		  if ($role === self::ROLE_CLIENT) return [self::ACTION_CANCEL];
  		  if ($role === self::ROLE_CONTRACTOR) return [self::ACTION_RESPOND];
        throw new Exception('Unknown role', 1);
  		  break;
  		case self::STATUS_CANCELED:
  		  return [];
  		case self::STATUS_IN_PROGRESS:
  		  if ($role === self::ROLE_CLIENT) return [self::ACTION_COMPLETE];
  		  if ($role === self::ROLE_CONTRACTOR) return [self::ACTION_DECLINE];
        throw new Exception('Unknown role', 1);
  		default:    
  			throw new Exception('Unknown status', 1);
  			break;
  	}
  }

  public function __construct($contractorId, $clientId) {
    $this->contractorId = $contractorId;
    $this->clientId = $clientId;
  }
}