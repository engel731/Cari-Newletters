<?php
  namespace Entity;
  
  class Mail
  {
    private $_id;
    private $_mail;
    private $_lieu;
    private $_erreurs = [];

    const MAIL_INVALIDE = 1;

    public function __construct(array $donnees) 
    {
      $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
      foreach ($donnees as $key => $value)
      {
        $method = 'set'.ucfirst($key);
            
        if (method_exists($this, $method))
        {
          $this->$method($value);
        }
      }
    }

    public function isValid()
    {
      return !(empty($this->_mail) || empty($this->_lieu));
    }

    public function id() { return $this->_id; }
    public function mail() { return $this->_mail; }
    public function lieu() { return $this->_lieu; }
    public function erreurs() { return $this->_erreurs; }

    public function setId($id)
    {
      $this->_id = (int) $id;
    }
          
    public function setMail($mail)
    {
      if (is_string($mail) && strlen($mail) <= 60)
      {
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
          $this->_mail = $mail;
        } else $this->_erreurs[] = self::MAIL_INVALIDE;
      } else $this->_erreurs[] = self::MAIL_INVALIDE;
    }

    public function setLieu($id_lieu)
    {
        $this->_lieu = (int) $id_lieu;
    }
  }