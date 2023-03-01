<?php

function getConnection()
{
    // try : je tente une connexion
    try {
        $db = new PDO(
            'mysql:host=localhost;dbname=labonneoccase;charset=utf8',
            'root',
            '',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC)
        );
        // si ça ne marche pas : je mets fin au code php en affichant l'erreur
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    return $db;
}


function checkEmptyFields()
{
    foreach ($_POST as $field) {
        if (empty($field)) {
            return true;
        }
    }
    return false;
}


function checkPassword($password)
{
    // minimum 8 caractères et maximum 15, minimum 1 lettre, 1 chiffre et 1 caractère spécial
    $regex = "^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[@$!%*?/&])(?=\S+$).{8,15}$^";
    return preg_match($regex, $password);
}



function inscription()
{
    /*détail de la fonction : 
    //connexion à la bdd
    */
    $db = getConnection();
    //vérif si champs vides => message d'erreur si c'est le cas
    if (checkEmptyFields()) {
        echo "<script> alert(\"Il y a un ou plusieurs champs vides !\");</script>";
    } else {
        if (!checkPassword($_POST['mot_de_passe'])) {
            //vérif si mdp réunit les critères requis (avec fonction checkpassword ci-dessous)
            echo "<script> alert(\"Mot de passe trop faible !\");</script>";
        } else {
            //hâchage du mot de passe (avec password_hash)
            $password = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            //sauvegarde de l'utilisateur en db 
            $query = $db->prepare("INSERT INTO `admin_` (pseudo, mot_de_passe) VALUES (?,?)");
            $query->execute([$_POST['pseudo'], $password]);
            //10) message de succès
            echo "<script> alert(\"Inscription validée !\");</script>";
        }
    }
}


function checkAdmin()
{
    $db = getConnection();
    $query = $db->prepare('SELECT COUNT(id) as data FROM admin_');
    $query->execute(array());
    $data_count = $query->fetch();
    if ($data_count['data'] > 0) {
        return true;
    } else {
        return false;
    }
}

function connexion($pseudo, $password)
{
    $db = getConnection();
    if (checkEmptyFields()) {
        echo "<script> alert(\"Il y a un ou plusieurs champs vides !\");</script>";
    } else {
        $query = $db->prepare('SELECT * FROM admin_ WHERE pseudo = ?');
        $query->execute([$pseudo]);
        if ($query->rowCount() == 0) {
            echo "<script> alert(\"Pseudo ou mot de passe incorrect\");</script>";
        } else {
            $user = $query->fetch();
            $hashed_password = $user['mot_de_passe'];
            if (password_verify($password, $hashed_password)) {
                $_SESSION['id'] = $user['id'];
                echo "<script> alert(\"Vous êtes connecté !\");</script>";
?>
                <script>
                    window.location.replace("Rhlmcabr0n.php");
                </script>
    <?php
            } else {
                echo "<script> alert(\"Pseudo ou mot de passe incorrect\");</script>";
            }
        }
    }
}


function getArticles()
{
    $db = getConnection();
    $query = $db->query('SELECT `picture` FROM annonces');
    return $query->fetchAll();
}



function getPicture($pictures)
{
    ?>
    <div class="col-md-4 mb-5" style="display: flex; justify-content: center;">
        <div id="carousel_for_sale" class="carousel carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner center-block">
                <?php
                $first = true;
                $id = 0;
                foreach ($pictures as $picture) {
                ?>
                    <div id="<?= $id ?>" class="carousel-item <?php if ($first) {
                                                                    echo 'active';
                                                                    $first = false;
                                                                } ?>" data-bs-interval="4000">
                        <img src="./uploads/<?php echo $picture['picture'] ?>" alt="...">
                    </div>
                <?php
                    $id++;
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel_for_sale" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel_for_sale" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
<?php
}


function checkPicture($picture_name)
{
    $db = getConnection();

    $query = $db->prepare("SELECT * FROM annonces WHERE picture = ?");
    $query->execute([$picture_name]);
    $picture = $query->fetch();
    if ($picture) {
        return false;
    } else {
        return true;
    }
}
