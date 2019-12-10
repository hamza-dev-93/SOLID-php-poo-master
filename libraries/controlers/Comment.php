<?php

namespace Controlers;

class Comment extends Controller
{

    protected $modelName = \Models\Comment::class;

    public function save()
    {

// On commence par l'author
        $author = null;
        if (!empty($_POST['author'])) {
            $author = $_POST['author'];
        }

// Ensuite le contenu
        $content = null;
        if (!empty($_POST['content'])) {
            // On fait quand même gaffe à ce que le gars n'essaye pas des balises cheloues dans son commentaire
            $content = htmlspecialchars($_POST['content']);
        }

// Enfin l'id de l'article
        $article_id = null;
        if (!empty($_POST['article_id']) && ctype_digit($_POST['article_id'])) {
            $article_id = $_POST['article_id'];
        }

// Vérification finale des infos envoyées dans le formulaire (donc dans le POST)
        // Si il n'y a pas d'auteur OU qu'il n'y a pas de contenu OU qu'il n'y a pas d'identifiant d'article
        if (!$author || !$article_id || !$content) {
            die("Votre formulaire a été mal rempli !");
        }

/**
 * 2. Vérification que l'id de l'article pointe bien vers un article qui existe
 * Ca nécessite une connexion à la base de données puis une requête qui va aller chercher l'article en question
 * Si rien ne revient, la personne se fout de nous.
 *
 * Attention, on précise ici deux options :
 * - Le mode d'erreur : le mode exception permet à PDO de nous prévenir violament quand on fait une connerie ;-)
 * - Le mode d'exploitation : FETCH_ASSOC veut dire qu'on exploitera les données sous la forme de tableaux associatifs
 *
 * PS : Ca fait pas genre 3 fois qu'on écrit ces lignes pour se connecter ?!
 */

        $objA = new \Models\Article();
        $article = $objA->find($article_id);

// Si rien n'est revenu, on fait une erreur
        if (!$article) {
            die("Ho ! L'article $article_id n'existe pas boloss !");
        }

// 3. Insertion du commentaire

        $this->model->save(compact('author', 'content', 'article_id'));
// 4. Redirection vers l'article en question :

        \Http::redirect("index.php?controller=article&task=show&id=" . $article_id);

    }

    public function delete()
    {

/**
 * 1. Récupération du paramètre "id" en GET
 */
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
            die("Ho ! Fallait préciser le paramètre id en GET !");
        }

        $id = $_GET['id'];

        $commentaire = $this->model->find($id);

        if (!$commentaire) {
            die("Aucun commentaire n'a l'identifiant $id !");
        }

/**
 * 4. Suppression réelle du commentaire
 * On récupère l'identifiant de l'article avant de supprimer le commentaire
 */

        $article_id = $commentaire['article_id'];

        $this->model->delete($id);
/**
 * 5. Redirection vers l'article en question
 */

        \Http::redirect("index.php?controller=article&task=show&id=" . $article_id);

    }
}
