<?php
// je récupère en haut de mon passage PHP tout ce qui a été initialisé dans init.php avec en plus tout ce qui a été codé dans fonctions.php
require_once('include/init.php');

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
    unset($_SESSION['membre']);
    header('location:' . URL . 'connexion.php');
}

// on vérifie si internaute est connecté. Si oui, alors on le redirige vers sa page profil, il n' a rien a faire ici.
if(internauteConnecte()){
    header('location:' . URL . 'profil.php' );
}

// message de félicitations pour la personne qui arrive de la page inscription, pour lui assurer qu'il a réussi
if(isset($_GET['action']) && $_GET['action']== 'validate'){
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

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
                    }elseif(isset($_GET['action']) && $_GET['action'] == 'acheter' ){
                        // s'il arrive du panier (car il doit se connecter pour pouvoir payer son achat, on le redirige ensuite vers le panier)
                        header('location:' . URL . 'panier.php');
                    }else{
                        // si c'est un utilisateur lambda, on l'envoie vers son profil
                        header('location:' . URL . 'profil.php?action=validate');
                    }
                }
            }
        }else{
            // si les deux mdp ne sont pas les mêmes, on l'en averti
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur mot de passe non conforme !</div>';
        }
    }else{
        // si le pseudo n'existe pas en bdd (si rowCount == 0), on lui demande de réecrire son pseudo
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo est inconnu !</div>';
    }
}



// je récupère en bas du passage PHP tout le header, dont le CDN Bootstrap
require_once('include/header.php');
?>

<h2 class="text-center py-5"><div class="badge badge-dark text-wrap p-3">Connexion</div></h2>

<?= $validate ?>

<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>'; -->

<!-- $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'; -->

<form class="my-5" method="POST" action="">

    <div class="col-md-4 offset-md-4 my-4">

    <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
    <input class="form-control btn btn-outline-success mb-4" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">

    <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
    <input class="form-control btn btn-outline-success mb-4" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">

    <button type="submit" class="btn btn-lg btn-outline-success offset-md-4 my-2">Connexion</button>

    </div>
   
</form>

<!-- je récupère mon footer en bas de page, notamment pour les scripts en JS -->
<?php require_once('include/footer.php');