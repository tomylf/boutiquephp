<?php
// fonction utilisateur que l'on va coder pour éviter d'ecrire à chaque fois qu' on en a besoin les echo <pre></pre> print_r et var_dump
function debug($var, $mode = 1){

    // debug_backtrace est une fonction prédéfinie qui collecte différentes informations concernant le fichier dans lequel on travail (son nom, son chemin physique, les lignes etc...)
    $trace = debug_backtrace();

    // array_shift permet de contouner une dimension supplémentaire d'un tableau. Au lieu d'ecrire $trace[0][file] on pourra directement ecrire $trace[file] sans préciser l'indice intermédiaire
    $trace = array_shift($trace);
    // ci dessous, le message que je veux m'afficher lors de chaque print_r et var_dump
    echo "Debug demandé sur le fichier <strong>$trace[file]</strong> à la ligne <strong>$trace[line]</strong> ";


    if($mode == 1){
        echo "<pre>"; print_r($var); echo "</pre>";
    }else{
        echo "<pre>"; var_dump($var); echo "</pre>";
    }
}

function internauteConnecte(){
    if(!isset($_SESSION['membre'])){
        return FALSE;
    }else{
        return TRUE;
    }
}

function internauteConnecteAdmin(){
    if(internauteConnecte() && $_SESSION['membre']['statut']==1){
        return TRUE;
    }else{
        return FALSE;
}
}

