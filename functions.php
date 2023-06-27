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
                $_SESSION['id'] = $user['id']; ?>
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

function getArticlesAll()
{
    $db = getConnection();
    $query = $db->query('SELECT id FROM annonces');
    return $query->fetchAll();
}

function getArticlesSold()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM annonces WHERE status = 2');
    return $query->fetchAll();
}
function getArticlesForSellIndex()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM annonces WHERE status = 1 ORDER BY created_at DESC');
    $result = $query->fetchAll();
    $result = array_slice($result, 0, 3); // garde seulement les 3 premières photos
    return $result;
}
function getCatalogueForSell()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM annonces WHERE status = 1');
    return $query->fetchAll();
}


function getArticleFromId($id)
{
    foreach (getArticlesAll() as $article) {
        if ($article['id'] == $id) {
            return $article['id'];
        }
    }
}


function showPictureSold($pictures)
{
    ?>
    <div class="col-md-6" style="display: flex; justify-content: center;">
        <div id="carousel_sold" class="carousel slide carousel-fade  " data-bs-ride="carousel">
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
                } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel_sold" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel_sold" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <?php
}

function showCatalogueForSell($pictures)
{
    foreach ($pictures as $picture) {
    ?>
        <!-- Visualiser le produit du catalogue -->
        <div class="show_annonce_forsell col-md-3 mb-5 card-catalogue m-5" style="display: block;">
            <img src="./uploads/<?php echo $picture['picture'] ?>" alt="pictures-cars-for-sell">
            <form action="" method="post">
                <input type="hidden" name="move-to-sold" value="<?= $picture['id'] ?>">
                <button type="submit" class="btn btn-dark">Vendre</button>
            </form>
            <div class="d-flex justify-content-center">
                <form action="" method="post">


                    <!-- Button trigger modal -->
                    <input type="hidden" name="btn-modal" value="<?= $picture['id'] ?>">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal<?= $picture['id'] ?>">
                        <img src="./images/icons/trash.png" alt="icône poubelle" style="width: 1em;">
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="modal<?= $picture['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 text-danger" id="exampleModalLabel">SUPPRESSION</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Confirmation de suppression
                                    <h1><?= $picture['title'] ?></h1>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <input type="hidden" name="delete" value="<?= $picture['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
                <button class="btn_edit_annonce_forsell btn btn-warning"><img src="./images/icons/edit-button.png" alt="icône édition" style="width: 1em;"></button>
            </div>
        </div>

        <!-- Modifier les informations du produit du catalogue -->
        <div class="edit_annonce_forsell col-md-3 mb-5 card-catalogue m-5">
            <form action="Rhlmcabr0n.php" method="post">
                <div class="row">
                    <div class="col-12">
                        <div class="form text-dark">
                            <input type="text" id="title_data_edit" name="title_data_edit" class="form-control is valid" placeholder="Ajouter un titre..." required>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="form text-dark">
                            <input type="text" id="desc_data_edit" name="desc_data_edit" class="form-control is valid" placeholder="https://leboncoin.fr/..." required>
                        </div>
                    </div>
                </div>
                <br>
                <input type="hidden" name="valid_edit" value="<?= $picture['id'] ?>">
                <button class="btn_valid_annonce_forsell btn btn-dark" type="submit">Valider</button>
            </form>
            <button class="back_forsell btn btn-warning"><img src="./images/icons/return.png" alt="icône édition" style="width: 1em;"></button>
        </div>


    <?php
    }
    if (empty($picture['id'])) {
    ?>
        <div class="col-md-6 col-sm-8 col-10 mx-auto nocars">
            <h2>Aucune voiture à vendre</h2>
        </div>
    <?php    }
}
function showCatalogueSold($pictures)
{
    foreach ($pictures as $picture) {
    ?>
        <!-- Visualiser le produit du catalogue -->
        <div class="show_annonce_sold col-md-3 mb-5 card-catalogue m-5" style="display: block;">
            <img src="./uploads/<?php echo $picture['picture'] ?>" alt="pictures-cars-for-sell">
            <form action="" method="post">
                <input type="hidden" name="move-to-forsell" value="<?= $picture['id'] ?>">
                <button type="submit" class="btn btn-dark">Vers les ventes</button>
            </form>
            <div class="d-flex justify-content-center">
                <form action="" method="post">
                    <!-- Button trigger modal -->
                    <input type="hidden" name="btn-modal" value="<?= $picture['id'] ?>">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal<?= $picture['id'] ?>">
                        <img src="./images/icons/trash.png" alt="icône poubelle" style="width: 1em;">
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="modal<?= $picture['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 text-danger" id="exampleModalLabel">SUPPRESSION</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Confirmation de suppression
                                    <h1><?= $picture['title'] ?></h1>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <input type="hidden" name="delete" value="<?= $picture['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <input type="hidden" name="delete" value="<?= $picture['id'] ?>"> -->
                    <!-- <button type="submit" class="btn btn-danger"><img src="./images/icons/trash.png" alt="icône poubelle" style="width: 1em;"></button> -->
                </form>
                <button class="btn_edit_annonce_sold btn btn-warning"><img src="./images/icons/edit-button.png" alt="icône édition" style="width: 1em;"></button>
            </div>
        </div>


        <!-- Modifier les informations du produit du catalogue -->
        <div class="edit_annonce_sold col-md-3 mb-5 card-catalogue m-5">
            <form action="Rhlmcabr0n.php" method="post">
                <div class="row">
                    <div class="col-12">
                        <div class="form text-dark">
                            <input type="text" id="title_data_edit" name="title_data_edit" class="form-control is valid" placeholder="Ajouter un titre..." required>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="form text-dark">
                            <input type="text" id="desc_data_edit" name="desc_data_edit" class="form-control is valid" placeholder="https://leboncoin.fr/..." required>
                        </div>
                    </div>
                </div>
                <br>
                <input type="hidden" name="valid_edit" value="<?= $picture['id'] ?>">
                <button class="btn_valid_annonce_sold btn btn-dark" type="submit">Valider</button>
            </form>
            <button class="back_sold btn btn-warning"><img src="./images/icons/return.png" alt="icône édition" style="width: 1em;"></button>
        </div>


    <?php
    }
    if (empty($picture['id'])) {
    ?>
        <div class="col-md-6 col-sm-8 col-10 mx-auto nocars">
            <h2>Aucune voiture vendue</h2>
        </div>
    <?php
    }
}

