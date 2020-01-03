<?php
  namespace Model;
  
  class MailManager
  {
    private $_db;

    public function __construct($db)
    {
      $this->setDb($db);
    }

    public function add(Mail $mail)
    {
      $req = $this->_db->prepare('INSERT INTO mail_listing(mail, lieu) VALUES(:mail, :lieu)');
      
      $req->execute(array(
        'mail' => $mail->mail(),
        'lieu' => $mail->lieu()
      ));

      return true;
    }

    public function setDb(PDO $db)
    {
      $this->_db = $db;
    }
  }