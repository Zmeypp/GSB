<?php
    session_start();
    include('./Connection_BDD.php');
    $conn = getBdd('localhost', 'groupe3', 'sio2021');

    $reqVerifVehiculeUserInDb = $conn->prepare("SELECT * from gsb_frais.vehicule_utilisateur WHERE idUser='".$_SESSION['id']."'");
    $reqVerifVehiculeUserInDb->execute();
    $VehiculeExist = $reqVerifVehiculeUserInDb->rowCount();


    if(isset($_POST['formAjoutVehicule'])) {
        if($VehiculeExist == 1) {
            //MODIFICATION DU VEHICULE DANS BDD

            $reqModificationVehiculeUser = $conn->prepare("UPDATE gsb_frais.vehicule_utilisateur SET idUser='".$_SESSION['id']."', Marque='".$_POST['MarqueVehicule']."', Modele='".$_POST['ModeleVehicule']."', chevauxFiscaux='".$_POST['ChevauxVehicule']."';");
            $reqModificationVehiculeUser->execute();

            header("Location: Saisir_Vehicule.php");
        }

        else {
            //CREATION VEHICULE DANS BDD

            $reqCreationVehiculeUser = $conn->prepare("INSERT INTO gsb_frais.vehicule_utilisateur (idUser, Marque, Modele, chevauxFiscaux) VALUES('".$_SESSION['id']."', '".$_POST['MarqueVehicule']."', '".$_POST['ModeleVehicule']."', '".$_POST['ChevauxVehicule']."');");
            $reqCreationVehiculeUser->execute();

            header("Location: Saisir_Vehicule.php");
        }
    }
?>

<html>
    <head>
        <title>Saisir un véhicule</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="block">
            <header class="header navBarHeader">
                <a href="#" class="header-logo image is-96x96"><img src="images/bonsoir.png"/></a>
                <nav class="header-menu">
                    <a href="profil.php"><i class="fas fa-home"></i> Accueil</a>
                    <a href="Renseigner_Fiche.php"><i class="fas fa-pen"></i> Renseigner mes fiches de frais</a>
                    <a href="Consulter_Fiche.php"><i class="fas fa-folder-open"></i>Consulter une fiche de frais</a>
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>


        <form method="post">
            <center>
                <div class="block animated fadeInDown slow decadeDiv">
                    <div class="box vehiculeBox">
                        <div class="field">
                            <h1>Saisir votre véhicule</h1>
                        </div>
                        <div class="field">
                            Marque du véhicule :
                            <input class="input" type="text" name="MarqueVehicule" placeholder="Exemple : Ford" />
                        </div>
                        <div class="field">
                            Modèle du véhicule :
                            <input class="input" type="text" name="ModeleVehicule" placeholder="Exemple : Kuga" />
                        </div>
                        <div class="field">
                            Nombre de chevaux fiscaux du véhicule :
                            <input class="input" type="text" name="ChevauxVehicule" placeholder="Exemple : 7" />
                        </div>
                        <br />
                        <button class="button is-primary buttonSaisirVehicule" name="formAjoutVehicule">Ajouter !</button>
                    </div>
                </div>
            </center>
        </form>

        <center>
            <div class="container about animated fadeInDown slow">
                <div class="columns">
                    <div class="column about-single-element">
                        <?php
                            if($VehiculeExist == 1) {
                                echo "<br />";
                                echo "<br />";
                                echo "<div class='notification is-warning'>";
                                    echo "Vous possédez déjà un véhicule. Remplir ce formulaire modifiera votre véhicule actuel.";
                                echo "</div>";
                            }

                            else {
                                echo "<br />";
                                echo "<br />";
                                echo "<div class='notification is-danger'>";
                                    echo "Vous ne possédez aucun véhicule. Remplissez ce formulaire au plus vite.";
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </center>

    </body>
</html>
