<?php
  namespace Model;

  use Entity\Mail;
  
  class MailManager
  {
    private $_wpdb;

    public function __construct($wpdb)
    {
      $this->setWpdb($wpdb);
    }

    public function getMailToSend() {
      $wpdb = $this->_wpdb;

      $resultats = $wpdb->get_results(
        "SELECT m.mail mail, t.type_dechet dechet
        FROM {$wpdb->prefix}cinor_crom_mail_listing m
        INNER JOIN {$wpdb->prefix}cinor_crom_street_listing s
        ON m.lieu = s.id
        INNER JOIN {$wpdb->prefix}cinor_crom_touring t
        ON s.ref_calendrier = t.ref_calendrier
        WHERE t.date_passage = DATE_ADD(CURDATE(), INTERVAL 1 DAY)",

        ARRAY_A
      );

      return $resultats;
    }

    public function notDoubleLocation(Mail $mail) 
    {
      $wpdb = $this->_wpdb;
        
      $row = $wpdb->get_row(
        $wpdb->prepare(
          "SELECT id FROM {$wpdb->prefix}cinor_crom_mail_listing WHERE mail = %s AND lieu = %s",
          $mail->mail(),
          $mail->lieu()
        )
      );

      return ($row == null) ? true : false;
    }

    public function add(Mail $mail)
    {
      $wpdb = $this->_wpdb;
      
      $wpdb->insert(
        "{$wpdb->prefix}cinor_crom_mail_listing", 
        
        array(
          'mail' => $mail->mail(), 
          'lieu' => $mail->lieu()
        ),

        array('%s','%s')
      );

      return true;
    }

    public function create() {
      $wpdb = $this->_wpdb;
      $charset_collate = $wpdb->get_charset_collate();

      $wpdb->query(
          "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cinor_crom_mail_listing (
              id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
              lieu SMALLINT UNSIGNED NOT NULL,
              mail VARCHAR(60) NOT NULL,
              PRIMARY KEY (id)
          ){$charset_collate};"
      );

      return true;
    }

    public function delete() {
      $wpdb = $this->_wpdb;
      $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cinor_crom_mail_listing");
    }

    public function setWpdb($wpdb)
    {
      $this->_wpdb = $wpdb;
    }
  }