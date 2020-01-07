<?php
  namespace Entity;

  class Touring
  {
    private $_id;
    private $_type_dechet;
    private $_ref_calendrier;
    private $_date_passage;

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
      return !(empty($this->_type_dechet) || empty($this->_ref_calendrier) || empty($this->_date_passage));
    }

    public function id() { return $this->_id; }
    public function type_dechet() { return $this->_type_dechet; }
    public function ref_calendrier() { return $this->_ref_calendrier; }
    public function date_passage() { return $this->_date_passage; }

    public function setId($id)
    {
      // L'identifiant des rues sera, quoi qu'il arrive, un nombre entier.
      $this->_id = (int) $id;
    }
          
    public function setType_dechet($type_dechet)
    {
      // On vérifie qu'il s'agit bien d'une chaîne de caractères.
      // Dont la longueur est inférieure à 20 caractères.
      if (is_string($type_dechet) && strlen($type_dechet) <= 20)
      {
        $this->_type_dechet = $type_dechet;
      }
    }

    public function setRef_calendrier($ref_calendrier)
    {
      // On vérifie qu'il s'agit bien d'une chaîne de caractères.
      // Dont la longueur est inférieure à 20 caractères.
      if (is_string($ref_calendrier) && strlen($ref_calendrier) <= 20)
      {
        $this->_ref_calendrier = $ref_calendrier;
      }
    }

    public function setDate_passage(\DateTime $date_passage)
    {
      $this->_date_passage = $date_passage;
    }
  }