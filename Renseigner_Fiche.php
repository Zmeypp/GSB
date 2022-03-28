<?php
session_start();
include('./Connection_BDD.php');
$conn = getBdd('localhost', 'root', '');

if(isset($_POST['formajoutforfait'])) {
    $nombrerepasmidi = htmlspecialchars($_POST['nbrrepas']);
    $nombrenuitée = htmlspecialchars($_POST['nbrnuitees']);
    $nombreetapes = htmlspecialchars($_POST['nbretape']);
    $nombrekm = htmlspecialchars($_POST['nbrkm']);
    $mois = htmlspecialchars(date('Ym'));
    $etape = htmlspecialchars('ETP');
    $kilometre = htmlspecialchars('KM');
    $nuitée = htmlspecialchars('NUI');
    $repas = htmlspecialchars('REP');
    if(!empty($nombrerepasmidi) && !empty($nombrenuitée) && !empty($nombreetapes) && !empty($nombrekm)) {
        $reqetape = $conn->prepare("INSERT INTO gsb_frais.lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES(:idVisiteur, :mois, :idFraisForfait, :quantite);");
        $reqetape->bindParam(':idVisiteur', $_SESSION['id']);
        $reqetape->bindParam(':mois', $mois);
        $reqetape->bindParam(':idFraisForfait', $etape);
        $reqetape->bindParam(':quantite', $nombreetapes);
        $reqetape->execute();

        $reqkilometre = $conn->prepare("INSERT INTO gsb_frais.lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES(:idVisiteur, :mois, :idFraisForfait, :quantite);");
        $reqkilometre->bindParam(':idVisiteur', $_SESSION['id']);
        $reqkilometre->bindParam(':mois', $mois);
        $reqkilometre->bindParam(':idFraisForfait', $kilometre);
        $reqkilometre->bindParam(':quantite', $nombrekm);
        $reqkilometre->execute();

        $reqnuitée = $conn->prepare("INSERT INTO gsb_frais.lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES(:idVisiteur, :mois, :idFraisForfait, :quantite);");
        $reqnuitée->bindParam(':idVisiteur', $_SESSION['id']);
        $reqnuitée->bindParam(':mois', $mois);
        $reqnuitée->bindParam(':idFraisForfait', $nuitée);
        $reqnuitée->bindParam(':quantite', $nombrenuitée);
        $reqnuitée->execute();

        $reqrepas = $conn->prepare("INSERT INTO gsb_frais.lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES(:idVisiteur, :mois, :idFraisForfait, :quantite);");
        $reqrepas->bindParam(':idVisiteur', $_SESSION['id']);
        $reqrepas->bindParam(':mois', $mois);
        $reqrepas->bindParam(':idFraisForfait', $repas);
        $reqrepas->bindParam(':quantite', $nombrerepasmidi);
        $reqrepas->execute();
    }
    else {
        $erreur = "Tous les champs doivent être complétés !";
    }
}

if(isset($_POST['formajoutnoforfait'])) {
    $date = htmlspecialchars(date('Y-m-d'));
    $libelle = htmlspecialchars($_POST['libelle']);
    $montant = htmlspecialchars($_POST['montant']);
    $mois = htmlspecialchars(date('Ym'));

    $reqhorsforfait = $conn->prepare("INSERT INTO gsb_frais.lignefraishorsforfait (id, idVisiteur, mois, libelle, `date`, montant) VALUES(:id, :idVisiteur, :mois, :libelle, :datehorsforfait, :montant);");
    $reqhorsforfait->bindParam(':id', $ProchaineID);
    $reqhorsforfait->bindParam(':idVisiteur', $_SESSION['id']);
    $reqhorsforfait->bindParam(':mois', $mois);
    $reqhorsforfait->bindParam(':libelle', $libelle);
    $reqhorsforfait->bindParam(':datehorsforfait', $date);
    $reqhorsforfait->bindParam(':montant', $montant);
    $reqhorsforfait->execute();
}
else {
    $erreur = "Tous les champs doivent être complétés !";
}

?>

<html>
    <head>
        <title>Renseigner une fiche de frais</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap">
    </head>

    <body>
        <div class="block">
            <header class="header navBarHeader">
                <a href="#" class="header-logo image is-96x96"><img src="images/bonsoir.png"/></a>
                <nav class="header-menu">
                    <a href="profil.php"><i class="fas fa-home"></i> Accueil</a>
                    <a href="Consulter_Fiche.php"><i class="fas fa-folder-open"></i> Consulter mes fiches de frais</a>
                    <a href="Saisir_Vehicule.php"><i class="fas fa-car"></i> Saisir un véhicule</a>
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>

        <form method="post">
            <center>
                <div class="block animated fadeInDown slow decadeDiv">
                    <div class="box periodeBox">
                        <div class="field">
                            <h1>Période</h1>
                        </div>
                        <div class="field">
                            <?php setlocale(LC_TIME, 'fra_fra'); ?>

                            <?php echo '<p class="strftime">'.date('M Y').'</p>'; ?>
                        </div>
                    </div>
                </div>
            </center>

            <center>
                <div class="block animated fadeInDown slow divForfait">
                    <div class="box fraisForfaitBox" method="post">
                        <div class="field">
                            <h1>Frais au forfait</h1>
                        </div>
                        <div class="field">
                            Nombre de repas au midi :
                            <input class="input" type="text" name="nbrrepas" placeholder="Exemple : 9" />
                        </div>
                        <div class="field">
                            Nombre de nuitées :
                            <input class="input" type="text" name="nbrnuitees" placeholder="Exemple : 5" />
                        </div>
                        <div class="field">
                            Nombre d'étape(s) :
                            <input class="input" type="text" name="nbretape" placeholder="Exemple : 13" />
                        </div>
                        <div class="field">
                            Nombre de km :
                            <input class="input" type="text" name="nbrkm" placeholder="Exemple : 600" />
                        </div>
                        <br />
                        <button class="button is-primary buttonRenseignerFiche" name="formajoutforfait">Ajouter !</button>
                    </div>
                </div>
            </center>
        </form>

        <center>
            <div class="block animated fadeInDown slow divNoForfait">
                <form class="box fraisNoForfaitBox" method="post">
                    <div class="field">
                        <h1>Frais hors forfait</h1>
                    </div>
                    <div class="field">
                        Date :
                        <input class="input" type="text" disabled="disabled" name="datehorsforfait" value="<?php echo date('Y-m-d'); ?>" />
                    </div>
                    <div class="field">
                        Libelle :
                        <input class="input" type="text" name="libelle" placeholder="Exemple : J'écris un libelle" />
                    </div>
                    <div class="field">
                        Montant en €:
                        <input class="input" type="text" name="montant" placeholder="Exemple : 50" />
                    </div>
                    <br />
                    <button class="button is-primary buttonRenseignerFiche" name="formajoutnoforfait">Ajouter !</button>
                </form>
            </div>
        </center>
    </body>
</html>