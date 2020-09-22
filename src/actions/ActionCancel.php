<?php


namespace TaskForce\actions;


class ActionCancel extends AbstractAction {
  
  
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  function getReadableName() {
    return 'Отменить';
  }
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  function getInnerName() {
    return 'action_cancel';
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