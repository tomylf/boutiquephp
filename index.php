<?php
// je récupère en haut de mon passage PHP tout ce qui a été initialisé dans init.php avec en plus tout ce qui a été codé dans fonctions.php
require_once('include/init.php');

// code

require_once('include/affichage.php');

// je récupère en bas du passage PHP tout le header, dont le CDN Bootstrap
require_once('include/header.php');
?>


</div>
    <div class="container-fluid">
    
        <div class="row my-5">

            <div class="col-md-2">

                <div class="list-group text-center">
                    <?php while($menuCategories = $afficheMenuCategories->fetch(PDO::FETCH_ASSOC)): ?>
                    <a class="btn btn-outline-success my-2" href="<?= URL ?>?categorie=<?= $menuCategories['categorie'] ?>"><?= $menuCategories['categorie'] ?></a>
                    <?php endwhile; ?>
                </div>
            
            </div>
            
            <?php if(isset($_GET['categorie'])): ?>
           <!-- --------------------------- -->
            <div class="col-md-8">
            
                <div class="text-center my-5">
                    <img class='img-fluid' src="img/la_boutique_bis.webp" alt="Bandeau de La Boutique" loading="lazy">
                </div>

                <div class="row justify-content-around">
                    <h2 class="py-5"><div class="badge badge-dark text-wrap">Nos <?= $afficheTitre['categorie'] ?></div></h2>
                </div>

                <div class="row justify-content-around text-center">

                        
                    <?php while($produit = $afficheProduits->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                            <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><img src="<?= URL . 'img/' . $produit['photo'] ?>" class="card-img-top" alt="..."></a>
                            <div class="card-body">
                                <h3 class="card-title"><?= $produit['titre'] ?></h3>
                                <h3 class="card-title"><div class="badge badge-dark text-wrap"><?= $produit['prix'] ?> €</div></h3>
                                <p class="card-text"><?= $produit['description'] ?></p>
                                <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-outline-success"><i class='bi bi-search'></i> Voir Produit</a>
                            </div>
                        </div>
                     <?php endwhile; ?>
                </div>

                <nav aria-label="">
                    <ul class="pagination justify-content-end">
                        <li class="mx-1 page-item  ">
                            <a class="page-link text-success" href="" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <!--  -->
                            <li class="mx-1 page-item ">
                                <a class="btn btn-outline-success " href=""></a>
                            </li>
                        <!--  -->
                        <li class="mx-1 page-item ">
                            <a class="page-link text-success" href="" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
               
            </div>

            <?php elseif(isset($_GET['public'])): ?>
            <!-- ----------------------- -->

            <div class="col-md-8">
            
                <div class="text-center my-5">
                    <img class='img-fluid' src="img/la_boutique_bis.webp" alt="Bandeau de La Boutique" loading="lazy">
                </div>

                <div class="row justify-content-around">
                    
                    <h2 class="py-5"><div class="badge badge-dark text-wrap"> <!-- ternaire selon modèles mixtes ou modèles pour enfants, femmes, hommes--> </div></h2>
                </div>

                <div class="row justify-content-around text-center">

                        
                    
                        <div class="card mx-3 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
                            <a href=""><img src="" class="card-img-top" alt="..."></a>
                            <div class="card-body">
                                <h3 class="card-title"></h3>
                                <h3 class="card-title"><div class="badge badge-dark text-wrap"> €</div></h3>
                                <p class="card-text"></p>
                                <a href="" class="btn btn-outline-success"><i class='bi bi-search'></i> Voir Produit</a>
                            </div>
                        </div>
                    
                </div>

                <nav aria-label="">
                <!-- dans les 3 <a href> je dois faire référence à la catégorie, en plus de la page, sinon cela ne fonctionnera pas -->
                    <ul class="pagination justify-content-end">
                        <li class="mx-1 page-item  ">
                            <a class="page-link text-success" href="" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        
                            <li class="mx-1 page-item ">
                                <a class="btn btn-outline-success " href=""></a>
                            </li>
                        
                        <li class="mx-1 page-item ">
                            <a class="page-link text-success" href="" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            
            </div>

            <?php else: ?>
            <!-- ------------------------------ -->

            <div class="col-md-8">

                <div class="row justify-content-around py-5">
                    <img class='img-fluid' src="img/la_boutique.webp" alt="Bandeau de La Boutique" loading="lazy">    
                </div>

            </div>
            <?php endif; ?>
             

        </div>

    </div>
<div class="container">

<!-- je récupère mon footer en bas de page, notamment pour les scripts en JS -->
<?php require_once('include/footer.php');