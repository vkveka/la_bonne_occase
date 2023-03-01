<?php
include 'head.php';
include 'functions.php';
session_start();

if (isset($_SESSION['id'])) {
?>
    <div class="container text-white mt-5" style="background-image: url(./images/etablie_garage_HD.jpg); background-position:center; background-size:cover; height:100vh">
        <div class="text-dark text-center my-5">
            <i class="fa-regular fa-user fa-4x"></i>
            <h1><b>Ajouter des photos</b></h1>
        </div>
        <div class="col-md-8 mx-auto bg-dark" style="border:1px solid black; padding:50px; border-radius:10px">

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating text-dark">
                            <input type="text" id="title_data" name="title_data" class="form-control is valid" placeholder="Focus RS 2022">
                            <label for="title_data" class="form-label">Légende de la photo</label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating text-dark">
                            <input type="text" id="desc_data" name="desc_data" class="form-control is valid" placeholder="C'est une très jolie voiture">
                            <label for="desc_data" class="form-label">Description</label>
                        </div>
                    </div>
                </div>
                <br>
                <!-- <label for="file_data" class="form-label">Ajouter une photo</label> -->
                <input type="file" id="file_data" name="file_data" class="form-control is valid" required>
                <br>
                <div class="row">
                    <div class="col-md-3 text-center mx-auto">
                        <input type="hidden" name="valid_data" value="true">
                        <button class="btn btn-light mt-5" type="submit" style="width:150px">Envoyer</button><br>
                    </div>
                    <form action="index.php" method="post">
                        <div class="col-md-3 text-center mx-auto">
                            <input type="hidden" name="deconnect" value="true">
                            <button class="btn btn-warning mt-5" type="submit" style="width:150px">Déconnexion</button>
                        </div>
                    </form>
                </div>

            </form>
        </div>
    </div>
    <?php
    if (isset($_POST['valid_data'])) {
        var_dump($_FILES['file_data']);
        $tmpName = $_FILES['file_data']['tmp_name'];
        $name = $_FILES['file_data']['name'];
        $size = $_FILES['file_data']['size'];
        $error = $_FILES['file_data']['error'];
        if (!checkPicture($name)) {
            $name = 'copie_' . $name;
        }
        move_uploaded_file($tmpName, './uploads/' . $name);
        $db = getConnection();
        $query = $db->prepare("INSERT INTO annonces (title, picture, description) VALUES (?,?,?)");
        $query->execute(([$_POST['title_data'], $name, $_POST['desc_data']]));
    }

    ?>
    <div class="row">

    </div>
<?php
} else {
?>
    <!-- <div class="container text-white">
        <div class="row" style="margin-top: 2%; padding: 0">
            <form action="Rhlmcabr0n.php" method="post">
                <div class="row">
                    <div class="col-md-8 mx-auto bg-dark" style="border:1px solid black; padding:50px; border-radius:10px">
                        <div class="col-md-10 text-center mx-auto">
                            <img src="./images/Fintosgarage_final.png" alt="logo_lbo" style="width: 100px; margin-right: 100px">
                            <input type="hidden" name="go_to_connection" value="true">
                            <button class="btn btn-light" type="submit" style="height: 60px;">Vous n'êtes pas connecté...</button>
                            <img src="./images/Fintosgarage_final.png" alt="logo_lbo" style="width: 100px; margin-left: 100px">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> -->
    <?php



    if (isset($_POST['valid_inscription'])) {
        if ($_POST['mot_de_passe'] == $_POST['confirm_mot_de_passe']) {
            inscription();
        } else {
            echo "<script> alert(\"Les mots de passe ne sont pas identiques !\");</script>";
        }
    }



    if (checkAdmin()) {
    ?>

        <body class="bg-secondary">
            <div class="container" id="form_connection">
                <div class="text-dark text-center m-5">
                    <i class="fa-regular fa-user fa-4x text-dark"></i>
                    <h1><b>Connexion</b></h1>
                </div>
                <div class="col-md-5 p-5 mx-auto bg-dark text-white" style="border-radius: 10px;">
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
                        <div class="row text-center mt-5" style="display: flex; align-items: center">
                            <div class="col-md-4 mx-auto">
                                <img src="./images/Fintosgarage_final.png" alt="logo_lbo" style="width: 60px; margin-right: 0px">
                            </div>
                            <div class="col-md-4 mx-auto">
                                <input type="hidden" name="valid_connexion" value="true">
                                <button class="btn btn-warning" type="submit" style="width:130px">Se connecter</button>
                            </div>
                            <div class="col-md-4 mx-auto">
                                <img src="./images/Fintosgarage_final.png" alt="logo_lbo" style="width: 60px; margin-right: 0px">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </body>
    <?php
    } else {
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
