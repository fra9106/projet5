<?php
namespace OpenClass;
require 'vendor/autoload.php';

use OpenClass\{Manager, Pagination};
use Control\{ControllerUser, ControllerAdmin};



class ArticlesManager extends Manager
{
	public function postArticle($idRubrique, $idUser, $title, $content) // insertion article à la db
	{
		$db = $this->dbConnect();
		$inserarticle = $db->prepare('INSERT INTO articles(id_rubrique, id_user, title, content, creation_date) VALUES (?, ?, ?, ?, NOW())');
        $article = $inserarticle->execute(array($idRubrique, $idUser, $title, $content));
		
		return $article;

	}

	public function getArticlesAdmin($depart, $articlesparp) // méthode de récupération articles
	{
		 
		$db = $this->dbConnect();
		$articles = $db->prepare('SELECT rubriques.id, rubriques.libele, articles.id, membres.pseudo, articles.title, articles.content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles INNER JOIN membres ON articles.id_user = membres.id INNER JOIN rubriques ON articles.id_rubrique = rubriques.id ORDER BY creation_date DESC LIMIT ?, ?');
		$articles->execute(array($depart, $articlesparp));
		return $articles;
	}

	public function postArticlesUser($idRubrique, $idUser, $title, $content) // insertion article user à la db
	{
		$db = $this->dbConnect();
		$inserarticle = $db->prepare('INSERT INTO articles(id_rubrique, id_user, title, content, creation_date) VALUES (?, ?, ?, ?, NOW())');
        $article = $inserarticle->execute(array($idRubrique, $idUser, $title, $content));
		
		return $article;

	}

	

	public function getArticleAdmin($dataId) // méthode de récupération article à modifier (admin)
	{
		
		$db = $this->dbConnect();
    	$req = $db->prepare('SELECT id, title, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles WHERE id = ?');
		$req->execute(array($dataId));
	
    	return $req;
	}

	public function deletArticle($dataId) //supprime un chapitre et ses commentaires (admin)
	{ 
        $db = $this->dbConnect();
        $comment = $db->prepare('DELETE FROM avis WHERE id_article = ?');
        $comment->execute([$dataId]);
        $req = $db->prepare('DELETE FROM articles WHERE id = ?');
        $req->execute(array($dataId));
       	return $req;
    }

    public function updateArticle($title, $content, $postId) //modifie article (admin)
    {
    	$db = $this->dbConnect();
		$updArticle = $db->prepare('UPDATE articles SET title = ?, content = ? WHERE id = ?');
        $artOk = $updArticle->execute(array($title, $content, $postId));
		return $artOk;
    }

    public function signalement($articId) //requete pour signaler un article (user)
	{
		$db = $this->dbConnect();
		$req = $db->prepare('UPDATE articles SET signalement = 1 WHERE id = ?');
		$req->execute(array($articId));

		return $req;
	}

	public function getArticlesSignal($signalement) //récupère les commentaires signalés pour les afficher dans la vue (admin)
	{
		$db = $this->dbConnect();
		$artic = $db->prepare('SELECT id, title, content, signalement, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles WHERE signalement = 1');
		$artic->execute(array($signalement));
		return $artic;
	}

	public function deSignal($articId) //désignale un article (admin)
	{
		$db = $this->dbConnect();
		$req = $db->prepare('UPDATE articles SET signalement = 0 WHERE id = ?');
		$req->execute(array($articId));

		return $req;
	}

	public function getArticles($idArticle, $idCategorie) // méthode de récupération chapitre par id
	{
		
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT id, id_rubrique, title, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles WHERE id = ? WHERE id_rubrique = ?');
		$req->execute(array($idArticle, $idCategorie));
		$post = $req->fetch();

		return $post;
	
	}

	public function getArticlesUser($idRubrique, $depart, $articlesparp) // méthode de récupération articles user
	{
		$db = $this->dbConnect();
		$articles = $db->prepare('SELECT rubriques.id, rubriques.libele, articles.id, membres.pseudo, articles.title, articles.content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM articles INNER JOIN membres ON articles.id_user = membres.id INNER JOIN rubriques ON articles.id_rubrique = rubriques.id WHERE id_rubrique = ? ORDER BY creation_date DESC LIMIT ?, ?');
		$articles->execute(array($idRubrique, $depart, $articlesparp));
		
		return $articles;
		
	}

	





	
}