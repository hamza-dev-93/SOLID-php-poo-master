<?php
namespace Models;

// require_once('libraries/autoload.php');
require_once 'libraries/database.php';
class Comment extends Model
{

    protected $table = "comments";

    public function findComments(int $id)
    {

        $query = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE article_id = :id");

        // On exécute la requête en précisant le paramètre :article_id
        //(['id' => $id])
        $query->execute(['id' => $id]);

        // On fouille le résultat pour en extraire les données réelles de l'article
        $item = $query->fetchAll();
        return $item;
    }

}
