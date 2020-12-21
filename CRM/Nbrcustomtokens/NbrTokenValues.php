<?php
use CRM_Nbrcustomtokens_ExtensionUtil as E;

/**
 * Class for NIHR BioResource specific token values
 * @author John Boucher     @date 12/06/20       @license AGPL-3.0
 * updated 02/11/20
 */

class CRM_Nbrcustomtokens_NbrTokenValues
{
  /** Method to process the token values hook */

  public function tokenValues(&$values, $pids, $job, $tokens, $context)
  {

    if (!empty($job)) {                                                                              # BULK EMAIL (event queue job id exists)
      $params = [1 => [$job, 'Integer']];
      $query = "select r.contact_id as pid, m.study_id as study_id, mj.mailing_id as mailing_id
                from civicrm_mailing_job mj, civicrm_nbr_mailing m, civicrm_mailing_recipients r
                where m.mailing_id = mj.mailing_id and m.mailing_id = r.mailing_id
                and mj.id = %1";
      $dao = CRM_Core_DAO::executeQuery($query, $params);
      while ($dao->fetch()) {                                                                       # for each pid
        $study_id = $dao->study_id;                                                                 #  get study_id
        $pid = $dao->pid;
        $caseId = CRM_Nihrbackbone_NbrVolunteerCase::getActiveParticipationCaseId($study_id, $pid); #  and case_id
        if (isset($tokens['NBR_Stage_2'])) {                                                        #  set stage2 tokens for pid
          $this->setStage2TokenValues($values, $pid, $caseId);
        }
        if (isset($tokens['NBR_Contact'])) {
          $this->setNbrContactTokenValues($values, $pid);                                           # set contact tokens for pid
        }
      }
    } else {                                                                                          # NOT BULK EMAIL
      if (!is_array($pids)) {
        $pids = [$pids];
      }
      foreach ($pids as $pid) {                                                                     # for each pid
        $caseId = $this->getParticipationCaseId($pid, $context, $values);
        if ($caseId) {
          if (isset($tokens['NBR_Stage_2'])) {                                                      #  set stage2 tokens for pid
            $this->setStage2TokenValues($values, $pid, $caseId);
          }
          if (isset($tokens['NBR_Contact'])) {                                                      # set contact tokens for pid
            $this->setNbrContactTokenValues($values, $pid);
          }
        }
      }
    }
  }

  public function setNbrContactTokenValues(&$values, $pid) {
    $params = [1 => [$pid, 'Integer']];
    $query = "select nva_participant_id, nva_bioresource_id from civicrm_value_nihr_volunteer_ids where entity_id = %1";
    $dao = CRM_Core_DAO::executeQuery($query, $params);
    if ($dao->fetch()) {
      $values[$pid]['NBR_Contact.participant_id'] = $dao->nva_participant_id;
      $values[$pid]['NBR_Contact.bioresource_id'] = $dao->nva_bioresource_id;
    }
  }

  public function setStage2TokenValues(&$values, $pid, $caseId)
  {
    $params = [1 => [$pid, 'Integer'], 2 => [$caseId, 'Integer'],];
    $query = "select sd.nsd_study_number as study_number, sd.nsd_study_long_name as study_long_name, camp.name as study_short_name, rcont.display_name as researcher,
            radd.street_address as r_addr0, radd.supplemental_address_1 as r_addr1, radd.supplemental_address_2 as r_addr2, radd.supplemental_address_3 as r_addr3,
            radd.postal_code as r_pcode, email.email as r_email, pcont.display_name as investigator, sd.nsd_scientific_info as study_text,  sd.nsd_ethics_number as study_ethics,
            sd.nsd_lay_summary as study_summary, pd.nvpd_study_participant_id as study_participant_id
            from civicrm_case_contact cc
            join civicrm_case cas on cc.case_id = cas.id
            left join civicrm_value_nbr_participation_data pd on cc.case_id = pd.entity_id
            left join civicrm_value_nbr_study_data sd on pd.nvpd_study_id = sd.entity_id
            left join civicrm_campaign camp on sd.entity_id = camp.id
            left join civicrm_contact rcont on sd.nsd_researcher = rcont.id
            left join civicrm_contact pcont on sd.nsd_principal_investigator = pcont.id
            left join civicrm_address radd on sd.nsd_researcher = radd.contact_id
            left join civicrm_email email on radd.contact_id = email.contact_id
            where coalesce(pd.nvpd_study_participant_id, '') != ''
            and cc.contact_id = %1 and cc.case_id = %2 and cas.is_deleted = 0 limit 1";
    $dao = CRM_Core_DAO::executeQuery($query, $params);
    if ($dao->fetch()) {
      $values[$pid]['NBR_Stage_2.study_number'] = $dao->study_number;
      $values[$pid]['NBR_Stage_2.study_short_name'] = $dao->study_short_name;
      $values[$pid]['NBR_Stage_2.study_long_name'] = $dao->study_long_name;
      $values[$pid]['NBR_Stage_2.investigator_name'] = $dao->investigator;
      $values[$pid]['NBR_Stage_2.researcher_name'] = $dao->researcher;
      $values[$pid]['NBR_Stage_2.researcher_address0'] = $dao->r_addr0;
      $values[$pid]['NBR_Stage_2.researcher_address1'] = $dao->r_addr1;
      $values[$pid]['NBR_Stage_2.researcher_address2'] = $dao->r_addr2;
      $values[$pid]['NBR_Stage_2.researcher_address3'] = $dao->r_addr3;
      $values[$pid]['NBR_Stage_2.researcher_pcode'] = $dao->r_pcode;
      $values[$pid]['NBR_Stage_2.researcher_email'] = $dao->r_email;
      $values[$pid]['NBR_Stage_2.study_text'] = $dao->study_text;
      $values[$pid]['NBR_Stage_2.study_ethics_number'] = $dao->study_ethics;
      $values[$pid]['NBR_Stage_2.study_lay_summary'] = $dao->study_summary;
      $values[$pid]['NBR_Stage_2.study_participant_id'] = $dao->study_participant_id;
    }
  }
  /**
   * Method to get case id either from request or from session
   *
   * @param $contactId
   * @param $context
   * @param $values
   * @return false|int|mixed|string
   * @throws CRM_Core_Exception
   */
  private function getParticipationCaseId($contactId, $context, $values) {
    // if context = email or PDF
    if ($context == "CRM_Contact_Form_Task_PDFLetterCommon" || $context == "CRM_Activity_BAO_Activity") {
      // if so try to retrieve case_id from session
      $session = CRM_Core_Session::singleton();
      if (isset($session->nbr_email_pdf_case_ids)) {
        $emailCaseIds = $session->nbr_email_pdf_case_ids;
        if (isset($emailCaseIds[$contactId])) {
          return (int) $emailCaseIds[$contactId];
        }
      }
    }
    // in all other situations, check if the case_id is in the request or values
    $caseId = CRM_Utils_Request::retrieveValue("caseid", "Integer");
    if ($caseId) {
      return $caseId;
    }
    if (isset($values[$contactId]['case_id'])) {
      return $values[$contactId]['case_id'];
    } elseif (isset($values[$contactId]['case.id'])) {
      return $values[$contactId]['case.id'];
    }
    return FALSE;
  }

}
