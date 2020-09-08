<?php

namespace TaskForce;

/**
 * Class Task
 * Класс описывает сущность предметной области "Задание". Хранит информацию
 * о статусе задания и позволяет возвращать доступные действия и результат
 * их выполнения.
 * @package TaskForce
 */
class Task {
  // Новое - Задание опубликовано, исполнитель ещё не найден
  const STATUS_NEW = 'status_new';
  // Отменено - Заказчик отменил задание
  const STATUS_CANCELED = 'status_canceled';
  // В работе - Заказчик выбрал исполнителя для задания
  const STATUS_IN_PROGRESS = 'status_in_progress';
  // Выполнено - Заказчик отметил задание как выполненное
  const STATUS_COMPLETED = 'status_completed';
  // Провалено - Исполнитель отказался от выполнения задания
  const STATUS_FAILED = 'status_failed';

  // Отменить задание
  const ACTION_CANCEL = 'action_cancel';
  // Откликнуться на задание
  const ACTION_RESPOND = 'action_respond';
  // Отказаться от задания
  const ACTION_DECLINE = 'action_decline';
  // Закончить задание
  const ACTION_COMPLETE = 'action_complete';

  // Роль исполнителя
  const ROLE_CONTRACTOR = 'role_contractor';
  // Роль заказчика
  const ROLE_CLIENT = 'role_client';
  
  const READABLE_NAMES_MAP_STATUS = [
    self::STATUS_NEW => 'Новое',
    self::STATUS_CANCELED => 'Отменено',
    self::STATUS_IN_PROGRESS => 'В работе',
    self::STATUS_COMPLETED => 'Выполнено',
    self::STATUS_FAILED => 'Провалено'
  ];

  const READABLE_NAMES_MAP_ACTION = [
    self::ACTION_CANCEL => 'Отменить',
    self::ACTION_RESPOND => 'Откликнуться',
    self::ACTION_DECLINE => 'Отказаться',
    self::ACTION_COMPLETE => 'Завершить'
  ];

  const READABLE_NAMES_MAP_ROLE = [
    self::ROLE_CLIENT => 'Заказчик',
    self::ROLE_CONTRACTOR => 'Исполнитель'
  ];
  
  private $contractorId;
  private $clientId;

  private $status;

  /**
   * Устанавливает новый статус задания
   * @param string $status Новый статус
   * Константа класса, начинающаяся со STATUS_
   */
  public function setStatus($status) {
    $this->status = $status;
  }

  /**
   * Вовзращает следующий статус задания с учётом текущего статуса
   * и действия, передаваемого параметром
   * @param string $action Действие, приводящее к смене статуса
   * Константа класса, начинающаяся с ACTION_
   * @return array Статус задания в результате действия
   * Константа класса, начинающаяся со STATUS_
   */
  public function getNextStatus($action) {
    switch ($action) {
      case self::ACTION_CANCEL:
        if ($this->status === self::STATUS_NEW) {
          return [self::STATUS_CANCELED];
        } else {
          throw new Exception('Cannot cancel from this status', 1);
        }
        break;
      case self::ACTION_RESPOND:
        if ($this->status === self::STATUS_NEW) {
          return [self::STATUS_IN_PROGRESS];
        } else {
          throw new Exception('Cannot respond from this status', 1);
        }
        break;
      case self::ACTION_DECLINE:
        if ($this->status === self::STATUS_IN_PROGRESS) {
          return [self::STATUS_FAILED];
        } else {
          throw new Exception('Cannot fail from this status', 1);
        }
        break;
      case self::ACTION_COMPLETE:
        if ($this->status === self::STATUS_IN_PROGRESS) {
          return [self::STATUS_COMPLETED];
        } else {
          throw new Exception('Cannot complete from this status', 1);
        }
        break;
      default:
        throw new Exception('Unknown action', 1);
        break;
    }
  }

  /**
   * Возвращает список доступных действий с учётом статуса и роли актора
   * @param string $status Статус, для которого необходимо получить список доступных действий.
   * Константа класса, начинающаяся со STATUS_
   * @param string $role Роль субъекта, изменяющего статус
   * Константа класса, начинающаяся с ROLE_
   * @return array Массив доступных действий
   */
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

  /**
   * Task constructor.
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   */
  public function __construct($contractorId, $clientId) {
    $this->contractorId = $contractorId;
    $this->clientId = $clientId;
  }
}