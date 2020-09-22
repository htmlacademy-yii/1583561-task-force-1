<?php


namespace TaskForce\actions;


class ActionDecline extends AbstractAction {
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function getReadableName() {
    return 'Отказаться';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function getInnerName() {
    return 'action_decline';
  }
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  function checkAccess($contractorId, $clientId, $currentUserId) {
    return $currentUserId == $contractorId;
  }
}