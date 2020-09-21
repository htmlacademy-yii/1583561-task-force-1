<?php


namespace TaskForce;


abstract class AbstractAction {
  /**
   * Возвращает человекочитаемое имя действия
   * @return string
   */
  abstract function ReadableName();
  
  /**
   * Возвращает внутреннее имя действия
   * @return string
   */
  abstract function InnerName();
  
  /**
   * @param int $contractorId Идентификатор исполнителя
   * @param int $clientId Идентификатор заказчика
   * @param int $currentUserId Идентификатор текущего пользователя
   * @return boolean True, если это действие возможно
   */
  abstract function CheckAccess($contractorId, $clientId, $currentUserId);
}