function showPictureForSellIndex($pictures)
{
    foreach ($pictures as $picture) {
    ?>
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 m-5 card-index-forsell">
            <!-- <div class="col-8 mx-auto"> -->
            <img src="./uploads/<?= $picture['picture'] ?>" alt="pictures-cars-for-sell" style="width: 13em;">
            <!-- </div> -->
            <!-- <div class="col-8"> -->
            <div class="row text-center parent_info_selling">
                <div class="col-md-4 info_selling">
                    <img src="./images/icons/year.png" alt="">
                    <div class="row">
                        <p><?= $picture['year'] ?></p>
                    </div>
                </div>
                <div class="col-md-4 info_selling">
                    <img src="./images/icons/km.png" alt="">
                    <div class="row">
                        <p><?= $picture['km'] ?></p>
                    </div>
                </div>
                <div class="col-md-4 info_selling">
                    <img src="./images/icons/fuel.png" alt="">
                    <div class="row">
                        <p><?= $picture['fuel'] ?></p>
                    </div>
                </div>
            </div>
            <!-- </div> -->
            <div class="link_lbc col-lg-10 col-sm-10 col-6 text-center">
                <a class="btn" target="_blank" href="<?= $picture['description'] ?>">Voir l'annonce</a>
            </div>
        </div>
    <?php
    }
    if (empty($pictures)) {
    ?>
        <div class="col-md-6 col-sm-10 col-10 mx-auto nocars-index text-center">
            <h3>Aucune voiture disponible.</h3>
        </div>
<?php
    }
}


function moveToSold($id)
{
    $db = getConnection();
    $query = $db->prepare('UPDATE annonces SET status = 2 WHERE id = ?');
    $query->execute([$id]);
}
function deleteAnnonce($id)
{
    $db = getConnection();
    $query = $db->prepare('DELETE FROM annonces WHERE id = ?');
    $query->execute([$id]);
}
function moveToForsell($id)
{
    $db = getConnection();
    $query = $db->prepare('UPDATE annonces SET status = 1 WHERE id = ?');
    $query->execute([$id]);
}
function updateAnnonce($title, $desc, $id)
{
    $db = getConnection();
    $query = $db->prepare('UPDATE annonces SET title = ?, description = ? WHERE id = ?');
    $query->execute([$title, $desc, $id]);
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
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>