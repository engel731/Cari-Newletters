<?php
  namespace Model;
  
  class StreetManager
  {
    private $_db;

    public function __construct($db)
    {
      $this->setDb($db);
    }

    public function search($keypress) {
      $req = $this->_db->prepare(
        'SELECT id, quartier, intitule_voie 
        FROM street_listing
        WHERE MATCH(intitule_voie) 
        AGAINST (:keypress IN BOOLEAN MODE)
        LIMIT 5'
      );

      $req->execute(array(':keypress' => $keypress));
      $row = [];

      while ($data = $req->fetch()) {
        array_push($row, [
          'id' => $data['id'],
          'intitule_voie' => $data['intitule_voie'],
          'quartier' => $data['quartier']
        ]);
      }

      return $row;
    }

    public function add(Street $street)
    {
      if($this->notExistItem($street)) {
        $intitule_voie = $street->type_voie() .' '. $street->intitule_voie();
        $intitule_voie = preg_replace("#  #", " ", $intitule_voie);
        $intitule_voie = preg_replace("#' #", "'", $intitule_voie);
        
        $req = $this->_db->prepare('INSERT INTO street_listing(ref_calendrier, intitule_voie, quartier) VALUES(:ref_calendrier, :intitule_voie, :quartier)');
        
        $req->execute(array(
          'ref_calendrier' => $street->ref_calendrier(),
          'intitule_voie' => $intitule_voie,
          'quartier' => $street->quartier()
        ));

        return true;
      }

      return false;
    }

    public function notExistItem(Street $street) 
    {
      $req = $this->_db->prepare('SELECT id FROM street_listing WHERE ref_calendrier = :ref_calendrier AND intitule_voie = :intitule_voie AND quartier = :quartier');
      
      $req->execute(array(
        'ref_calendrier' => $street->ref_calendrier(),
        'intitule_voie' => $street->type_voie() .' '. $street->intitule_voie(),
        'quartier' => $street->quartier()
      ));

      return (!$req->fetch()) ? true : false;
    }

    public function delete(Street $street)
    {
      // Exécute une requête de type DELETE.
    }

    public function idDoesExist($id)
    {
      $req = $this->_db->prepare('SELECT id FROM street_listing WHERE id = :id');
      $req->execute(array('id' => $id));

      return ($req->fetch()) ? true : false;
    }

    public function getList()
    {
      // Retourne la liste de toutes les rues.
    }

    public function update(Street $street)
    {
      // Prépare une requête de type UPDATE.
      // Assignation des valeurs à la requête.
      // Exécution de la requête.
    }

    public function setDb(PDO $db)
    {
      $this->_db = $db;
    }
  }