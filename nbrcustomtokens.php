<?php

require_once 'nbrcustomtokens.civix.php';
use CRM_Nbrcustomtokens_ExtensionUtil as E;

/**
 * Implements hook_civicrm_tokens().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tokens/
 */
function nbrcustomtokens_civicrm_tokens(&$tokens) {
  $nbrTokens = new CRM_Nbrcustomtokens_NbrTokens();
  $nbrTokens->tokens($tokens);
}

/**
 * Implements hook_civicrm_tokenValues().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tokenValues/
 */

function nbrcustomtokens_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  $tokenValues = new CRM_Nbrcustomtokens_NbrTokenValues();
  $tokenValues->tokenValues($values, $cids, $job, $tokens, $context);
}


/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function nbrcustomtokens_civicrm_config(&$config) {
  _nbrcustomtokens_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function nbrcustomtokens_civicrm_xmlMenu(&$files) {
  _nbrcustomtokens_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function nbrcustomtokens_civicrm_install() {
  _nbrcustomtokens_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function nbrcustomtokens_civicrm_postInstall() {
  _nbrcustomtokens_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function nbrcustomtokens_civicrm_uninstall() {
  _nbrcustomtokens_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function nbrcustomtokens_civicrm_enable() {
  _nbrcustomtokens_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function nbrcustomtokens_civicrm_disable() {
  _nbrcustomtokens_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function nbrcustomtokens_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _nbrcustomtokens_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function nbrcustomtokens_civicrm_managed(&$entities) {
  _nbrcustomtokens_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function nbrcustomtokens_civicrm_caseTypes(&$caseTypes) {
  _nbrcustomtokens_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function nbrcustomtokens_civicrm_angularModules(&$angularModules) {
  _nbrcustomtokens_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function nbrcustomtokens_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _nbrcustomtokens_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function nbrcustomtokens_civicrm_entityTypes(&$entityTypes) {
  _nbrcustomtokens_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function nbrcustomtokens_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function nbrcustomtokens_civicrm_navigationMenu(&$menu) {
  _nbrcustomtokens_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _nbrcustomtokens_civix_navigationMenu($menu);
} // */
