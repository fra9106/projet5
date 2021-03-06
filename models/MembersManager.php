<?php
namespace OpenClass;
require 'vendor/autoload.php';

use OpenClass\Manager;

class MembersManager extends Manager
{

    public function insertMembre($pseudo, $mail, $mdp, $avatar) //insertion infos nouveau membre en db
    
    {
        $db = $this->dbConnect();
        $insertmbr = $db->prepare("INSERT INTO membres(pseudo, mail, motdepasse, droits, avatar) VALUES(?, ?, ?, 0, ?)");
        $insertmbr->execute(array(
            $pseudo,
            $mail,
            $mdp,
            'default.jpg'
        ));
        return $insertmbr;

    }

    public function testMail($mail) //test pour contrer doublon mail
    
    {
        $db = $this->dbConnect();
        $reqmail = $db->prepare("SELECT * FROM membres WHERE mail = ?");
        $reqmail->execute(array(
            $mail
        ));
        $mailexist = $reqmail->rowCount();
        return $mailexist;
    }

    public function getConnect($pseudo) //récupère les information relative à la connexion de l'utilisateur inscrit en db
    
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, motdepasse, droits FROM membres WHERE pseudo = :pseudo');
        $req->execute(array(
            'pseudo' => $pseudo
        ));
        $connect = $req->fetch();
        return $connect;
    }

    public function remember($pseudo, $mdp) // fonction se souvenir de moi
    
    {
        $db = $this->dbConnect();
        $requser = $db->prepare("SELECT * FROM membres WHERE pseudo = ? AND motdepasse = ?");
        $req->execute(array(
            $_COOKIE['pseudo'],
            $_COOKIE['motdepasse']
        ));
        $usercook = $req->rowCount();
        return $usercook;

    }

    public function infosProfil() // infos user
    
    {
        $db = $this->dbConnect();
        $requser = $db->prepare("SELECT * FROM membres WHERE id = ?");
        $requser->execute(array(
            $_SESSION['id']
        ));
        $allinfos = $requser->fetch();
        return $allinfos;

    }

    public function infoPseudo($newpseudo) //modifie le pseudo
    
    {
        $db = $this->dbConnect();
        $insertpseudo = $db->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
        $insertpseudo->execute(array(
            $newpseudo,
            $_SESSION['id']
        ));
        return $insertpseudo;
    }

    public function infoMail($newmail) //modifie le mail
    
    {
        $db = $this->dbConnect();
        $insertmail = $db->prepare("UPDATE membres SET mail = ? WHERE id = ?");
        $insertmail->execute(array(
            $newmail,
            $_SESSION['id']
        ));
        return $insertmail;
    }

    public function infoMdp($newmdp) //modifie le mot de passe
    
    {
        $db = $this->dbConnect();
        $insertmdp = $db->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
        $insertmdp->execute(array(
            $newmdp,
            $_SESSION['id']
        ));
        return $insertmdp;
    }

    public function infosAvatar($newavatar) // modifie le fichier avatar
    
    {
        $db = $this->dbConnect();
        $upavatar = $db->prepare("UPDATE membres SET avatar = ? WHERE id = ?");
        $upavatar->execute(array(
            $newavatar,
            $_SESSION['id']
        ));
        return $upavatar;
    }

}

