<?php
require_once('../include/init.php');
if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    exit();
}
require_once('includeAdmin/header.php');
?>
<!-- $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>'; -->
<!-- $content .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                        <strong>Félicitations !</strong> Insertion du produit réussie !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'; -->
<h1 class="text-center my-5"><div class="badge badge-warning text-wrap p-3">Gestion des utilisateurs</div></h1>
<!-- <div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-warning rounded" role="alert">
    <p>Gérez ici votre base de données des utilisateurs</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un utilisateur</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div> -->
<!-- le formulaire et son h2 n'apparaissent que si on en a fait la demande a partir du tableau des données. Sinon, c'est le tableau uniquement qui s'affiche, plus pratique pour l'admin -->

<?php if(isset($_GET['action'])): ?>
<h2 class="my-5">Formulaire <?= ($_GET['action'] == 'add') ? "d'ajout" : "de modification" ?>  des utilisateurs</h2>
<form class="my-5" method="POST" action="">
    <input type="hidden" name="id_membre" value="">
    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
        <input class="form-control" type="text" name="pseudo" id="pseudo"  placeholder="Pseudo">
        </div>

        <?php if($_GET['action'] == 'add'): ?>

        <div class="col-md-4 mt-5">
        <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
        <input class="form-control" type="password" name="mdp" id="mdp" placeholder="Mot de passe">
        </div>
        <div class="col-md-4 mt-5">
        <label class="form-label" for="email"><div class="badge badge-dark text-wrap">Email</div></label>
        <input class="form-control" type="email" name="email" id="email"  placeholder="Email">
        </div>

        <?php endif ?>

    </div>
    <div class="row">
        <div class="col-md-4 mt-5">
        <label class="form-label" for="nom"><div class="badge badge-dark text-wrap">Nom</div></label>
        <input class="form-control" type="text" name="nom" id="nom"  placeholder="Nom">
        </div>
        <div class="col-md-4 mt-5">
        <label class="form-label" for="prenom"><div class="badge badge-dark text-wrap">Prénom</div></label>
        <input class="form-control" type="text" name="prenom" id="prenom"  placeholder="Prénom">
        </div>
        <div class="col-md-4 mt-4">
            <p><div class="badge badge-dark text-wrap">Civilité</div></p>
            <input type="radio" name="civilite" id="civilite1" value="femme" >
            <label class="mx-2" for="civilite1">Femme</label>
            <input type="radio" name="civilite" id="civilite2" value="homme" >
            <label class="mx-2" for="civilite2">Homme</label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mt-5">
            <label class="form-label" for="ville"><div class="badge badge-dark text-wrap">Ville</div></label>
            <input class="form-control" type="text" name="ville" id="ville"  placeholder="Ville">
        </div>
        <div class="col-md-4 mt-5">
            <label class="form-label" for="code_postal"><div class="badge badge-dark text-wrap">Code Postal</div></label>
            <input class="form-control" type="text" name="code_postal" id="code_postal"  placeholder="Code postal">
        </div>
        <div class="col-md-4 mt-5">
            <label class="form-label" for="adresse"><div class="badge badge-dark text-wrap">Adresse</div></label>
            <input class="form-control" type="text" name="adresse" id="adresse"  placeholder="Adresse">
        </div>
    </div>
    <div class="col-md-1 mt-5">
    <button type="submit" class="btn btn-outline-dark btn-warning">Valider</button>
    </div>
</form>

<?php endif; ?>

<?php $queryUser = $pdo->query("SELECT id_membre FROM membre") ?>
<h2 class="py-5">Nombre de'utilisateurs en base de données: <?= $queryUser->rowCount() ?></h2>
<div class="row justify-content-center py-5">
    <a href='?action=add'>
        <button type="button" class="btn btn-sm btn-outline-dark shadow rounded"><i class="bi bi-plus-circle-fill"></i> Ajouter un utilisateur</button>
    </a>
</div>
<table class="table table-dark text-center">
    <?php $afficheUsers = $pdo->query("SELECT * FROM membre ORDER BY pseudo ASC");
    $user = $afficheUsers->fetch(PDO::FETCH_ASSOC) ?>
    <thead>
        <tr>
        <?php foreach($user as $key => $value):?>
            <?php if($key != 'mdp'): ?>
            <th style='padding:5px'><?=  $key  ?></th>
            <?php endif; ?>
        <?php endforeach; ?>
        <th colspan='2'>Actions </th>
        </tr>
    </thead>
    <tbody>
        <?php while($user = $afficheUsers->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
        <?php foreach($user as $key => $value): ?>
            <?php if($key != 'mdp'): ?>
            <td style='padding:5px'> <?= $value ?> </td>
            <?php endif; ?>
        <?php endforeach; ?>
                <td><a href='?action=update&id_membre=<?= $user['id_membre'] ?>'><i class="bi bi-pen-fill text-warning"></i></a></td>
        <td><a data-href="?action=delete" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<nav>
  <ul class="pagination justify-content-end">
    <li class="page-item ">
        <a class="page-link text-dark" href="" aria-label="Previous">
            <span aria-hidden="true">précédente</span>
            <span class="sr-only">Previous</span>
        </a>
    </li>
        <li class="mx-1 page-item">
            <a class="btn btn-outline-dark " href=""></a>
        </li>
    <li class="page-item ">
        <a class="page-link text-dark" href="" aria-label="Next">
            <span aria-hidden="true">suivante</span>
            <span class="sr-only">Next</span>
        </a>
    </li>
  </ul>
</nav>
<!-- <td><a href=''><i class="bi bi-pen-fill text-warning"></i></a></td>-->
<!-- <td><a data-href="" data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></td> -->
<!-- modal suppression codepen https://codepen.io/lowpez/pen/rvXbJq -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer Utilisateur
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer cet utilisateur de votre base de données ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<?php if(isset($_GET['action']) && $_GET['page']
):?>
<!-- modal infos -->
<div class="modal fade" id="myModalUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning" id="exampleModalLabel">Gestion des utilisateurs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Gérez ici votre base de données des utilisateurs</p>
        <p>Vous pouvez modifier leurs données, ajouter ou supprimer un utilisateur</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-warning text-dark" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- modal -->
<?php endif;?>
      <?php require_once('includeAdmin/footer.php'); ?>