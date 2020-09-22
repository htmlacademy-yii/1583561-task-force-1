<?php


namespace TaskForce\actions;


class ActionComplete extends AbstractAction {
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function getReadableName() {
    return 'Завершить';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function getInnerName() {
    return 'action_complete';
  }
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  function checkAccess($contractorId, $clientId, $currentUserId) {
    return $clientId == $currentUserId;
  }
}