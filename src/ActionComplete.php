<?php


namespace TaskForce;


class ActionComplete extends AbstractAction {
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function ReadableName() {
    return 'Завершить';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function InnerName() {
    return 'action_complete';
  }
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  function CheckAccess($contractorId, $clientId, $currentUserId) {
    return $clientId == $currentUserId;
  }
}