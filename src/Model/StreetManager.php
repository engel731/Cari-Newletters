<?php
  namespace Model;

  use Entity\Street;
  
  class StreetManager
  {
    private $_wpdb;

    public function __construct($wpdb)
    {
      $this->setWpdb($wpdb);
    }

    public function search($keypress) {
      $wpdb = $this->_wpdb;
      
      $resultats = $wpdb->get_results(
        $wpdb->prepare(
          "SELECT id, quartier, intitule_voie 
          FROM {$wpdb->prefix}cari_street_listing 
          WHERE MATCH(intitule_voie) 
          AGAINST (%s IN BOOLEAN MODE)
          LIMIT 3",

          $keypress
        ), 
        
        ARRAY_A
      );
      
      return $resultats;
    }

    public function add(Street $street)
    {
      $wpdb = $this->_wpdb;
      
      if($this->notExistItem($street)) {
        $intitule_voie = $street->type_voie() .' '. $street->intitule_voie();
        $intitule_voie = preg_replace("#  #", " ", $intitule_voie);
        $intitule_voie = preg_replace("#' #", "'", $intitule_voie);

        $wpdb->insert(
          "{$wpdb->prefix}cari_street_listing",

          array(
              'ref_calendrier' => $street->ref_calendrier(),
              'intitule_voie' => $intitule_voie,
              'quartier' => $street->quartier()
          ),

          array('%s','%s','%s')
        );

        return true;
      }

      return false;
    }

    public function notExistItem(Street $street) 
    {
      $wpdb = $this->_wpdb;
      
      $row = $wpdb->get_row(
        $wpdb->prepare(
          "SELECT id FROM {$wpdb->prefix}cari_street_listing WHERE ref_calendrier = %s AND intitule_voie = %s AND quartier = %s",
          $street->ref_calendrier(),
          $street->type_voie() .' '. $street->intitule_voie(),
          $street->quartier()
        )
      );

      return ($row == null) ? true : false;
    }

    public function idDoesExist($id)
    {
      $wpdb = $this->_wpdb;

      $row = $wpdb->get_row(
        $wpdb->prepare(
          "SELECT id FROM {$wpdb->prefix}cari_street_listing WHERE id = %d",
          $id
        )
      );
      
      return ($row != null) ? true : false;
    }

    public function create() {
      $wpdb = $this->_wpdb;
      $charset_collate = $wpdb->get_charset_collate();

      $wpdb->query(
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cari_street_listing (
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
            ref_calendrier VARCHAR(20) NOT NULL,
            intitule_voie VARCHAR(90) NOT NULL,
            quartier VARCHAR(40) NOT NULL,
            PRIMARY KEY (id),
            FULLTEXT ind_intitule_voie (intitule_voie(40)) 
        ){$charset_collate};"
      );
    }

    public function cleanWholeTable() {
      $wpdb = $this->_wpdb;

      $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}cari_street_listing"); 
    }

    public function delete() {
      $wpdb = $this->_wpdb;
      $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cari_street_listing");
    }

    public function setWpdb($wpdb)
    {
      $this->_wpdb = $wpdb;
    }
  }