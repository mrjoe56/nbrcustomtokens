<?php
use CRM_Nbrcustomtokens_ExtensionUtil as E;

/**
 * Class for NIHR BioResource specific token values
 * @author John Boucher     @date 12 Jun 2020       @license AGPL-3.0
 */

class CRM_Nbrcustomtokens_NbrTokenValues {
  /** Method to process the token values hook */

  # job is blank, $context blank

  public function tokenValues(&$values, $cids, $job, $tokens, $context) {

    if (isset($tokens['NBR_Stage_2'])) {

      if (!is_array($cids)){$cids = [$cids];}

      foreach ($cids as $cid) {

        $caseId = CRM_Utils_Request::retrieveValue("caseid", "Integer"); # get case id from url or $values array

        if (!$caseId) {
          if (isset($values[$cid]['case_id'])) {
            $caseId = $values[$cid]['case_id'];
          }
          elseif (isset($values[$cid]['case.id'])) {
            $caseId = $values[$cid]['case.id'];
          }
        }

        if ($caseId) {

          $params = [1 => [$cid, 'Integer'], 2 => [$caseId, 'Integer'],];
          $query = 'select sd.nsd_study_number as study_number, sd.nsd_study_long_name as study_long_name, camp.name as study_short_name, rcont.display_name as researcher,
            radd.street_address as r_addr0, radd.supplemental_address_1 as r_addr1, radd.supplemental_address_2 as r_addr2, radd.supplemental_address_3 as r_addr3,
            radd.postal_code as r_pcode, email.email as r_email, pcont.display_name as investigator, sd.nsd_scientific_info as study_text,  sd.nsd_ethics_number as study_ethics
            from civicrm_case_contact cc
            join civicrm_case cas on cc.case_id = cas.id
            left join civicrm_value_nbr_participation_data pd on cc.case_id = pd.entity_id
            left join civicrm_value_nbr_study_data sd on pd.nvpd_study_id = sd.entity_id
            left join civicrm_campaign camp on sd.entity_id = camp.id
            left join civicrm_contact rcont on sd.nsd_researcher = rcont.id
            left join civicrm_contact pcont on sd.nsd_principal_investigator = pcont.id
            left join civicrm_address radd on sd.nsd_researcher = radd.contact_id
            left join civicrm_email email on radd.contact_id = email.contact_id
            where cc.contact_id = %1 and cc.case_id = %2 and cas.is_deleted = 0 limit 1';

          $dao = CRM_Core_DAO::executeQuery($query, $params);
          if ($dao->fetch()) {
            $values[$cid]['NBR_Stage_2.study_number'] = $dao->study_number;
            $values[$cid]['NBR_Stage_2.study_short_name'] = $dao->study_short_name;
            $values[$cid]['NBR_Stage_2.study_long_name'] = $dao->study_long_name;
            $values[$cid]['NBR_Stage_2.investigator_name'] = $dao->investigator;
            $values[$cid]['NBR_Stage_2.researcher_name'] = $dao->researcher;
            $values[$cid]['NBR_Stage_2.researcher_address0'] = $dao->r_addr0;
            $values[$cid]['NBR_Stage_2.researcher_address1'] = $dao->r_addr1;
            $values[$cid]['NBR_Stage_2.researcher_address2'] = $dao->r_addr2;
            $values[$cid]['NBR_Stage_2.researcher_address3'] = $dao->r_addr3;
            $values[$cid]['NBR_Stage_2.researcher_pcode'] = $dao->r_pcode;
            $values[$cid]['NBR_Stage_2.researcher_email'] = $dao->r_email;
            $values[$cid]['NBR_Stage_2.study_text'] = $dao->study_text;
            $values[$cid]['NBR_Stage_2.study_ethics_number'] = $dao->study_ethics;
          }

        }

      }

    }

    if (isset($tokens['NBR_Contact'])) {
      $params = [1 => [$cid, 'Integer']];
      $query = "select nva_participant_id, nva_bioresource_id from civicrm_value_nihr_volunteer_ids where entity_id = %1";
      $dao = CRM_Core_DAO::executeQuery($query, $params);
      if ($dao->fetch()) {
        $values[$cid]['NBR_Contact.participant_id'] = $dao->nva_participant_id;
        $values[$cid]['NBR_Contact.bioresource_id'] = $dao->nva_bioresource_id;
      }

    }

  }

}
