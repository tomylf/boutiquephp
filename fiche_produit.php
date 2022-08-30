<?php 

// je recupère en haut de mon passage PHP tout ce qui a été initialisé dans init.php avec en plus tout ce qui a été codé dans fonction.php
require_once('include/init.php');

require_once('include/affichage.php');
$pageTitle = "Fiche du produit" . substr($detail['categorie'],0,-1) . $detail['titre'];

// je recupère en bas du passage PHP tou le header, dont le CDN Bootstrap
require_once('include/header.php');

?>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- debut de la colonne qui va afficher les categories -->
        <div class="col-md-2">

            <div class="list-group text-center">
                
                <?php while ($menuCategories = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a class="btn btn-outline-success my-2" href="<?= URL ?>?categorie=<?= $menuCategories['categorie'] ?>"><?= $menuCategories['categorie'] ?></a>
                <?php endwhile; ?>
                
            </div>

        </div>
        <!-- fin de la colonne catégories -->
        <div class="col-md-8">

            <h2 class='text-center my-5'>
                <div class="badge badge-dark text-wrap p-3">Fiche du produit <?= substr($detail['categorie'],0,-1) . $detail['titre']; ?> </div>
            </h2>

            <div class="row justify-content-around text-center py-5">
                <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 22rem;">
                    <img src="<?= URL . 'img/' . $detail['photo'] ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h3 class="card-title"><div class="badge badge-dark text-wrap"><?= $detail['prix'] ?>€</div></h3>
                        <p class="card-text"><?= $detail['description'] ?></p>
                        <!-- ------------------- -->
                        <?php if($detail['stock'] > 0): ?>
                        <form method="POST" action="panier.php">
                            <input type="hidden" name="id_produit" value="<?= $detail['id_produit'] ?>">
                            
                            <label for="">J'en achète</label>
                            
                            <select class="form-control col-md-5 mx-auto" name="" id="">
                                <!-- ----------- -->
                                <?php for($quantite = 1; $quantite <= min($detail['stock'],5); $quantite++): ?>
                                <option class="bg-dark text-light" value="<?= $quantite ?>"><?= $quantite ?></option>
                                <?php endfor; ?>
                                <!-- ----------- -->
                            </select>
                            <button type="submit" class="btn btn-outline-success my-2" name="ajout_panier" value="ajout_panier"><i class="bi bi-plus-circle"></i> Panier <i class="bi bi-cart3"></i></button>
                        </form>
                        <?php else: ?>
                        <!-- ----------- -->
                        
                            <p class="card-text"><div class="badge badge-danger text-wrap p-3">Produit en rupture de stock</div></p>
                        
                        <!-- ------------ -->
                         <?php endif; ?>
                        <p><a href="">Voir tous les modèles</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php require_once('include/footer.php');

