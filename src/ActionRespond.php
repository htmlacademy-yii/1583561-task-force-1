<?php


namespace TaskForce;


class ActionRespond extends AbstractAction {
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function ReadableName() {
    return 'Откликнуться';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function InnerName() {
    return 'action_respond';
  }
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  function CheckAccess($contractorId, $clientId, $currentUserId) {
    return true;
  }
}