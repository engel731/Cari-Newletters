<?php
  namespace Entity;
  
  class Street
  {
    private $_id;
    private $_ref_calendrier;
    private $_type_voie;
    private $_intitule_voie;
    private $_quartier;

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
      return !(empty($this->_ref_calendrier) || empty($this->_type_voie) || empty($this->_intitule_voie) || empty($this->_quartier));
    }

    public function id() { return $this->_id; }
    public function ref_calendrier() { return $this->_ref_calendrier; }
    public function type_voie() { return $this->_type_voie; }
    public function intitule_voie() { return $this->_intitule_voie; }
    public function quartier() { return $this->_quartier; }

    public function setId($id)
    {
      // L'identifiant des rues sera, quoi qu'il arrive, un nombre entier.
      $this->_id = (int) $id;
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

    public function setType_voie($type_voie)
    {
      // On vérifie qu'il s'agit bien d'une chaîne de caractères.
      // Dont la longueur est inférieure à 20 caractères.
      if (is_string($type_voie) && strlen($type_voie) <= 20)
      {
        $this->_type_voie = $type_voie;
      }
    }

    public function setIntitule_voie($intitule_voie)
    {
      // On vérifie qu'il s'agit bien d'une chaîne de caractères.
      // Dont la longueur est inférieure à 70 caractères.
      if (is_string($intitule_voie) && strlen($intitule_voie) <= 70)
      {
        $this->_intitule_voie = $intitule_voie;
      }
    }

    public function setQuartier($quartier)
    {
      // On vérifie qu'il s'agit bien d'une chaîne de caractères.
      // Dont la longueur est inférieure à 40 caractères.
      if (is_string($quartier) && strlen($quartier) <= 40)
      {
        $this->_quartier = $quartier;
      }
    }
  }