<?php
$page = "Liste des utilisateurs";
require_once("inc/header_back.php");

if(isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "delete" && is_numeric($_GET['id'])) # la fonction is_numeric() me permet de vérifier que le paramètre rentré est bien un chiffre
    {
        debug($_GET['id']);
        $req = "SELECT * FROM membre WHERE id_membre = :id";
        $result = $pdo->prepare($req);
        $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $result->execute();
        // debug($result);

        if($result->rowCount() == 1)
        {
            $user = $result->fetch();
            
            //debug($produit);
            
            $delete_req = "DELETE FROM membre WHERE id_membre = $user[id_membre]";
            
            $delete_result = $pdo->exec($delete_req);
        }
        if($delete_result)
        {
            header("location:list_user.php?m=success");

        }
        else{
            
        }

        if(isset($_GET['m']) && !empty($_GET['m']))
    {
        switch($_GET['m'])
        {
            case "success":
            $msg .= "<div class='alert alert-success'>Le user a bien été supprimé.</div>";
            break;
            case "fail":
            $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
            case "update":
            $msg .= "<div class='alert alert-success'>Le user a bien été mis à jour.</div>";
            break;
            default:
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
    }


    }

 # Je sélectionne tous mes résultats en BDD pour la table produit
 $result = $pdo->query('SELECT * FROM membre');
 $users = $result->fetchAll();
 
 // debug($user $contenu .= "<div class='table-responsive'>";
 $contenu .= "<table class='table table-striped table-sm'>";
 $contenu .= "<thead class='thead-dark'><tr>";
 
 for($i= 0; $i < $result->columnCount(); $i++)
 {
     if($i == 2 || $i == 0 ){

     }
     else{
        $colonne = $result->getColumnMeta($i);
        $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
    }
     
 
 }
 
 $contenu .= "<th colspan='2'>Actions</th>";
 $contenu .= "</tr></thead><tbody>";
 
 //debug($produits);
 
     foreach($users as $user)
     {
 
         $contenu .= "<tr>";

         foreach ($user as $key => $value) 
         {
             if($key == 'mdp' || $key == 'id_membre'){

             } else{
                 $contenu .= "<td>" . $value . "</td>";  
             }
             
         }
 
         $contenu .= "<td><a href='../inscription.php?id=" . $user['id_membre'] . "'><i class='fas fa-pen'></i></a></td>";
 
         $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $user['id_membre'] . "'><i class='fas fa-trash-alt'></i></a></td>";
 
         # J'appelle ma modal de supression (fonction créée dans fonction.php)
         deleteModal($user['id_membre'], $user['pseudo'], $user['nom']);
 
         $contenu .= "</tr>";
     }
 
 $contenu .= "</tbody></table>";
 $contenu .= "</div>";
?>
<?=$contenu?>


<?php require_once("inc/footer_back.php"); ?>