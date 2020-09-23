<?php


namespace TaskForce\actions;


class ActionRespond extends AbstractAction {
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function getReadableName() {
    return 'Откликнуться';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function getInnerName() {
    return 'action_respond';
  }
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  function checkAccess($contractorId, $clientId, $currentUserId) {
    return true;
  }
}