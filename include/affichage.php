<?php
// affichage des catégories dans la navigation latérale
$afficheMenuCategories = $pdo->query(" SELECT DISTINCT categorie FROM produit ORDER BY categorie ASC ");
// fin de navigation laterale catégories

// tout l'affichage par categorie
if(isset($_GET['categorie'])){
    // pagination pour les categories
    
    // fin pagination pour les categories

    // affichage de tous les produits concernés par une categorie
    $afficheProduits = $pdo->query(" SELECT * FROM produit WHERE categorie = '$_GET[categorie]' ORDER BY prix ASC ");
    // fin affichage des produits par categorie

    // affichage de la categorie dans le <h2>
    $afficheTitreCategorie = $pdo->query("SELECT categorie FROM produit WHERE categorie = '$_GET[categorie]' ");
    $afficheTitre = $afficheTitreCategorie->fetch(PDO::FETCH_ASSOC);
    // fin du h2 categorie

    // pour les onglets categories
    // syntaxe courte, la meilleure, appropriée
    $pageTitle = "Nos modèles de " . $_GET['categorie'];
    // syntaxe longue, inefficace sur la durée car toutes les catégories ne sont pas connues à l'avance
    // if($_GET['categorie'] == 'Jupes'){
    //     $pageTitle = "Nos modèles de Jupes";
    // }elseif($_GET['categorie'] == 'Manteaux'){
    //     $pageTitle = 'Nos modèles de manteaux'
    // }
    // fin onglets categories
}
// fin affichage par categorie

// -----------------------------------------------------------------------------------

// tout l'affichage par public



    // pagination produits par public
    
    // fin pagination produits par public

    // affichage des produits par public
    
    // fin affichage des produits par public

    // affichage du public dans le <h2>
    
    // fin du </h2> pour le public

    // pour les onglets publics
    
    // fin onglets publics

// fin affichage par public

// ---------------------------------------------------------------------------------------
// Tout ce qui concerne la fiche produit

// affichage d'un produit
if(isset($_GET['id_produit'])){
    $detailProduit = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
    $detail = $detailProduit->fetch(PDO::FETCH_ASSOC);
}


// fin affichage d'un seul produit


//  fin fiche produit

// --------------------------------------------------------------------------------------------