<?php

    # Règles SEO
    $page = "Inscription";
    $seo_description = "Rejoignez le club des meilleures affaires en ligne: jusqu'à -80%";
  

    require_once("inc/header.php");
    debug($_SESSION);
    debug($_POST);
    debug($_FILES);
    

    //conditions en chaines donc if elseif il s'arrete a la premiere condition verifiée
if(userAdmin()&&isset($_GET['modifUser'])&&is_numeric($_GET['modifUser'])){
    $valeur =userModif($_SESSION['target']);

}elseif($_POST && $_SESSION)
{   
    $nom_photo=photoVerif($_POST, $_FILES);
    debug($nom_photo);
    $chemin_photo = RACINE . '/assets/uploads/user/' . $nom_photo;
    $result = $pdo->prepare("UPDATE membre SET photo=:photo WHERE id_membre=:id_membre");
    $result->bindValue(':photo', $nom_photo, PDO::PARAM_STR);
    $result->bindValue(':id_membre', $_SESSION['user']['id_membre'], PDO::PARAM_STR);
    
            if($result->execute()) # Si j'enregistre bien en BDD
            {
                if(!empty($_FILES['photo']['name']))
                {
                    copy($_FILES['photo']['tmp_name'], $chemin_photo);
                }
            }
    $_SESSION['user']['photo']=$nom_photo;
    $valeur = userModif($_POST);
}elseif($_POST)
{   
    $nom_photo=photoVerif();
    $chemin_photo = RACINE . '/assets/uploads/user/' . $nom_photo;
    $result = $pdo->prepare("UPDATE membre SET photo=:photo WHERE id_membre=:id_membre");
    $result->bindValue(':photo', $nom_photo, PDO::PARAM_STR);
    $result->bindValue(':id_membre', $_POST['id_membre'], PDO::PARAM_STR);

    
    if($result->execute()) # Si j'enregistre bien en BDD
            {
                if(!empty($_FILES['photo']['name']))
                {
                    copy($_FILES['photo']['tmp_name'], $chemin_photo);
                }
            }
            $_SESSION['user']['photo']=$nom_photo;
    $valeur = userModif($_POST);
    
    
}elseif($_SESSION['user'])
{
    $valeur = userModif($_SESSION['user']);
    $page = "Modification du profil";
    // debug($valeur);
} else {
    $valeur=array();
    $valeur['pseudo']='';
    $valeur['prenom']='';
    $valeur['nom']='';
    $valeur['email']='';
    $valeur['civilite']='';
    $valeur['adresse']='';
    $valeur['code_postal']='';
    $valeur['ville']='';
}


?>

    <div class="starter-template">
    <h1><?= $page ?></h1>
        <form action="" method="post" enctype="multipart/form-data">
            <small class="form-text text-muted">Vos données ne seront revendues qu'à des services tiers.</small>
            <?= $msg ?>
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" placeholder="Choisissez votre pseudo ..." name="pseudo" required <?php if($_SESSION) {echo "disabled";}?> value="<?= $valeur['pseudo'] ?>">
                
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="Choisissez votre mot de passe ..." name="password" <?php if(!$_SESSION) {echo 'required';}?>>
            </div>
            <?php 
                if($_SESSION){
                    echo ' <div class="form-group">
                    <label for="passwordNew">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="passwordNew" placeholder="Choisissez votre nouveau mot de passe ..." name="passwordNew">
                </div>';
                }
            ?>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" placeholder="Quel est votre prénom ..." name="prenom" value="<?= $valeur['prenom'] ?>">
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" placeholder="Quel est votre nom ..." name="nom" value="<?= $valeur['nom'] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Entrez votre email ..." name="email" value="<?= $valeur['email'] ?>">
            </div>
            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" id="civilite" name="civilite">
                    <option value="f" <?php if($valeur['civilite'] == 'f'){echo 'selected';} ?> >Femme</option>
                    <option value="m" <?php if ($valeur['civilite'] == 'm') {echo 'selected';} ?> >Homme</option>
                    <option value="o" <?php if ($valeur['civilite'] == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" placeholder="Quelle est votre adresse ..." name="adresse" value="<?= $valeur['adresse'] ?>">
            </div>
            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input type="text" class="form-control" id="code_postal" placeholder="Quel est votre code postal ..." name="code_postal" value="<?= $valeur['code_postal'] ?>">
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" placeholder="Quelle est votre ville ..." name="ville" value="<?= $valeur['ville'] ?>">
            </div>
            <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
            <?php

                if(isset($modif_produit))
                {
                    echo "<input name='photo_actuelle' value='$photo' type='hidden'>";
                    echo "<img style='width:25%;' src='" . URL . "/assets/uploads/admin/$photo'>";
                }

            ?>
            </div>
            <?php
                if(userAdmin()){
                    echo "    <select name='statut' id='statut'>
                    <option value='0'>Utilisateur classique</option>
                    <option value='1'>administrateur</option>
                </select>";
                }
            ?>
            <button type="submit" class="btn btn-primary btn-lg btn-block"><?=$page?></button>
        </form>
    </div>


<?php require_once("inc/footer.php");?>