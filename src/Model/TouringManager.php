<?php
  namespace Model;

  use Entity\Touring;
  
  class TouringManager
  {
    private $_wpdb;

    public function __construct($wpdb)
    {
      $this->setWpdb($wpdb);
    }

    public function add(Touring $touring)
    {
      $wpdb = $this->_wpdb;
      
      if($this->notExistItem($touring)) {
        $wpdb->insert(
          "{$wpdb->prefix}cari_touring",

          array(
              'type_dechet' => $touring->type_dechet(),
              'ref_calendrier' => $touring->ref_calendrier(),
              'date_passage' => $touring->date_passage()->format('Y-m-d')
          ),

          array('%s','%s','%s')
        );

        return true;
      }

      return false;
    }

    public function notExistItem(Touring $touring) 
    {
      $wpdb = $this->_wpdb;
      
      $row = $wpdb->get_row(
        $wpdb->prepare(
          "SELECT id FROM {$wpdb->prefix}cari_touring WHERE type_dechet = %s AND ref_calendrier = %s AND  date_passage = %s",
          $touring->type_dechet(),
          $touring->ref_calendrier(),
          $touring->date_passage()->format('Y-m-d')
        )
      );

      return ($row == null) ? true : false;
    }

    public function create() {
      $wpdb = $this->_wpdb;
      $charset_collate = $wpdb->get_charset_collate();

      $wpdb->query(
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cari_touring (
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
            type_dechet VARCHAR(20) NOT NULL,
            ref_calendrier VARCHAR(20) NOT NULL,
            date_passage DATE NOT NULL,
            PRIMARY KEY (id)
        ){$charset_collate};"
      );
    }

    public function cleanWholeTable() {
      $wpdb = $this->_wpdb;

      $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}cari_touring"); 
    }

    public function delete() {
      $wpdb = $this->_wpdb;
      
      $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cari_touring");
    }

    public function setWpdb($wpdb)
    {
      $this->_wpdb = $wpdb;
    }
  }