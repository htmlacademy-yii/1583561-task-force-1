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
   * @param AbstractAction $action Действие, приводящее к смене статуса
   * Константа класса, начинающаяся с ACTION_
   * @return array Статус задания в результате действия
   * Константа класса, начинающаяся со STATUS_
   */
  public function getNextStatus($action) {
    if ($action instanceof ActionCancel) {
      if ($this->status === self::STATUS_NEW) {
        return [self::STATUS_CANCELED];
      } else {
        throw new Exception('Cannot cancel from this status', 1);
      }
    } else if ($action instanceof  ActionRespond) {
      if ($this->status === self::STATUS_NEW) {
        return [self::STATUS_IN_PROGRESS];
      } else {
        throw new Exception('Cannot respond from this status', 1);
      }
    } else if ($action instanceof ActionDecline) {
      if ($this->status === self::STATUS_IN_PROGRESS) {
        return [self::STATUS_FAILED];
      } else {
        throw new Exception('Cannot fail from this status', 1);
      }
    } else if ($action instanceof ActionComplete) {
      if ($this->status === self::STATUS_IN_PROGRESS) {
        return [self::STATUS_COMPLETED];
      } else {
        throw new Exception('Cannot complete from this status', 1);
      }
    } else throw new Exception('Unknown action', 1);
  }

  /**
   * Возвращает список доступных действий с учётом статуса и роли актора
   * @param string $status Статус, для которого необходимо получить список доступных действий.
   * Константа класса, начинающаяся со STATUS_
   * @param string $role Роль субъекта, изменяющего статус
   * Константа класса, начинающаяся с ROLE_
   * @return array Массив доступных действий
   */
  public function getAvaliableActionsNoCheck($status, $role) {
    switch ($status) {
      case self::STATUS_COMPLETED:
        return [];
      case self::STATUS_FAILED:
        return [];
      case self::STATUS_NEW:
        if ($role === self::ROLE_CLIENT) return [new ActionCancel()];
        if ($role === self::ROLE_CONTRACTOR) return [new ActionRespond()];
        throw new Exception('Unknown role', 1);
        break;
      case self::STATUS_CANCELED:
        return [];
      case self::STATUS_IN_PROGRESS:
        if ($role === self::ROLE_CLIENT) return [new ActionComplete()];
        if ($role === self::ROLE_CONTRACTOR) return [new ActionDecline()];
        throw new Exception('Unknown role', 1);
      default:
        throw new Exception('Unknown status', 1);
        break;
    }
  }
  
  /**
   * Возвращает список доступных действий с учётом статуса, роли актора и текущего пользователя
   * @param int $currentUserId Идентификатор текущего пользователя
   * @param string $status Статус, для которого необходимо получить список доступных действий.
   * Константа класса, начинающаяся со STATUS_
   * @param string $role Роль субъекта, изменяющего статус
   * Константа класса, начинающаяся с ROLE_
   * @return array Массив доступных действий
   */
  public function getAvaliableActions($status, $role, $currentUserId) {
    $actions = $this->getAvaliableActionsNoCheck($status, $role);
    $clientId = $this->clientId;
    $contractorId = $this->contractorId;
    return array_filter($actions,
        function ($x) use ($currentUserId, $clientId, $contractorId) {
          return $x->checkAccess($contractorId, $clientId, $currentUserId);
        }
      );
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