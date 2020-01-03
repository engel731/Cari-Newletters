<?php
  namespace Model;
  
  class TouringManager
  {
    private $_db; // Instance de PDO.

    public function __construct($db)
    {
      $this->setDb($db);
    }

    public function add(Touring $touring)
    {
      if($this->notExistItem($touring)) {
        $req = $this->_db->prepare('INSERT INTO touring(type_dechet, ref_calendrier, date_passage) VALUES(:type_dechet, :ref_calendrier, :date_passage)');
       
        $req->execute(array(
          'type_dechet' => $touring->type_dechet(),
          'ref_calendrier' => $touring->ref_calendrier(),
          'date_passage' => $touring->date_passage()->format('Y-m-d')
        ));
      }
    }

    public function delete(Touring $touring)
    {
      // Exécute une requête de type DELETE.
    }

    public function notExistItem(Touring $touring) 
    {
      $req = $this->_db->prepare('SELECT id FROM touring WHERE type_dechet = :type_dechet AND ref_calendrier = :ref_calendrier AND  date_passage = :date_passage');
      
      $req->execute(array(
        'type_dechet' => $touring->type_dechet(),
        'ref_calendrier' => $touring->ref_calendrier(),
        'date_passage' => $touring->date_passage()->format('Y-m-d')
      ));

      return (!$req->fetch()) ? true : false;
    }

    public function get($id)
    {
      // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Touring.
    }

    public function getList()
    {
      // Retourne la liste de toutes les tournée.
    }

    public function update(Touring $touring)
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