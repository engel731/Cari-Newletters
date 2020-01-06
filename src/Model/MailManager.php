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

    public function add(Mail $mail)
    {
      $wpdb = $this->_wpdb;
      
      $wpdb->insert(
        "{$wpdb->prefix}cari_mail_listing", 
        
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
          "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cari_mail_listing (
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
      $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cari_mail_listing;");
    }

    public function setWpdb($wpdb)
    {
      $this->_wpdb = $wpdb;
    }
  }