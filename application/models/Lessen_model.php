<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lessen_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getLessen($aantal = 0, $pagina = 1)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->order_by('workshops.workshop_ID', 'ASC');
        $this->db->order_by('lessen.les_positie', 'ASC');
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenWorkshop_ID($aantal = 0, $pagina = 1, $workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->where('workshops.workshop_ID', $workshop_ID);
        $this->db->order_by('workshops.workshop_ID', 'ASC');
        $this->db->order_by('lessen.les_positie', 'ASC');
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

    function getGratisLessen()
    {
        $this->db->select('*');
        $this->db->from('gratis_lessen');
        $query = $this->db->get();
        return $query->result();
    }

    function getGratisLesByID($item_ID) {
        $this->db->select('*');
        $this->db->from('gratis_lessen');
        $this->db->where('les_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getLesTypes() {
        $this->db->select('*');
        $this->db->from('les_types');
        $query = $this->db->get();

        return $query->result();
    }

    function getLesTypeByID($item_ID) {
        $this->db->select('*');
        $this->db->from('les_types');
        $this->db->where('les_type_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenDocent($aantal = 0, $pagina = 1, $docent_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('docent_ID', $docent_ID);
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->order_by('workshops.workshop_ID', 'ASC');
        $this->db->order_by('lessen.les_positie', 'ASC');
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenAantal()
    {
        $this->db->select('*');
        $this->db->from('lessen');
        return $this->db->count_all_results();
    }

    function getLessenAantalWorkshop($workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('workshop_ID', $workshop_ID);
        return $this->db->count_all_results();
    }

    function getLessenDocentWorkshop($aantal = 0, $pagina = 1, $docent_ID, $workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('docent_ID', $docent_ID);
        $this->db->where('lessen.workshop_ID', $workshop_ID);
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->order_by('workshops.workshop_ID', 'ASC');
        $this->db->order_by('lessen.les_positie', 'ASC');
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenAantalDocentWorkshop($docent_ID, $workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('docent_ID', $docent_ID);
        $this->db->where('lessen.workshop_ID', $workshop_ID);
        return $this->db->count_all_results();
    }

    function getLessenAantalDocent($docent_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('docent_ID', $docent_ID);
        return $this->db->count_all_results();
    }

    function getLessenByWorkshopID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('groep_ID', null);
        $this->db->order_by('les_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenWorkshop($item_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('groep_ID', null);
        $this->db->order_by('les_positie', 'ADC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenByGroepID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen_lessen');
        $this->db->where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', null);
        $this->db->or_where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', $item_ID);
        $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID');
        $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID');
        $this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'left');
        $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenByGroepIDPositie($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen_lessen');
        $this->db->where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', null);
        $this->db->or_where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', $item_ID);
        $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID');
        $this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'left');
        $this->db->order_by('lessen.les_positie', 'ASC');
        $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenByGroepIDNormaalDatum($item_ID)
    {
        $this->db->select('
        groepen_lessen.groep_les_ID,
        groepen_lessen.groep_les_datum,
        groepen_lessen.groep_les_eindtijd,
        groepen_lessen.les_locatie_ID,
        groepen_lessen.les_voorbereidingsmail_verstuurd,
        groepen_lessen.les_gekoppeld_aan_ID,
        groepen_lessen.technicus,
        groepen_lessen.docent_ID as groepsles_docent_ID,
        lessen.les_ID,
        lessen.docent_ID as les_docent_ID,
        lessen.les_locatie,
        lessen.les_titel,
        lessen.les_beschrijving,
        groepen_lessen.groep_les_adres,
        groepen_lessen.groep_les_postcode,
        groepen_lessen.groep_les_plaats,
        lessen.les_huiswerk_aantal,
        lessen.les_type_ID,
        locaties.locatie_ID,
        locaties.locatie_adres
    ');
        $this->db->from('groepen_lessen');
        $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID');
        $this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'left');
        $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID');
        $this->db->where('les_types.les_beschikbaar', 0);
        $this->db->where('les_types.les_gekoppeld_aan', 0);
        $this->db->where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', null);
        $this->db->or_where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', $item_ID);
        $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenByGroepIDBeschikbaar($item_ID)
    {
        $this->db->select('
            groepen_lessen.groep_les_ID,
            groepen_lessen.groep_les_datum,
            groepen_lessen.groep_les_eindtijd,
            groepen_lessen.les_locatie_ID,
            groepen_lessen.les_voorbereidingsmail_verstuurd,
            groepen_lessen.les_gekoppeld_aan_ID,
            groepen_lessen.docent_ID as groepsles_docent_ID,
            lessen.les_ID,
            lessen.docent_ID as les_docent_ID,
            lessen.les_locatie,
            lessen.les_titel,
            lessen.les_beschrijving,
            groepen_lessen.groep_les_adres,
            groepen_lessen.groep_les_postcode,
            groepen_lessen.groep_les_plaats,
            lessen.les_huiswerk_aantal,
            lessen.les_type_ID
        ');
        $this->db->from('groepen_lessen');
        $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID');
        $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID', 'left');
        $this->db->where('les_types.les_beschikbaar', 1);
        $this->db->where('les_types.les_gekoppeld_aan', 0);
        $this->db->where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', null);
        $this->db->or_where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', $item_ID);
        $this->db->where('les_types.les_type_ID !=', 21);
        $this->db->order_by('lessen.les_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getVoorbereidendeLessenByGroepID($item_ID)
    {
        $this->db->select('
            groepen_lessen.groep_les_ID,
            groepen_lessen.groep_les_datum,
            groepen_lessen.groep_les_eindtijd,
            groepen_lessen.les_locatie_ID,
            groepen_lessen.les_voorbereidingsmail_verstuurd,
            groepen_lessen.les_gekoppeld_aan_ID,
            groepen_lessen.les_dagen_ervoor_beschikbaar,
            groepen_lessen.docent_ID as groepsles_docent_ID,
            lessen.les_ID,
            lessen.docent_ID as les_docent_ID,
            lessen.les_locatie,
            lessen.les_titel,
            lessen.les_beschrijving,
            groepen_lessen.groep_les_adres,
            groepen_lessen.groep_les_postcode,
            groepen_lessen.groep_les_plaats,
            lessen.les_huiswerk_aantal,
            lessen.les_type_ID
        ');
        $this->db->from('groepen_lessen');
        $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID');
        $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID');
        $this->db->where('les_types.les_beschikbaar', 0);
        $this->db->where('les_types.les_gekoppeld_aan', 1);
        $this->db->where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', null);
        $this->db->or_where('groepen_lessen.groep_ID', $item_ID)->where('lessen.groep_ID', $item_ID);
        $this->db->where('les_types.les_type_ID !=', 21);
        $this->db->order_by('lessen.les_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenByGebruikerID($gebruiker_ID, $workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('individuen_lessen');
        $this->db->where('individuen_lessen.gebruiker_ID', $gebruiker_ID);
        $this->db->where('individuen_lessen.workshop_ID', $workshop_ID);
        $this->db->join('lessen', 'lessen.les_ID = individuen_lessen.les_ID');
        $this->db->join('resultaten', 'resultaten.les_ID = lessen.les_ID AND stem_resultaten.gebruiker_ID = stem_individuen_lessen.gebruiker_ID', 'left');
        $this->db->order_by('individuen_lessen.individu_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getIndividueelLessenByGebruikerID($gebruiker_ID)
    {
        $this->db->select('individuen_lessen.gebruiker_ID, individuen_lessen.les_ID, individu_les_datum as les_datum, lessen.workshop_ID, individuen_lessen.les_voorbereidingsmail_verstuurd');
        $this->db->from('individuen_lessen');
        $this->db->where('individuen_lessen.gebruiker_ID', $gebruiker_ID);
        $this->db->join('lessen', 'lessen.les_ID = individuen_lessen.les_ID');
        $this->db->join('aanmeldingen', 'aanmeldingen.gebruiker_ID = individuen_lessen.gebruiker_ID');
        $this->db->where('aanmeldingen.workshop_ID', 'individuen_lessen.workshop_ID');
        $this->db->where('aanmeldingen.aanmelding_verlopen', 0);
        $this->db->order_by('lessen.workshop_ID', 'DESC');
        $this->db->order_by('individuen_lessen.individu_les_datum', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    function getExtraLesByIDandGroepID($les_ID, $item_ID) {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('les_ID', $les_ID);
        $this->db->where('groep_ID', $item_ID);
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getLiveLesByIDandGroepID($les_ID, $item_ID) {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('les_ID', $les_ID);
        $this->db->where('groep_ID', $item_ID);
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getExtraLessenByWorkshopIDandGroepID ($workshop_ID, $item_ID) {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('workshop_ID', $workshop_ID);
        $this->db->where('groep_ID', $item_ID);
        $this->db->order_by('les_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getGroepLessenByGebruikerIDenWorkshopID($gebruiker_ID, $workshop_ID)
    {
        $this->db->select('gebruikers.gebruiker_ID, groepen_lessen.les_ID, groep_les_datum as les_datum, lessen.workshop_ID');
        $this->db->from('groepen_lessen');
        $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID');
        $this->db->join('aanmeldingen', 'groepen.groep_ID = aanmeldingen.groep_ID');
        $this->db->join('gebruikers', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID');
        $this->db->where('gebruikers.gebruiker_ID', $gebruiker_ID);
        $this->db->where('lessen.workshop_ID', $workshop_ID);
        $this->db->where('aanmeldingen.aanmelding_verlopen', 0);
        $this->db->order_by('lessen.workshop_ID', 'DESC');
        $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getIndividueelLessenByGebruikerIDenWorkshopID($gebruiker_ID, $workshop_ID)
    {
        $this->db->select('individuen_lessen.gebruiker_ID, individuen_lessen.les_ID, individu_les_datum as les_datum, lessen.workshop_ID');
        $this->db->from('individuen_lessen');
        $this->db->where('individuen_lessen.gebruiker_ID', $gebruiker_ID);
        $this->db->join('lessen', 'lessen.les_ID = individuen_lessen.les_ID');
        $this->db->order_by('lessen.workshop_ID', 'DESC');
        $this->db->where('lessen.workshop_ID', $workshop_ID);
        $this->db->order_by('individuen_lessen.individu_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getIndividueelLessenByLesIDenWorkshopID($les_ID, $workshop_ID)
    {
        $this->db->select('*');
        $this->db->from('individuen_lessen');
        $this->db->join('gebruikers', 'individuen_lessen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->where('individuen_lessen.workshop_ID', $workshop_ID);
        $this->db->where('individuen_lessen.les_ID', $les_ID);
        $this->db->where('individuen_lessen.les_ID', $les_ID);
        $this->db->where('individuen_lessen.les_voorbereidingsmail_verstuurd', 0);
        $query = $this->db->get();
        return $query->result();
    }


    function getGroepLessenByGebruikerID($gebruiker_ID)
    {
        $this->db->select('gebruikers.gebruiker_ID, groepen_lessen.les_ID, groep_les_datum as les_datum, lessen.workshop_ID, groepen_lessen.les_voorbereidingsmail_verstuurd, groepen_lessen.groep_ID');
        $this->db->from('groepen_lessen');
        $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID');
        $this->db->join('aanmeldingen', 'groepen.groep_ID = aanmeldingen.groep_ID');
        $this->db->join('gebruikers', 'aanmeldingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID');
        $this->db->where('gebruikers.gebruiker_ID', $gebruiker_ID);
        $this->db->where('lessen.groep_ID', null);
        $this->db->where('aanmeldingen.aanmelding_verlopen', 0);
        $this->db->order_by('lessen.workshop_ID', 'DESC');
        $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getGroepLessenByWorkshopID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('groep_aanmelden', 'ja');
        $this->db->order_by('groep_startdatum', 'ASC');
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $groep = $query->row();

            $this->db->select('*');
            $this->db->from('groepen_lessen');
            $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID', 'left');
            $this->db->where('groepen_lessen.groep_ID', $groep->groep_ID);
            $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
            $query = $this->db->get();

            if($query->num_rows() > 0) return $query->result();
            else return null;
        }
        else return null;
    }

    function getGroepLessenByWorkshopIDMigratie($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->order_by('groep_startdatum', 'ASC');
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $groep = $query->row();

            $this->db->select('*');
            $this->db->from('groepen_lessen');
            $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID', 'left');
            $this->db->where('groepen_lessen.groep_ID', $groep->groep_ID);
            $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
            $query = $this->db->get();

            if($query->num_rows() > 0) return $query->result();
            else return null;
        }
        else return null;
    }

    function getGroepAanmeldenLessenByWorkshopID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('groep_aanmelden', 'ja');
        $this->db->where('groep_startdatum >', date('Y-m-d H:i:s'));
        $this->db->order_by('groep_startdatum', 'ASC');
        $query = $this->db->get();

        if($query->num_rows() > 0)
            {
                $groepen = array();

                foreach($query->result() as $row) {
                    $groepen[] = $row->groep_ID;
                }

                $this->db->select('groep_les_ID, groep_les_datum, groep_les_eindtijd, les_locatie_ID, groep_les_adres, groep_les_postcode, groep_les_plaats, les_voorbereidingsmail_verstuurd, groepen_lessen.groep_ID, lessen.les_ID, les_positie, les_titel, les_beschrijving, lessen.les_type_ID, les_huiswerk, les_huiswerk_aantal, les_locatie, les_voorbereidingsmail, lessen.workshop_ID, groepen_lessen.docent_ID, groepen.groep_titel');
                $this->db->from('groepen_lessen');
                $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID', 'left');
                $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID', 'left');
                $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID', 'left');
                $this->db->where('lessen.groep_ID', null);
                $this->db->where_in('groepen_lessen.groep_ID', $groepen);
                $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
                $query = $this->db->get();
            if($query->num_rows() > 0) return $query->result();
            else return null;
        }
        else return null;
    }

    function getGroepAanmeldenLessenByWorkshopIDUitgebreid($item_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('groep_aanmelden', 'ja');
        $this->db->where('groep_startdatum >', date('Y-m-d H:i:s'));
        $this->db->order_by('groep_startdatum', 'ASC');
        $query = $this->db->get();

        if($query->num_rows() > 0)
            {
                $groepen = array();

                foreach($query->result() as $row) {
                    $groepen[] = $row->groep_ID;
                }

                $this->db->select('groep_les_ID, groep_les_datum, groep_les_eindtijd, les_locatie_ID, groep_les_adres, groep_les_postcode, groep_les_plaats, les_voorbereidingsmail_verstuurd, groepen_lessen.groep_ID, lessen.les_ID, les_positie, les_titel, les_beschrijving, lessen.les_type_ID, les_huiswerk, les_huiswerk_aantal, les_locatie, les_voorbereidingsmail, lessen.workshop_ID, groepen_lessen.docent_ID, groepen.groep_titel');
                $this->db->from('groepen_lessen');
                $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID', 'left');
                $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID', 'left');
                $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID', 'left');
                $this->db->where('les_types.les_weergeven', 1);
                $this->db->where('lessen.groep_ID', null);
                $this->db->where_in('groepen_lessen.groep_ID', $groepen);
                $this->db->order_by('groepen_lessen.groep_les_datum', 'ASC');
                $query = $this->db->get();
            if($query->num_rows() > 0) return $query->result();
            else return null;
        }
        else return null;
    }

    function getLessenIndividueelByWorkshopID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('individuen_lessen');
        $this->db->where('workshop_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getGroepLesByLesID($les_ID, $groep_ID)
    {
        $this->db->select('*');
        $this->db->from('groepen_lessen');
        $this->db->where('les_ID', $les_ID);
        $this->db->where('groep_ID', $groep_ID);
        $this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'left');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
    else return null;
    }

    function getUitnodigingenMetGroepLes()
    {
        $this->db->select('*');
        $this->db->join('groepen_lessen', 'uitnodigingen.les_ID = groepen_lessen.les_ID');
        $this->db->from('uitnodigingen');

        $query = $this->db->get();

        return $query->result();
    }
    function getLesByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->where('les_ID', $item_ID);
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID', 'left');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getGroepLesByID($item_ID)
    {
        $this->db->select('*, groepen_lessen.docent_ID');
        $this->db->from('groepen_lessen');
        $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->join('groepen', 'groepen.groep_ID = groepen_lessen.groep_ID');
        $this->db->join('les_types', 'les_types.les_type_ID = lessen.les_type_ID', 'left');
        $this->db->join('locaties', 'groepen_lessen.les_locatie_ID = locaties.locatie_ID', 'left');
        $this->db->where('groep_les_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getLesIndividuByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('individuen_lessen');
        $this->db->join('lessen', 'lessen.les_ID = individuen_lessen.les_ID');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->where('individu_les_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getLesIndibiduByLesIDenGebruiker_ID($item_ID, $gebruiker_ID)
    {
        $this->db->select('*');
        $this->db->from('individuen_lessen');
        $this->db->join('lessen', 'lessen.les_ID = individuen_lessen.les_ID');
        $this->db->join('gebruikers', 'individuen_lessen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->where('individuen_lessen.les_ID', $item_ID);
        $this->db->where('individuen_lessen.gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getKalenderLessen()
    {
        $this->db->select('*, groepen_lessen.docent_ID');
        $this->db->from('groepen_lessen');
        $this->db->join('lessen', 'lessen.les_ID = groepen_lessen.les_ID');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->join('groepen', 'groepen.groep_ID = groepen_lessen.groep_ID');
        $query = $this->db->get();
        return $query->result();
    }



    ////////////
    // INSERT //
    ////////////

    function insertLes($data)
    {
        $this->db->insert('lessen', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function insertLesBekeken($data)
    {
        $this->db->insert('lessen_bekeken', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function insertGratisLes($data)
    {
        $this->db->insert('gratis_lessen', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function insertGroepLes($data)
    {
        $this->db->insert('groepen_lessen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function insertLesIndividu($data)
    {
        $this->db->insert('individuen_lessen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function insertLesType($data)
    {
        $this->db->insert('les_types', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }


    ////////////
    // UPDATE //
    ////////////

    function updateLes($item_ID, $data)
    {
        $this->db->where('les_ID', $item_ID);
        $this->db->update('lessen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function updateGratisLes($item_ID, $data)
    {
        $this->db->where('les_ID', $item_ID);
        $this->db->update('gratis_lessen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function updateGroepLes($item_ID, $data)
    {
        $this->db->where('groep_les_ID', $item_ID);
        $this->db->update('groepen_lessen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function updateIndividuLes($item_ID, $data)
    {
        $this->db->where('individu_les_ID', $item_ID);
        $this->db->update('individuen_lessen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function updateLesType($item_ID, $data)
    {
        $this->db->where('les_type_ID', $item_ID);
        $this->db->update('les_types', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }



    ////////////
    // DELETE //
    ////////////

    function deleteLes($item_ID)
    {
        $this->db->where('les_ID', $item_ID);
        $this->db->delete('lessen');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteBeoordelingscriteria($item_ID)
	{
		$this->db->where('beoordelingscriteria_ID', $item_ID);
		$this->db->delete('beoordelingscriteria');
		if($this->db->affected_rows() == 1) return true;
		else return false;
	}

    function deleteGratisLes($item_ID)
    {
        $this->db->where('les_ID', $item_ID);
        $this->db->delete('gratis_lessen');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteGroepLesByLesID($item_ID)
    {
        $this->db->where('les_ID', $item_ID);
        $this->db->delete('groepen_lessen');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteGroepLesByID($item_ID)
    {
        $this->db->where('groep_les_ID', $item_ID);
        $this->db->delete('groepen_lessen');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function deleteLesType($item_ID) {
        $this->db->where('les_type_ID', $item_ID);
        $this->db->delete('les_types');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function getLessenBeoordeling() {
        $this->db->select('*');
        $this->db->from('lessen');
        $this->db->join('workshops', 'workshops.workshop_ID = lessen.workshop_ID');
        $this->db->order_by('workshops.workshop_ID', 'ASC');
        $this->db->order_by('lessen.les_positie', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getLesBekeken($gebruiker_ID, $les_ID) {
        $this->db->select('*');
        $this->db->from('lessen_bekeken');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->where('les_ID', $les_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenBekeken($gebruiker_ID, $workshop_ID) {
        $this->db->select('*');
        $this->db->from('lessen_bekeken');
        $this->db->join('workshops', 'lessen_bekeken.workshop_ID = workshops.workshop_ID');
        $this->db->join('lessen', 'lessen_bekeken.les_ID = lessen.les_ID');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->where('lessen_bekeken.workshop_ID', $workshop_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenBekekenGebruiker($gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('lessen_bekeken');
        $this->db->join('workshops', 'lessen_bekeken.workshop_ID = workshops.workshop_ID');
        $this->db->join('lessen', 'lessen_bekeken.les_ID = lessen.les_ID');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $this->db->order_by("workshop_titel", "asc");
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordelingenCompleet() {
        $this->db->select('lessen.les_ID, gebruikers.gebruiker_ID, workshops.workshop_ID, gebruikers.gebruiker_naam, workshops.workshop_titel, lessen.les_titel, les_beoordelingen.les_beoordeling');
        $this->db->from('les_beoordelingen');
        $this->db->join('lessen', 'les_beoordelingen.les_ID = lessen.les_ID');
        $this->db->join('workshops', 'lessen.workshop_ID = workshops.workshop_ID');
        $this->db->join('gebruikers', 'les_beoordelingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->order_by('workshops.workshop_titel', 'ASC');
        $this->db->order_by('lessen.les_titel', 'ASC');
        $this->db->order_by('les_beoordelingen.les_beoordeling', 'ASC');
        $this->db->limit(500);
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordelingscriteria() {
        $this->db->select('*');
        $this->db->from('beoordelingscriteria');
        $this->db->where('beoordelingscriteria_niveau', NULL);
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordelingscriteriaVWS() {
        $this->db->select('*');
        $this->db->from('beoordelingscriteria');
        $this->db->where('beoordelingscriteria_niveau', 5);
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordelingenCompleetByGebruikerID($gebruiker_ID) {
        $this->db->select('*');
        $this->db->from('les_beoordelingen');
        $this->db->join('lessen', 'les_beoordelingen.les_ID = lessen.les_ID');
        $this->db->join('workshops', 'lessen.workshop_ID = workshops.workshop_ID');
        $this->db->join('gebruikers', 'les_beoordelingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->where('gebruikers.gebruiker_ID', $gebruiker_ID);
        $this->db->order_by('workshops.workshop_titel', 'ASC');
        $this->db->order_by('lessen.les_titel', 'ASC');
        $this->db->order_by('les_beoordelingen.les_beoordeling', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordelingscriteriaByID($item_ID) {
	    $this->db->select('*');
        $this->db->from('beoordelingscriteria');
        $this->db->where('beoordelingscriteria_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getRecenteBeoordelingen() {
        $this->db->select('lessen.les_ID, gebruikers.gebruiker_ID, workshops.workshop_ID, gebruikers.gebruiker_naam, workshops.workshop_titel, lessen.les_titel, les_beoordelingen.les_beoordeling');
        $this->db->from('les_beoordelingen');
        $this->db->join('lessen', 'les_beoordelingen.les_ID = lessen.les_ID');
        $this->db->join('workshops', 'lessen.workshop_ID = workshops.workshop_ID');
        $this->db->join('gebruikers', 'les_beoordelingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->order_by('les_beoordelingen.les_beoordeling_ID', 'DESC');
        $this->db->limit(20);
        $query = $this->db->get();
        return $query->result();
    }

    function getAVGlesBeoordeling($item_ID) {
        $this->db->select('ROUND(AVG(les_beoordeling)) as les_beoordeling');
        $this->db->from('les_beoordelingen');
        $this->db->where('les_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getLessenBeoordelingByID($item_ID) {
        $this->db->select('*');
        $this->db->from('les_beoordelingen');
        $this->db->join('gebruikers', 'les_beoordelingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->where('les_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordeling($gebruiker_ID, $les_ID) {
        $this->db->select('*');
        $this->db->from('les_beoordelingen');
        $this->db->where('les_ID', $les_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getBeoordelingByID($item_ID) {
        $this->db->select('*');
        $this->db->from('les_beoordelingen');
        $this->db->join('lessen', 'les_beoordelingen.les_ID = lessen.les_ID');
        $this->db->join('workshops', 'lessen.workshop_ID = workshops.workshop_ID');
        $this->db->join('gebruikers', 'les_beoordelingen.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->where('les_beoordeling_ID', $item_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function insertBeoordeling($data) {
        $this->db->insert('les_beoordelingen', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function getBeoordelingscriteriaAndHuiswerk($huiswerk_ID) {
	    $this->db->select('*');
        $this->db->from('beoordelingscriteria_resultaat');
        $this->db->join('beoordelingscriteria', 'beoordelingscriteria_resultaat.beoordelingscriteria_ID = beoordelingscriteria.beoordelingscriteria_ID');
        $this->db->join('huiswerk', 'beoordelingscriteria_resultaat.huiswerk_ID = huiswerk.huiswerk_ID');
        $this->db->where('beoordelingscriteria_resultaat.huiswerk_ID', $huiswerk_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function updateBeoordelingscriteriaResultaat($resultaat_ID, $beoordelingscriteria_ID, $data) {
        $this->db->where('resultaat_ID', $resultaat_ID);
        $this->db->where('beoordelingscriteria_ID', $beoordelingscriteria_ID);
        $this->db->update('beoordelingscriteria_resultaat', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function insertBeoordelingscriteria($data) {
        $this->db->insert('beoordelingscriteria', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function insertBeoordelingscriteriaResultaat($data) {
        $this->db->insert('beoordelingscriteria_resultaat', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function deleteBeoordelingscriteriaResultaat($resultaat_ID) {
        $this->db->where('resultaat_ID', $resultaat_ID);
		$this->db->delete('beoordelingscriteria_resultaat');
		if($this->db->affected_rows() == 1) return true;
		else return false;
    }

    function updateBeoordeling($item_ID, $data) {
        $this->db->where('les_beoordeling_ID', $item_ID);
        $this->db->update('les_beoordelingen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function updateBeoordelingscriteria($item_ID, $data) {
        $this->db->where('beoordelingscriteria_ID', $item_ID);
        $this->db->update('beoordelingscriteria', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }


    function getAVGGebruikerBeoordeling($gebruiker_ID, $item_ID) {
        $this->db->select('ROUND(AVG(gebruiker_beoordeling)) as gebruiker_beoordeling');
        $this->db->from('gebruiker_beoordelingen');
        $this->db->join('lessen', 'gebruiker_beoordelingen.les_ID = lessen.les_ID');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getAVGGebruikerBeoordelingOveral($gebruiker_ID) {
        $this->db->select('ROUND(AVG(gebruiker_beoordeling)) as gebruiker_beoordeling');
        $this->db->from('gebruiker_beoordelingen');
        $this->db->join('lessen', 'gebruiker_beoordelingen.les_ID = lessen.les_ID');
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getGebruikerBeoordelingByID($gebruiker_ID, $item_ID) {
        $this->db->select('*');
        $this->db->from('gebruiker_beoordelingen');
        $this->db->join('lessen', 'gebruiker_beoordelingen.les_ID = lessen.les_ID');
        $this->db->where('workshop_ID', $item_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function getGebruikerBeoordeling($gebruiker_ID, $les_ID) {
        $this->db->select('*');
        $this->db->from('gebruiker_beoordelingen');
        $this->db->where('les_ID', $les_ID);
        $this->db->where('gebruiker_ID', $gebruiker_ID);
        $query = $this->db->get();
        return $query->result();
    }

    function insertGebruikerBeoordeling($data) {
        $this->db->insert('gebruiker_beoordelingen', $data);
        if($this->db->affected_rows() == 1) return $this->db->insert_id();
        else return 0;
    }

    function updateGebruikerBeoordeling($item_ID, $data) {
        $this->db->where('gebruiker_beoordeling_ID', $item_ID);
        $this->db->update('gebruiker_beoordelingen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function transferBeoordeling($item_ID, $data) {
        $this->db->where('les_ID', $item_ID);
        $this->db->update('gebruiker_beoordelingen', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }

    function getAanstaandeLessen()
    {
        $this->db->select('*, groepen.groep_ID as groupID');
        $this->db->from('groepen_lessen');
        $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID');
        $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID');
        $this->db->join('docenten', 'groepen_lessen.docent_ID = docenten.docent_ID');
        $this->db->join('gebruikers', 'docenten.gebruiker_ID = gebruikers.gebruiker_ID');
        $this->db->where('DATE(groep_les_datum)', date('Y-m-d'));
        $this->db->where('HOUR(groep_les_datum)',date('H')+3);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->result();
    }
    function getAanstaandeLessen_new()
    {
        $this->db->select('*, groepen.groep_ID as groupID');
        $this->db->from('groepen_lessen');
        $this->db->join('groepen', 'groepen_lessen.groep_ID = groepen.groep_ID','left');
        $this->db->join('lessen', 'groepen_lessen.les_ID = lessen.les_ID','left');
        $this->db->join('docenten', 'groepen_lessen.docent_ID = docenten.docent_ID','left');
        $this->db->join('gebruikers', 'docenten.gebruiker_ID = gebruikers.gebruiker_ID','left');
        $this->db->where('DATE(groep_les_datum)', date('Y-m-d'));
        $this->db->where('HOUR(groep_les_datum)',date('H')+3);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->result();
    }
    function getAanmeldingByID($workshop_ID,$groupID,$les_ID)
    {
        $this->db->select('gebruikers.gebruiker_naam,gebruikers.gebruiker_voornaam,gebruikers.gebruiker_geslacht,gebruikers.gebruiker_geboortedatum,gebruikers.gebruiker_adres,gebruikers.gebruiker_postcode,gebruikers.gebruiker_telefoonnummer,gebruikers.gebruiker_mobiel,gebruikers.gebruiker_emailadres,stem_aanwezigheid.aanwezigheid_aanwezig');
        $this->db->from('aanmeldingen');
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
        $this->db->join('aanwezigheid', 'aanwezigheid.gebruiker_ID = aanmeldingen.gebruiker_ID', 'left');
        $this->db->where('aanmeldingen.workshop_ID', $workshop_ID);
        $this->db->where('aanmeldingen.groep_ID', $groupID);
        $this->db->where('gebruiker_status !=', "concept");
		$this->db->where('aanmelding_verlopen', "0");
        // $this->db->where('aanwezigheid.les_ID', $les_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->result();
    }

    function getTeacherEmail($docent_ID)
    {
        $this->db->select('*,gebruikers.gebruiker_emailadres as Email');
        $this->db->from('docenten');
        $this->db->where('docent_ID', $docent_ID);
        $this->db->join('gebruikers', 'gebruikers.gebruiker_ID = docenten.gebruiker_ID');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
    }
    function deleteBeoordelingscriteriaResultaat_new($huiswerk_ID) {
        $this->db->where('huiswerk_ID', $huiswerk_ID);
        $this->db->delete('beoordelingscriteria_resultaat');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}
