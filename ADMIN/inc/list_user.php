<?php
$page = "Liste des user";
require_once("inc/header_back.php");

 # Je sélectionne tous mes résultats en BDD pour la table produit
 $result = $pdo->query('SELECT * FROM membre');
 $users = $result->fetchAll();
 
 // debug($user $contenu .= "<div class='table-responsive'>";
 $contenu .= "<table class='table table-striped table-sm'>";
 $contenu .= "<thead class='thead-dark'><tr>";
 
 for($i= 0; $i < $result->columnCount(); $i++)
 {
     $colonne = $result->getColumnMeta($i);
     $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
 
 }
 
 $contenu .= "<th colspan='2'>Actions</th>";
 $contenu .= "</tr></thead><tbody>";
 
 //debug($produits);
 
     foreach($users as $user)
     {
 
         $contenu .= "<tr>";
         foreach ($user as $key => $value) 
         {
             
            $contenu .= "<td>" . $value . "</td>";  
             
         }
 
         $contenu .= "<td><a href='formulaire_produit.php?id=" . $user['id_produit'] . "'><i class='fas fa-pen'></i></a></td>";
 
         $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $user['id_produit'] . "'><i class='fas fa-trash-alt'></i></a></td>";
 
         # J'appelle ma modal de supression (fonction créée dans fonction.php)
         deleteModal($user['id_produit'], $user['titre'], $user['reference']);
 
         $contenu .= "</tr>";
     }
 
 $contenu .= "</tbody></table>";
 $contenu .= "</div>";
?>



<?php require_once("inc/footer_back.php"); ?>