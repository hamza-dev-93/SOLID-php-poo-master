<?php
namespace Controlers;

//  require_once('libraries/utils.php');
//  require_once('libraries/autoload.php');

class Article extends Controller
{

    protected $modelName = \Models\Article::class;

    /**
     * CE FICHIER A POUR BUT D'AFFICHER LA PAGE D'ACCUEIL !
     *
     * On va donc se connecter à la base de données, récupérer les articles du plus récent au plus ancien (SELECT * FROM articles ORDER BY created_at DESC)
     * puis on va boucler dessus pour afficher chacun d'entre eux
     */
     
    public function index()
    {

        $articles = $this->model->findAll("created_at");
        $pageTitle = "Accueil";
        \Renderer::render("articles/index", compact('pageTitle', 'articles'));

    }
    /**
     * CE FICHIER DOIT AFFICHER UN ARTICLE ET SES COMMENTAIRES !
     *
     * On doit d'abord récupérer le paramètre "id" qui sera présent en GET et vérifier son existence
     * Si on n'a pas de param "id", alors on affiche un message d'erreur !
     *
     * Sinon, on va se connecter à la base de données, récupérer les commentaires du plus ancien au plus récent (SELECT * FROM comments WHERE article_id = ?)
     *
     * On va ensuite afficher l'article puis ses commentaires
     */
    
    /**
     * show
     *
     * @return void
     */
    public function show()
    {
        $article_id = null;

// Mais si il y'en a un et que c'est un nombre entier, alors c'est cool
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $article_id = $_GET['id'];
        }

// On peut désormais décider : erreur ou pas ?!
        if (!$article_id) {
            die("Vous devez préciser un paramètre `id` dans l'URL !");
        }

// On fouille le résultat pour en extraire les données réelles de l'article

        $article = $this->model->find($article_id);

        $objC = new \Models\Comment();
        $commentaires = $objC->findComments($article_id);

/**
 * 5. On affiche
 */
        $pageTitle = $article['title'];
        \Renderer::render("articles/show", compact('pageTitle', 'article', 'commentaires', 'article_id'));

    }

/**
 * DANS CE FICHIER, ON CHERCHE A SUPPRIMER L'ARTICLE DONT L'ID EST PASSE EN GET
 *
 * Il va donc falloir bien s'assurer qu'un paramètre "id" est bien passé en GET, puis que cet article existe bel et bien
 * Ensuite, on va pouvoir effectivement supprimer l'article et rediriger vers la page d'accueil
 */
    public function delete()
    {
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
            die("Ho ?! Tu n'as pas précisé l'id de l'article !");
        }

        $id = $_GET['id'];
        $article = $this->model->find($id);
        if (!$article) {
            die("L'article $id n'existe pas, vous ne pouvez donc pas le supprimer !");
        }
        $this->model->delete($id);
        \Http::redirect("index.php");

    }

}
