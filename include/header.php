<?php
// je récupère en haut de mon passage PHP tout ce qui a été initialisé dans init.php avec en plus tout ce qui a été codé dans fonctions.php
require_once('include/init.php');

// si un user se connecte et rempli le formulaire
if($_POST){
    // requete préparée pour comparer les pseudos existants en BDD avec celui renseigné dans le formulaire par le user
    $verifUser = $pdo->prepare(" SELECT * FROM membre WHERE  pseudo = :pseudo ");
    $verifUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifUser->execute();

    // rowCount est utilisé pour dire si un pseudo similaire existe déjà en BDD
    if($verifUser->rowCount() == 1){
        // dans ce cas, on passe à l'étape suivante en vérifiant son mdp
        $user = $verifUser->fetch(PDO::FETCH_ASSOC);
        // après avoir récupéré les infos en BDD avec fetch, on compare son mdp en BDD avec celui qu'il a mis dans le formulaire
        if(password_verify($_POST['mdp'], $user['mdp'])){
            // si ils sont similaires, on ouvre une sessions membre (session qui permettra de le reconnaitre comme connecté, selon le code de notre fonction internauteConnecte dans fonctions.php)

            // ci dessous, syntaxe longue pour ouvrir une session membre
            // $_SESSION['membre']['id_membre'] = $user['id_membre'];
            // $_SESSION['membre']['pseudo'] = $user['pseudo'];
            // $_SESSION['membre']['nom'] = $user['nom'];
            // $_SESSION['membre']['prenom'] = $user['prenom'];
            // $_SESSION['membre']['email'] = $user['email'];
            // $_SESSION['membre']['civilite'] = $user['civilite'];
            // $_SESSION['membre']['ville'] = $user['ville'];
            // $_SESSION['membre']['code_postal'] = $user['code_postal'];
            // $_SESSION['membre']['adresse'] = $user['adresse'];
            // $_SESSION['membre']['statut'] = $user['statut'];

            // syntaxe courte avec une boucle foreach
            foreach($user as $key => $value){
                // on récupère toutes les infos en bdd, sauf celle concernant le mot de passe
                if($key != 'mdp'){
                    $_SESSION['membre'][$key] = $value;
                    // maintenant qu'il a réussi sa connexion on code trois redirections possibles
                    if(internauteConnecteAdmin()){
                        // s'il est admin on le dirige vers le back office
                        header('location:' . URL . 'admin/index.php?action=validate' );
                    }else{
                        // si c'est un utilisateur lambda, on l'envoie vers son profil
                        header('location:' . URL . 'profil.php?action=validate');

                        $validate_index .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                  Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté(e) 😉 !
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>';
                    }
                }
            }
        }else{
            // si les deux mdp ne sont pas les mêmes, on l'en averti
            $erreur_index .= '<div class="alert alert-danger" role="alert">Erreur mot de passe non conforme !</div>';
        }
    }else{
        // si le pseudo n'existe pas en bdd (si rowCount == 0), on lui demande de réecrire son pseudo
        $erreur_index .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo est inconnu !</div>';
    }
}



// je récupère en bas du passage PHP tout le header, dont le CDN Bootstrap

?>
<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur pseudo inconnu !</div>'; -->

<!-- $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                  Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous etes connecté(e) 😉 !
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>'; -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="logo.png" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

         <!-- links pour les icon bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

    <title><?= isset($pageTitle) ? $pageTitle : "La Boutique" ?></title>
</head>
<body>

<header>

<!-- ------------------- -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= URL ?>"><img src="<?= URL ?>img/boutique_logo.webp"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item mt-2">
        <a class="nav-link" href="<?= URL ?>">La Boutique</a>
      </li>
      <!-- ----------- -->
      <li class="nav-item">
        <a class="nav-link" href=""><button type="button" class="btn btn-outline-success"></button></a>
      </li>
      <!-- ---------- -->
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- -------------------------- -->
    <?php if(internauteConnecte()) : ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-success">Espace <?= $_SESSION ['membre']['pseudo'] ?><strong></strong></button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>profil.php">Profil de <?= $_SESSION ['membre']['pseudo'] ?> </a>
          <a class="dropdown-item" href="<?= URL ?>panier.php">Panier de <?= $_SESSION ['membre']['pseudo'] ?> </a>
          <a class="dropdown-item" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
        </div>
      </li>
      <?php else: ?>
    
      <!-- ---------------------------- -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle mr-5" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button type="button" class="btn btn-outline-success">Espace Membre</button>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>inscription.php"><button class="btn btn-outline-success">Inscription</button></a>
          <a class="dropdown-item"><button class="btn btn-outline-success" data-toggle="modal" data-target="#connexionModal">
            Connexion
          </button></a>
          <a class="dropdown-item" href="<?= URL ?>panier.php"><button class="btn btn-outline-success px-4">Panier</button></a>
        </div>
      </li>
      <?php endif; ?>
    
     <!-- ------------------------------------ -->
    <?php if(internauteConnecteAdmin()): ?>
      <li class="nav-item mr-5">
          <a class="nav-link" href="admin/index.php"><button type="button" class="btn btn-outline-success">Admin</button></a>
      </li>
      <?php endif; ?>
    
      <!-- ------------------------------------ -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

</header>

<div class="container">

          <!-- Modal -->
          <div class="modal fade" id="connexionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><img src="<?= URL ?>img/boutique_logo.webp"> La Boutique</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                
                <form name="connexion" method="POST" action="">
                    <div class="row justify-content-around">
                      <div class="col-md-4 mt-4">
                      <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
                      <input class="form-control btn btn-outline-success" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">
                      </div>
                    </div>

                    <div class="row justify-content-around">
                      <div class="col-md-6 mt-4">
                      <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
                      <input class="form-control btn btn-outline-success" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">
                      </div>
                    </div>
                    
                    <div class="row justify-content-center">
                      <button type="submit" name="connexion" class="btn btn-lg btn-outline-success mt-3">Connexion</button>
                    </div>

                </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
              </div>
            </div>
          </div>
          <!-- ------------- -->

          <?= $validate_index ?>
          <?= $erreur_index ?>

<h1 class="text-center mt-5"><div class="badge badge-dark text-wrap p-3">La Boutique</div></h1>
<h2 class="text-center pb-5">Notre Catalogue. Nos Produits !</h2>