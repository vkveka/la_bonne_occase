<?php
include 'head.php';
include 'functions.php';
session_start();
// phpinfo();

$etat = "";
?>

<body id="body-connexion">
    <?php
    if (isset($_SESSION['id'])) {
    ?>

        <!-- Formulaire d'ajout de fichier en base de données -->
        <section>
            <div class="container mt-5">
                <div class="text-light text-center my-5 d-flex">
                    <div class="col-md-4 col-7 offset-md-4">
                        <h1><b>Ajouter des photos</b></h1>
                    </div>
                    <div class="col-4 text-end">
                        <form action="index.php" method="post">

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#deconnect-session">
                                <img id="logout" src="./images/icons/logout.png" alt="" style="width: 2em;">
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="deconnect-session" tabindex="-1" aria-labelledby="deconnect-sessionLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header">
                                            <!-- <h1 class="modal-title fs-5" id="deconnect-sessionLabel">Deconnexion</h1> -->
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <h5>Confirmer la deconnexion ?</h5>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>


                                            <input type="hidden" name="deconnect" value="true">
                                            <button class="btn btn-danger" type="submit">
                                                Se déconnecter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </form>
                    </div>
                </div>
                <div class="col-md-8 mx-auto main-form">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating text-dark">
                                    <input type="text" id="title_data" name="title_data" class="form-control is valid" placeholder="Focus RS 2022">
                                    <label for="title_data" class="form-label">Ajouter une légende</label>
                                    <br>
                                    <div class="form text-dark">
                                        <input type="text" id="desc_data" name="desc_data" class="form-control is valid" placeholder="https://leboncoin.fr/...">
                                    </div>
                                    <br>
                                    <div class="form">
                                        <input type="file" id="file_data" name="file_data" class="form-control is valid" required>
                                    </div>
                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radio_data" id="flexRadioDefault1" value="à vendre" required>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <b>à vendre</b>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radio_data" id="flexRadioDefault2" value="vendue">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            <b>vendue</b>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 label_info">
                                <div class="row">
                                    <div class="col-auto">
                                        <label for="year_data" class="form-label"><b>Année</b></label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="year_data" name="year_data" class="form-control is valid" placeholder="1991">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-auto">
                                        <label for="km_data" class="form-label"><b>Kilométrage</b></label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="km_data" name="km_data" class="form-control is valid" placeholder="150 000">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-auto">
                                        <label for="fuel_data" class="form-label"><b>Carburant</b></label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="fuel_data" name="fuel_data" class="form-control is valid" placeholder="Jus de betterave">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-3 text-center">
                                <input type="hidden" name="valid_data" value="true">
                                <button class="btn btn-light mt-5" type="submit">
                                    <i class="fa-solid fa-car fa-2x text-danger"></i>
                                    <i class="fa-solid fa-car fa-2x text-warning"></i>
                                    <i class="fa-solid fa-car fa-2x text-success"></i>
                                </button><br>
                            </div>

                        </div>
                        <?php if (isset($_POST['valid_data'])) {
                            $status = isset($_POST['radio_data']) && $_POST['radio_data'] == 'vendue' ? 2 : 1;
                            // var_dump($_FILES['file_data']);
                            $tmpName = $_FILES['file_data']['tmp_name'];
                            $name = $_FILES['file_data']['name'];
                            $size = $_FILES['file_data']['size'];
                            $error = $_FILES['file_data']['error'];
                            if (!checkPicture($name)) {
                                $name = 'copie_' . $name;
                            }
                            if ($error != 0) {
                                $etat = 'Une erreur est survenue...';
                            } else {
                                $etat = 'Ajout du fichier réussi !';
                                move_uploaded_file($tmpName, './uploads/' . $name);
                                $db = getConnection();
                                $query = $db->prepare("INSERT INTO annonces (title, picture, year, km, fuel, description, status, created_at) VALUES (?,?,?,?,?,?,?,?)");
                                $query->execute(([$_POST['title_data'], $name, $_POST['year_data'], $_POST['km_data'], $_POST['fuel_data'], $_POST['desc_data'], $status, date('Y-m-d H:i:s')]));

                        ?>
                                <script>
                                    setTimeout(function() {
                                        window.location.replace("Rhlmcabr0n.php");
                                    }, 0000); // millisecondes
                                </script>
                        <?php
                            }
                        }
                        ?>
                        <div class="row mt-3 justify-content-end">
                            <div class="col-md-4">
                                <p <?php if ($etat == 'Une erreur est survenue...') { ?> style="color: red;" <?php } else { ?> style="color: green;" <?php } ?>><?= $etat ?></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>


        <!-- Sélction du catalogue -->
        <section id="catalogue">
            <div class="head-catalogue col-lg-3 col-md-4 col-sm-6 col-10 mx-auto">
                <h1 class="text-center">CATALOGUES</h1>
                <select class="form-select" aria-label="séléction du catalogue">
                    <option id="catalogue-forsell">A vendre</option>
                    <option id="catalogue-sold">Vendues</option>
                </select>
            </div>

            <!-- Espace catalogue des voitures à vendre -->
            <div class="col-lg-10 mx-auto">
                <div class="row justify-content-center text-center p-5" id="box-forsell">
                    <?php
                    showCatalogueForSell(getCatalogueForSell());

                    if (isset($_POST['move-to-sold'])) {
                        $annonce = getArticleFromId($_POST['move-to-sold']);
                        moveToSold($annonce);

                    ?> <script>
                            setTimeout(function() {
                                window.location.replace("Rhlmcabr0n.php");
                            }, 0000); // millisecondes
                        </script>
                    <?php
                    }
                    if (isset($_POST['delete'])) {
                        $delete = getArticleFromId($_POST['delete']);
                        deleteAnnonce($delete);
                    ?> <script>
                            setTimeout(function() {
                                window.location.replace("Rhlmcabr0n.php");
                            }, 0000); // millisecondes
                        </script>
                    <?php
                    }
                    if (isset($_POST['valid_edit'])) {
                        $update = getArticleFromId($_POST['valid_edit']);
                        $title = $_POST['title_data_edit'];
                        $desc = $_POST['desc_data_edit'];
                        updateAnnonce($title, $desc, $update);
                    }
                    ?>
                </div>
            </div>


            <!-- Espace catalogue des voitures vendues -->
            <div class="col-lg-10 mx-auto">
                <div class="row justify-content-center text-center p-5" id="box-sold">
                    <?php
                    showCatalogueSold(getArticlesSold());
                    if (isset($_POST['move-to-forsell'])) {
                        $annonce = getArticleFromId($_POST['move-to-forsell']);
                        // var_dump($annonce);
                        moveToForsell($annonce);
                    }
                    if (isset($_POST['delete'])) {
                        $delete = getArticleFromId($_POST['delete']);
                        deleteAnnonce($delete);
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Affiche et cache les catalogues -->
        <script>
            // Récupération des éléments HTML
            const boxForsell = document.getElementById("box-forsell");
            const boxSold = document.getElementById("box-sold");
            const btnForsell = document.getElementById("catalogue-forsell");
            const btnSold = document.getElementById("catalogue-sold");

            // Fonction pour afficher la boîte "A vendre"
            function showForsell() {
                boxForsell.style.display = "flex";
                boxSold.style.display = "none";
            }

            // Fonction pour afficher la boîte "Vendues"
            function showSold() {
                boxSold.style.display = "flex";
                boxForsell.style.display = "none";
            }

            // Ajout d'un écouteur d'événement sur les boutons
            btnForsell.addEventListener("click", showForsell);
            btnSold.addEventListener("click", showSold);

            // Par défaut, on affiche la boîte "A vendre"
            showForsell();
        </script>


        <!-- Affiche et cache les options d'édit -->
        <script>
            // Récupération des éléments HTML
            const boxEdit1 = document.getElementsByClassName("edit_annonce_forsell");
            const boxShow1 = document.getElementsByClassName("show_annonce_forsell");
            const btnEdit1 = document.getElementsByClassName("btn_edit_annonce_forsell");
            // const btnShow1 = document.getElementsByClassName("btn_valid_annonce_forsell");
            const btnBack1 = document.getElementsByClassName("back_forsell");

            const boxEdit2 = document.getElementsByClassName("edit_annonce_sold");
            const boxShow2 = document.getElementsByClassName("show_annonce_sold");
            const btnEdit2 = document.getElementsByClassName("btn_edit_annonce_sold");
            // const btnShow2 = document.getElementsByClassName("btn_valid_annonce_sold");
            const btnBack2 = document.getElementsByClassName("back_sold");

            // A VENDRE ************************************
            // Fonction pour afficher la boîte "Edit"


            for (let i = 0; i < btnEdit1.length; i++) {
                btnEdit1[i].addEventListener("click", function() {
                    boxEdit1[i].style.display = "flex";
                    boxShow1[i].style.display = "none";
                });

                btnBack1[i].addEventListener("click", function() {
                    boxEdit1[i].style.display = "none";
                    boxShow1[i].style.display = "flex";
                });
            }

            for (let i = 0; i < btnEdit2.length; i++) {
                btnEdit2[i].addEventListener("click", function() {
                    boxEdit2[i].style.display = "flex";
                    boxShow2[i].style.display = "none";
                });

                btnBack2[i].addEventListener("click", function() {
                    boxEdit2[i].style.display = "none";
                    boxShow2[i].style.display = "flex";
                });
            }
        </script>

        <?php

    } else {
        if (isset($_POST['valid_inscription'])) {
            if ($_POST['mot_de_passe'] == $_POST['confirm_mot_de_passe']) {
                inscription();
            } else {
                echo "<script> alert(\"Les mots de passe ne sont pas identiques !\");</script>";
            }
        }

        if (checkAdmin()) {
            // ***********************************************************************************************************
            // ***************************************** INTERFACE DE CONNEXION ****************************************** 
            // ***********************************************************************************************************
        ?>

            <body class="bg-secondary">
                <div class="container" id="form_connection">
                    <div class="text-light text-center m-5">
                        <i class="fa-regular fa-user fa-4x"></i>
                        <h1><b>Connexion</b></h1>
                    </div>
                    <div class="col-md-5 p-5 mx-auto text-white bg-connexion">
                        <form action="Rhlmcabr0n.php" method="post">
                            <div class="row mx-auto text-light">
                                <div class="col-md-12 form-floating">
                                    <input type="text" id="pseudo" name="pseudo" class="form-control is valid text-light" placeholder="Pseudo" required>
                                    <label for="pseudo" class="ms-3"><b>Pseudo</b></label>
                                </div>
                                <div class="col-md-12 mt-4 form-floating">
                                    <input type="password" id="password" name="mot_de_passe" class="form-control is valid text-light" placeholder="Tapez votre mot de passe" autocomplete="new-password" required>
                                    <label for="password" class="ms-3"><b>Mot de passe</b></label>
                                </div>
                            </div>
                            <div class="row text-center mt-5 row-img-btn" style="display: flex; align-items: center">
                                <div class="col-md-4 mx-auto">
                                    <img class="img_1" src="./images/Fintosgarage_final.png" alt="logo_lbo" style="width: 60px; margin-right: 0px">
                                </div>
                                <div class="col-md-4 mx-auto">
                                    <input type="hidden" name="valid_connexion" value="true">
                                    <button class="btn btn-warning" type="submit" style="width:130px">Se connecter</button>
                                </div>
                                <div class="col-md-4 mx-auto">
                                    <img class="img_2" src="./images/Fintosgarage_final.png" alt="logo_lbo" style="width: 60px; margin-right: 0px">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </body>
        <?php
        } else {

            // ***********************************************************************************************************
            // ***************************************** INTERFACE D'INSCRIPTION ****************************************** 
            // ***********************************************************************************************************
        ?>



            <body class="bg-secondary">
                <div class="container" id="form_inscription" style="z-index: 1;">
                    <div class="text-dark text-center m-5">
                        <i class="fa-regular fa-user fa-4x text-dark"></i>
                        <h1><b>Création compte ADMIN</b></h1>
                    </div>
                    <div class="col-md-8 mx-auto bg-dark text-white" style="border:1px solid black; padding:50px; border-radius:10px">

                        <form action="Rhlmcabr0n.php" method="POST">
                            <div class="row">
                                <div class="col-md-7 mx-auto">
                                    <label for="pseudo" class="form-label">Pseudo</label>
                                    <input type="text" id="pseudo" name="pseudo" class="form-control is valid" placeholder="Pseudo" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 offset-2">
                                    <label for="mdp" class="form-label">Mot de passe</label>
                                    <input type="password" id="mdp" name="mot_de_passe" class="form-control is valid" placeholder="Tapez votre mot de passe" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="mdp_confirm" class="form-label">Confirmez votre mot de passe</label>
                                    <input type="password" id="mdp_confirm" name="confirm_mot_de_passe" class="form-control is valid" placeholder="Tapez votre mot de passe" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 text-center mx-auto">
                                    <input type="hidden" name="valid_inscription" value="true">
                                    <button class="btn btn-light mt-5" type="submit" style="width:150px">S'inscrire</button><br>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </body>
    <?php
        }


        if (isset($_POST['valid_connexion'])) {
            connexion($_POST['pseudo'], $_POST['mot_de_passe']);
        }
    }
    ?>
</body>