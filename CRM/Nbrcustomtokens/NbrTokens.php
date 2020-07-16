<?php
use CRM_Nbrcustomtokens_ExtensionUtil as E;

/**
 * Class for NIHR BioResource specific tokens
 *
 * @author John Boucher
 * @date 12 Jun 2020
 * @license AGPL-3.0
 */
class CRM_Nbrcustomtokens_NbrTokens {

  /** Method to process the tokens hook  */
  public function tokens(&$tokens) {

    $tokens['NBR_Stage_2'] = [
      'NBR_Stage_2.study_number' => 'Study number',
      'NBR_Stage_2.study_short_name' => 'Study short name',   # internal token name and token label shown to user
      'NBR_Stage_2.study_long_name' => 'Study long name',
      'NBR_Stage_2.investigator_name' => 'Study PI',
      'NBR_Stage_2.researcher_name' => 'Study researcher',
      'NBR_Stage_2.researcher_address0' => 'Researcher addr street',
      'NBR_Stage_2.researcher_address1' => 'Researcher addr 1',
      'NBR_Stage_2.researcher_address2' => 'Researcher addr 2',
      'NBR_Stage_2.researcher_address3' => 'Researcher addr 3',
      'NBR_Stage_2.researcher_pcode' => 'Researcher postcode',
      'NBR_Stage_2.researcher_email' => 'Researcher email',
      'NBR_Stage_2.study_text' => 'Study text',
    ];


    $tokens['NBR_Contact'] = [
      'NBR_Contact.participant_id' => 'Participant ID',
      'NBR_Contact.bioresource_id' => 'Bioresource ID',
    ];

  }
}

