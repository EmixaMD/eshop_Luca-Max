<?php

# La fonction utilisateur debug() nous permettra d'appeler à souhait un var_dump/print_r où l'on souhaite avec en prime les informations liées au fichier + la ligne où le debug a été appelé ! Je me permet de choisir aussi entre un print_r() et un var_dump() tout en laissant par défaut le var_dump()
function debug($var, $mode = 1)
{
    echo "<div class='alert alert-warning'>";
        
        $trace = debug_backtrace(); # la fonction debug_backtrace() nous permet de tracer l'endroit où notre fonction est appelée. Cependant, elle nous retourne un array multi-dimensionnel

        //var_dump($trace);

        $trace = array_shift($trace); # la fonction array_shift() me permet de retourner le résultat en array simple

        echo "Le debug a été appelé dans le fichier $trace[file] à la ligne $trace[line] <hr>";

        echo "<pre>";

            switch ($mode) {
                case '1':
                    var_dump($var);
                    break;
                default:
                    print_r($var);
                    break;
            }
            
        echo "</pre>";

    echo "</div>";
}

# Fonction pour vérifier que l'utilisateur est connecté
function userConnect()
{
    // if(isset($_SESSION['user']))
    // {
    //     return TRUE;
    // }
    // else 
    // {
    //     return FALSE;    
    // }

    if(isset($_SESSION['user'])) return TRUE;
    else return FALSE;
}

# Fonction pour vérifier que l'utilisateur est ADMIN
function userAdmin()
{
    if(userConnect() && $_SESSION['user']['statut'] == 1) return TRUE;
    else return FALSE;
}

# Création d'une modal de suppression
function deleteModal($id, $titre, $reference)
{
    echo "<div class='modal fade' id='deleteModal" . $id . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo "<h5 class='modal-title' id='exampleModalLabel'>Suppression</h5>";
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo "Êtes-vous sûr de vouloir supprimer le produit " . $titre . " (référence: " . $reference . " ) ?";
                echo '</div>';
                echo '<div class="modal-footer">';
                echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>';
                echo '<a href="?a=delete&id=' . $id . '" class="btn btn-danger">Supprimer</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
}

# Création d'une fonction pour créer et ajouter au panier
function ajoutPanier($id, $quantite, $photo, $titre, $prix)
{
    if(!isset($quantite))
    {
        $quantite = 1;
    }
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = array(); // si jamais je ne trouve pas de panier, je créé un array
    }

    if(!isset($_SESSION['panier'][$id])) # Si la référence du produit n'existe pas en $_SESSION, je créée une ligne la concernant dans mon tableau
    {
        $_SESSION['panier'][$id] = array();
        $_SESSION['panier'][$id]['quantite'] = $quantite;
        $_SESSION['panier'][$id]['photo'] = $photo;
        $_SESSION['panier'][$id]['titre'] = $titre;
        $_SESSION['panier'][$id]['prix'] = $prix;
    }
    else # Le produit est déjà en panier, j'ajoute la quantité à celle existante
    {
        $_SESSION['panier'][$id]['quantite'] += $quantite;
    }
}

// Nous créons une fonction pour compter le nombre de produit dans le panier afin d'y afficher une bulle
function nombreProduit() 
{
    $quantiteProduit = 0; // Nous commençons le décompte à 0

    if (!empty($_SESSION['panier']))  // Nous regardons si le panier est créé
    {
        foreach ($_SESSION['panier'] as $produit) 
        {
            $quantiteProduit += $produit['quantite']; // nous rassemblons toutes les quantités ensemble
        }
    }

    return $quantiteProduit;
}

// Nous créons une fonction pour retourner le prix total du panier
function prixTotal() 
{
    $total = 0;
    
    if(!empty($_SESSION['panier'])) 
    {
        foreach ($_SESSION['panier'] as $produit) 
        {
            $total += $produit['prix'] * $produit['quantite'];
        }
    }
    
    return $total;
}

//Fonction inscription/modification utilisateur

