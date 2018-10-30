<?php
$page = "Liste des commandes";
require_once("inc/header_back.php");

# Je sélectionne tous mes résultats en BDD pour la table commande
    $result = $pdo->query('SELECT d.id_detail_commande,c.id_commande,d.quantite, c.date_enregistrement, c.montant, p.reference,p.titre, p.photo,m.code_postal,m.adresse,c.etat FROM commande c , detail_commande d, produit p,membre m WHERE c.id_commande = d.id_commande AND d.id_produit = p.id_produit AND c.id_membre = m.id_membre ORDER BY c.date_enregistrement');
    $lignes = $result->fetchAll();

    if(isset($_POST['etatSub'])){
        
        $result = $pdo->prepare("UPDATE commande SET etat = :etat  WHERE id_commande = :id ");
        $result->bindValue(':id',$_POST['id'],PDO::PARAM_INT);
        $result->bindValue(':etat',$_POST['etat'],PDO::PARAM_STR);
        $result->execute();
        header('location:list_commande.php');


    }
    
    // debug($commandes);
    
    $contenu .= "<div class='table-responsive'>";
    $contenu .= "<table class='table table-striped table-sm'>";
    $contenu .= "<thead class='thead-dark'><tr>";
    
    for($i= 2; $i < $result->columnCount(); $i++)
    {
        $colonne = $result->getColumnMeta($i);
        $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
    
    }

    if(!empty($_GET['id']) ){
        echo '<form method="post" action="#">
            <input type="hidden" name="id" value="'.$_GET['id'].'" />
            <select class="form-control" id="etat" name="etat">
                <option value=""></option>
                <option value="en preparation">en preparation</option>
                <option value="envoyé">envoyé</option>
                <option value="livré">livré</option>
                    
                </select>

            <button type="submit" name="etatSub" />OK</button>
        </form>';

    }

    
    
    $contenu .= "<th colspan='2'>Actions</th>";
    $contenu .= "</tr></thead><tbody>";
    
    //debug($commandes);
    
        foreach($lignes as $ligne)
        {
    
            $contenu .= "<tr>";
            foreach ($ligne as $key => $value) 
            {
                if($key == "id_detail_commande" ||$key == "id_commande"){

                }
                else if($key == "photo")
                {
                    $contenu .= "<td><img height='100' src='" . URL . "assets/uploads/admin/" . $value . "' alt='" . $ligne['reference'] . "'/></td>";
                }
                else 
                {
                    $contenu .= "<td>" . $value . "</td>";  
                }
                
            }
    
            $contenu .= "<td><a href='list_commande.php?id=" . $ligne['id_commande'] . "'><i class='fas fa-pen'></i></a></td>";
    
            $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $ligne['id_commande'] . "'><i class='fas fa-trash-alt'></i></a></td>";
    
            # J'appelle ma modal de supression (fonction créée dans fonction.php)
            
    
            $contenu .= "</tr>";
        }
    
    $contenu .= "</tbody></table>";
    $contenu .= "</div>";

    deleteModal($ligne['id_commande'],$ligne['date_enregistrement'] ,$ligne['reference'] );
?>

    <?= $msg ?>
    <?= $contenu ?>
    
    <?php require_once("inc/footer_back.php"); 
    
?>


