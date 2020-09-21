<?php


namespace TaskForce;


class ActionCancel extends AbstractAction {
  
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function ReadableName() {
    return 'Отменить';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function InnerName() {
    return 'action_cancel';
  }
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  function CheckAccess($contractorId, $clientId, $currentUserId) {
    return $clientId === $currentUserId;
   }
}