<?php
namespace Models;

// require_once('libraries/database.php');
// require_once('libraries/autoload.php');

abstract class Model
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = \Database::getPdo();
    }
    public function findAll(string $order = '')
    {

        if ($order) {
            $sql = 'SELECT * FROM ' . $this->table . ' ORDER BY ' . $order . ' DESC';
        } else {
            $sql = 'SELECT * FROM ' . $this->table;
        }

        // On utilisera ici la méthode query (pas besoin de préparation car aucune variable n'entre en jeu)
        $resultats = $this->pdo->query($sql);
        // On fouille le résultat pour en extraire les données réelles
        $items = $resultats->fetchAll();
        return $items;
    }

    /**
     * 3. Récupération de l'article en question
     * On va ici utiliser une requête préparée car elle inclue une variable qui provient de l'utilisateur : Ne faites
     * jamais confiance à ce connard d'utilisateur ! :D
     */
    public function find(int $id)
    {

        $query = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");

        // On exécute la requête en précisant le paramètre :article_id
        $query->execute(['id' => $id]);

        // On fouille le résultat pour en extraire les données réelles de l'article
        $item = $query->fetch();
        return $item;
    }
    public function findComments(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE article_id = :article_id");

        // On exécute la requête en précisant le paramètre :article_id
        $query->execute(['article_id' => $id]);

        // On fouille le résultat pour en extraire les données réelles de l'article
        $item = $query->fetchAll();
        return $item;
    }

    /**
     * 4. Réelle suppression de l'article
     */
    public function delete(int $id): void
    {

        $query = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $query->execute(['id' => $id]);
    }
// 3. Insertion du commentaire
    public function save(array $vars)
    {
        extract($vars);

        $query = $this->pdo->prepare('INSERT INTO ' . $this->table . ' SET author = :author, content = :content, article_id = :article_id, created_at = NOW()');
        $query->execute(compact('author', 'content', 'article_id'));

    }

}
