<?php


namespace TaskForce\actions;


abstract class AbstractAction {
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  abstract function getReadableName();
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  abstract function getInnerName();
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  abstract function checkAccess($contractorId, $clientId, $currentUserId);
}