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
  private $_customFieldData = [];
  private $_customGroupTable = NULL;
  private $_customCode;
  private $_contactId;

  /**
   * CRM_Nbrcustomtokens_CustomField constructor.
   * @param $customCode
   */
  public function __construct($customCode, $contactId) {
    $this->_customCode = $customCode;
    $this->_contact = $contactId;
  }

  /**
   * Method to set all the properties and return false if something is wrong
   */
  public function initialize() {

  }
  private function setCustomFieldData() {

  }
  public function getValue() {

  }
  private function setCustomGroupTable() {

  }

}

