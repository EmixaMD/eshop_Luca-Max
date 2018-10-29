<?php
$page = "Liste des utilisateurs";
require_once("inc/header_back.php");

# Je sélectionne tous mes résultats en BDD pour la table commande
    $result = $pdo->query('SELECT * FROM commande');
    $commandes = $result->fetchAll();
    
    // debug($commandes);
    
    $contenu .= "<div class='table-responsive'>";
    $contenu .= "<table class='table table-striped table-sm'>";
    $contenu .= "<thead class='thead-dark'><tr>";
    
    for($i= 0; $i < $result->columnCount(); $i++)
    {
        $colonne = $result->getColumnMeta($i);
        $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
    
    }
    
    $contenu .= "<th colspan='2'>Actions</th>";
    $contenu .= "</tr></thead><tbody>";
    
    //debug($commandes);
    
        foreach($commandes as $commande)
        {
    
            $contenu .= "<tr>";
            foreach ($commande as $key => $value) 
            {
                if($key == "photo")
                {
                    $contenu .= "<td><img height='100' src='" . URL . "assets/uploads/admin/" . $value . "' alt='" . $commande['titre'] . "'/></td>";
                }
                else 
                {
                    $contenu .= "<td>" . $value . "</td>";  
                }
                
            }
    
            $contenu .= "<td><a href='formulaire_produit.php?id=" . $commande['id_commande'] . "'><i class='fas fa-pen'></i></a></td>";
    
            $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $commande['id_commande'] . "'><i class='fas fa-trash-alt'></i></a></td>";
    
            # J'appelle ma modal de supression (fonction créée dans fonction.php)
            deleteModal($commande['id_commande'], $commande['id_membre'], $commande['date_enregistrement']);
    
            $contenu .= "</tr>";
        }
    
    $contenu .= "</tbody></table>";
    $contenu .= "</div>";
?>

    <?= $msg ?>
    <?= $contenu ?>
    
    <?php require_once("inc/footer_back.php"); 
    
?>