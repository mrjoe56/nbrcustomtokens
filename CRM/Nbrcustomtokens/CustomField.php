<?php
use CRM_Nbrcustomtokens_ExtensionUtil as E;

/**
 * Class for safety catch contact custom field tokens
 *
 * @author Erik Hommel <erik.hommel@civicoop.org>
 * @date 18 Feb 2021
 * @license AGPL-3.0
 */
class CRM_Nbrcustomtokens_CustomField {
  private $_columnName = NULL;
  private $_customGroupTableName = NULL;
  private $_optionGroupId = NULL;
  private $_customCode;
  private $_contactId;

  /**
   * CRM_Nbrcustomtokens_CustomField constructor.
   *
   * @param string $customCode
   * @param int $contactId
   */
  public function __construct(string $customCode, int $contactId) {
    $this->_customCode = strtolower($customCode);
    $this->_contactId = $contactId;
  }

  /**
   * Method to set all the properties and return false if something is wrong
   */
  public function initialize() {
    $customFieldId = str_replace("custom_", "", $this->_customCode);
    try {
      $result = \Civi\Api4\CustomField::get()
        ->addSelect('column_name', 'option_group_id', 'custom_group.table_name')
        ->addWhere('id', '=', $customFieldId)
        ->setLimit(1)
        ->execute();
      $customField = $result->first();
      foreach ($customField as $key => $value) {
        switch ($key) {
          case "column_name":
            $this->_columnName = $value;
            break;
          case "custom_group.table_name":
            $this->_customGroupTableName = $value;
            break;
          case "option_group_id":
            $this->_optionGroupId = $value;
            break;
        }
      }
    }
    catch (API_Exception $ex) {
      Civi::log()->error("Could not find custom field data for " . $this->_customCode
        . " in "  . __METHOD__ . ", error message from API4 CustomField get: " . $ex->getMessage());
    }
  }

  /**
   * Method to get value from custom field
   *
   * @return mixed
   */
  public function getValue() {
    if (!empty($this->_columnName)) {
      // generate query
      $query = "SELECT " . $this->_columnName . " FROM " . $this->_customGroupTableName . " WHERE entity_id = %1";
      $value = CRM_Core_DAO::singleValueQuery($query, [1 => [$this->_contactId, "Integer"]]);
      if ($value) {
        // if required, retrieve option value label
        if ($this->_optionGroupId) {
          try {
            $optionValues = \Civi\Api4\OptionValue::get()
              ->addSelect('label')
              ->addWhere('option_group_id', '=', $this->_optionGroupId)
              ->addWhere('value', '=', $value)
              ->setLimit(1)
              ->execute();
            $found = $optionValues->first();
            if (isset($found['label'])) {
              $value = $found['label'];
            }
          }
          catch (API_Exception $ex) {
          }
        }
        return $value;
      }
    }
    return FALSE;
  }

}

