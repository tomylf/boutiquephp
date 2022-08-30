<?php
// je récupère en haut de mon passage PHP tout ce qui a été initialisé dans init.php avec en plus tout ce qui a été codé dans fonctions.php
require_once('include/init.php');
// on vérifie si internaute est connecté. Si oui, alors on le redirige vers sa page profil, il n' a rien a faire ici.
if(internauteConnecte()){
    header('location' . URL . 'profil.php' );
}
// si j'ai reçu une information via un formulaire
if($_POST){
    // je vérifie les entrées de chaque input unes a unes
    // pour le pseudo, je vérifie qu'il existe, puis qu'il correspond au preg_match
    if(!isset($_POST['pseudo']) || !preg_match( "#^[a-zA-Z0-9-_.]{3,20}$#" , $_POST['pseudo'] ) ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>';
    }
    // je vérifie que mdp existe, puis qu'il est compris entre 3 et 20 caractères
    if(!isset($_POST['mdp']) || strlen($_POST['mdp']) < 3 || strlen($_POST['mdp']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>';
    }
    // je vérifie que nom existe, puis qu'il est compris entre 3 et 20 caractères
    if(!isset($_POST['nom']) || strlen($_POST['nom']) < 3 || strlen($_POST['nom']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
    }
    // je vérifie que prénom existe, puis qu'il est compris entre 3 et 20 caractères
    if(!isset($_POST['prenom']) || strlen($_POST['prenom']) < 3 || strlen($_POST['prenom']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prénom !</div>';
    }
  // je vérifie que email est renseigné, puis j'utilise filter var avec l'argument FILTER_VALIDATE_EMAIL que cela correspond bien à une syntaxe d'adresse mail
    if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
    }
    // je vérifie que civilité existe et que cela correspond à femme ou homme (tout en utilsant && et pas || pour tout ce qui est boutons a cocher, selecteurs etc...)
    if(!isset($_POST['civilite']) || $_POST['civilite'] != 'femme' && $_POST['civilite'] != 'homme' ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format civilité !</div>';
    }
    // je vérifie que ville existe, puis qu'il est compris entre 3 et 20 caractères
    if(!isset($_POST['ville']) || strlen($_POST['ville']) < 3 || strlen($_POST['ville']) > 20 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
    }
    // je vérifie qu'il existe et qu'il correspond au preg_match (que des chiffres, au nombre de cinq)
    if(!isset($_POST['code_postal']) || !preg_match( "#^[0-9]{5}$#" , $_POST['code_postal'] ) ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code postal !</div>';
    }
    // je vérifie que adresse existe, puis qu'il est compris entre 3 et 50 caractères
    if(!isset($_POST['adresse']) || strlen($_POST['adresse']) < 3 || strlen($_POST['adresse']) > 50 ){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
    }
    // une fois tous les inputs vérifiés, je vérifie que le pseudo choisi n'existe pas déjà en BDD (il doit etre unique, sinon génère une erreur sql)
    // Je fais une requete (préparée obligatoirement dans ce cas) pour récupérer tous les (éventuels) pseudo équivalents au pseudo inséré dans le formulaire
    $verifPseudo = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo ");
    // bindValue de la requete préparée pour le pseudo
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    // exécution obligatoire de la requete préparée
    $verifPseudo->execute();
    // le résultat récupéré ($verifPseudo) est analysé par rowCount. Si le résultat retourné par rowCount est == à 0, cela veut dire que aucun pseudo en BDD ne ressemble à celui envoyé dans le formulaire, on l'accepte donc
    // si rowCount renvoie == 1, cela voudra dire qu'une ligne a été trouvée en BDD avec un pseudo équivalent, on doit donc le refuser
    if($verifPseudo->rowCount() == 1){
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo existe déjà, choisissez en un autre !</div>';
    }
    // dernière étape, on hashe le mot de passe avant de l'envoyer en BDD (législation RGPD). On le fait avec la fonction prédéfinie password_hash, avec en second parametre PASSWORD_DEFAULT
    $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    if(empty($erreur)){
        // requete préparée pour envoyer toutes les infos en BDD
        $insererUser = $pdo->prepare(" INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse) ");
        // debug($insererUser,2);
        // les bindValue de la requete
        $insererUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $insererUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
        $insererUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $insererUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $insererUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $insererUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $insererUser->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $insererUser->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        $insererUser->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        // execution de la requete
        $insererUser->execute();
        // si le user réussi a s'inscrire, on fait une redirection vers la page de connexion. Le ?action=validate permettra d'y afficher un message de réussite (voir page connexion avec $validate)
        header('location' . URL . 'connexion.php?action=validate');
    }
}
// je récupère en bas du passage PHP tout le header, dont le CDN Bootstrap
require_once('include/header.php');
?>
<h2 class="text-center py-5"><div class="badge badge-dark text-wrap p-3">Inscription</div></h2>
<!-- affichage du message d'erreur pour que le user puisse corriger -->
<?= $erreur ?>
<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pseudo !</div>'; -->
<form class="my-5" method="POST" action="">
    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
        <input class="form-control btn btn-outline-success" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" max-length="20" pattern="[a-zA-Z0-9-_.]{3,20}" title="caractères acceptés: majuscules et minuscules, chiffres, signes tels que: - _ . , entre trois et vingt caractères." required>
        </div>
        <div class="col-md-4 mt-5">
        <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
        <input class="form-control btn btn-outline-success" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe" required>
        </div>
        <div class="col-md-4 mt-5">
        <label class="form-label" for="email"><div class="badge badge-dark text-wrap">Email</div></label>
        <input class="form-control btn btn-outline-success" type="email" name="email" id="email" placeholder="Votre email" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="nom"><div class="badge badge-dark text-wrap">Nom</div></label>
        <input class="form-control btn btn-outline-success" type="text" name="nom" id="nom" placeholder="Votre nom">
        </div>
        <div class="col-md-4 mt-5">
        <label class="form-label" for="prenom"><div class="badge badge-dark text-wrap">Prénom</div></label>
        <input class="form-control btn btn-outline-success" type="text" name="prenom" id="prenom" placeholder="Votre prénom">
        </div>
        <div class="col-md-4 mt-5 pt-2">
        <p><div class="badge badge-dark text-wrap">Civilité</div></p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite1" value="femme">
                <label class="form-check-label mx-2" for="civilite1">Femme</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite2" value="homme" checked>
                <label class="form-check-label mx-2" for="civilite2">Homme</label>
            </div>
        </div>
    </div>
   <div class="row">
        <div class="col-md-4 mt-5">
            <label class="form-label" for="ville"><div class="badge badge-dark text-wrap">Ville</div></label>
            <input class="form-control btn btn-outline-success" type="text" name="ville" id="ville" placeholder="Votre ville">
        </div>
        <div class="col-md-4 mt-5">
            <label class="form-label" for="code_postal"><div class="badge badge-dark text-wrap">Code Postal</div></label>
            <input class="form-control btn btn-outline-success" type="text" name="code_postal" id="code_postal" placeholder="Votre code postal">
        </div>
        <div class="col-md-4 mt-5">
            <label class="form-label" for="adresse"><div class="badge badge-dark text-wrap">Adresse</div></label>
            <input class="form-control btn btn-outline-success" type="text" name="adresse" id="adresse" placeholder="Votre adresse">
        </div>
    </div>
    <div class="col-md-1 mt-5">
    <button type="submit" class="btn btn-lg btn-outline-success">Valider</button>
    </div>
</form>
<!-- je récupère mon footer en bas de page, notamment pour les scripts en JS -->
<?php require_once('include/footer.php');