function userModif($var)
{
    global $msg;
    // debug($var, 2);

    # Je vérifie le pseudo
    if(!empty($var['pseudo']))
    {
        $pseudo_verif = preg_match("#^[a-zA-Z0-9-._]{3,20}$#", $var['pseudo']);
        # Ici, nous allons utiliser une expression régulière (REGEX). Une REGEX nous permet de vérifier une condition.
        # la fonction preg_match() nous permet de vérifier si une variable respecte la REGEX rentrée. Elle prend 2 arguments : REGEX + le résultat à vérifier. Elle nous retourne un TRUE/FALSE

        if(!$pseudo_verif) # équivaut à dire $pseudo_verif est FALSE
        {
            $msg .= "<div class='alert alert-danger'>Votre pseudo doit contenir des lettres (minuscules ou majuscules), un chiffre et doit posséder entre 3 et 20 caractères. Vous pouvez utiliser un caractère spécial ('-', '.', '_'). Veuillez réessayer !</div>";
        }

    }
    else 
    {
        $msg .= "<div class='alert alert-danger'>Veuillez rentrer un pseudo.</div>";
    }

    # Je vérifie le password
    if(!empty($var['password']))
    {
        $password_verif = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#', $var['password']);

        if(!$password_verif)
        {
            $msg .= "<div class='alert alert-danger'>Votre mot de passe doit contenir entre 6 et 15 caractères avec au moins une majuscule, une minuscule, un nombre et un symbole. Veuillez réessayer !</div>";
        }
    }
    else 
    {
        $msg .= "<div class='alert alert-danger'>Veuillez rentrer un mot de passe.</div>";
    }

    # Je vérifie l'email
    if(!empty($var['email']))
    {
        $email_verif = filter_var($var['email'], FILTER_VALIDATE_EMAIL);
        # la fonction filter_var() me permet de vérifier un résultat (email, URL ...). Elle prend 2 arguments : le résultat à vérifier + la méthode. Nous avons un retour un BOOL (TRUE/FALSE)

        $email_interdits = [
            'mailinator.com',
            'yopmail.com',
            'mail.com'
        ];

        $email_domain = explode('@', $var['email']); # On utilise la function explode() pour exploser un résultat en 2 partie selon le caractère choisit. Elle prend 2 arguments : le caractère ciblé, le résultat à analyser 

        // debug($email_domain);
        
        if(!$email_verif || in_array($email_domain[1], $email_interdits))
        # la fonction in_array() nous permet de vérifier que le résultat ciblé fait bien partie de l'ARRAY ciblé. Elle prends 2 arguments: le résultat à vérifier + le tableau ciblé
        {
            $msg .= "<div class='alert alert-danger'>Veuillez rentrer un email valide.</div>";
        }

    }
    else 
    {
        $msg .= "<div class='alert alert-danger'>Veuillez rentrer un email.</div>";
    }

    # Je vérifie que la civilité est valide
    if(!isset($var['civilite']) || ($var['civilite'] != "m" && $var['civilite'] != "f" && $var['civilite'] != "o"))
    {
        $msg .= "<div class='alert alert-danger'>Veuillez rentrer votre civilité.</div>";
    }

    // PLACER LES AUTRES VERIFICATIONS ICI
    if ($var == $_POST && !empty($_SESSION)){

        $result = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo");
        $result->bindValue(':pseudo', $var['pseudo'], PDO::PARAM_STR);
        $result->execute();

        if($result->rowCount() == 1)
        {
            $msg .= "<div class='alert alert-danger'>Le pseudo $var[pseudo] est déjà pris, veuillez en choisir un autre.</div>";
        } else {
            if (!empty($var['mdp']))
            {
                if(password_verify($_POST['password'],$user['mdp']))
                {
                    $mdpResult = $pdo->prepare("UPDATE membre SET mdp =:mdp WHERE id_membre = :id_membre");
                    $password_hash = password_hash($var['password'], PASSWORD_BCRYPT);
                    $mdpResult->bindValue(":mdp", $password_hash, PDO::PARAM_STR);
                    $mdpResult->execute;
                } else 
                {
                    $msg .= "<div class='alert alert-danger'>Erreur d'authentification</div>";
                    die();
                }
                
            }
            $result = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, nom=:nom, prenom=:prenom, email=:email, civilite=:civilite, ville=:ville, code_postal=:code_postal, adresse=adresse, statut=:statut) WHERE id_membre = :id_membre");



        }

    }
    elseif($var == $_POST)
    {

        if(empty($msg))
        {
            // check si le pseudo est dispo
            $result = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo");
            $result->bindValue(':pseudo', $var['pseudo'], PDO::PARAM_STR);
            $result->execute();

            if($result->rowCount() == 1)
            {
                $msg .= "<div class='alert alert-danger'>Le pseudo $var[pseudo] est déjà pris, veuillez en choisir un autre.</div>";
            }
            else 
            {
                $result = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, 0)");

                $password_hash = password_hash($var['password'], PASSWORD_BCRYPT); 
                # La fonction password_hash() va nous permettre de crypter sérieusement un mot de passe. Elle prend 2 arguments: le résultat ciblé + la méthode à utiliser

                $result->bindValue(':pseudo', $var['pseudo'], PDO::PARAM_STR);
                $result->bindValue(':mdp', $password_hash, PDO::PARAM_STR);
                $result->bindValue(':nom', $var['nom'], PDO::PARAM_STR);
                $result->bindValue(':prenom', $var['prenom'], PDO::PARAM_STR);
                $result->bindValue(':email', $var['email'], PDO::PARAM_STR);
                $result->bindValue(':civilite', $var['civilite'], PDO::PARAM_STR);
                $result->bindValue(':ville', $var['ville'], PDO::PARAM_STR);
                $result->bindValue(':adresse', $var['adresse'], PDO::PARAM_STR);
                
                $result->bindValue(':code_postal', $var['code_postal'], PDO::PARAM_INT);

                if($result->execute())
                {
                    // $msg .= "<div class='alert alert-success'>Vous êtes bien enregistré.</div>";

                    header("location:connexion.php?m=success");
                }


            }
        }
    }

    # Je souhaite conserver les valeurs rentrées par l'utilisateur durant le processus de rechargement de la page
    $pseudo = (isset($var['pseudo'])) ? $var['pseudo'] : '';
    $prenom = (isset($var['prenom'])) ? $var['prenom'] : '';
    $nom = (isset($var['nom'])) ? $var['nom'] : '';
    $email = (isset($var['email'])) ? $var['email'] : '';
    $adresse = (isset($var['adresse'])) ? $var['adresse'] : '';
    $code_postal = (isset($var['code_postal'])) ? $var['code_postal'] : '';
    $ville = (isset($var['ville'])) ? $var['ville'] : '';
    $civilite = (isset($var['civilite'])) ? $var['civilite'] : '';

    $valeur = array(
        "pseudo" => "$pseudo",
        "prenom" => "$prenom",
        "nom" => "$nom",
        "email" => "$email",
        "adresse" => "$adresse",
        "code_postal" => "$code_postal",
        "ville" => "$ville",
        "civilite" => "$civilite"
    );

    debug($valeur);
    return $valeur;
}