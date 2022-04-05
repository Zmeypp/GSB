<?php
session_start();
include('./Connection_BDD.php');
$conn = getBdd('localhost', 'root', 'sio2021');
$erreur = "";

if(isset($_POST['formconnexion'])) {
    $userconnect= htmlspecialchars($_POST['userconnect']);
    $mdpconnect = $_POST['mdpconnect'];

    if(!empty($userconnect) AND !empty($mdpconnect) AND isset($_POST["captcha"])&&$_POST["captcha"]!=""&&$_SESSION["code"]==$_POST["captcha"]) {
        $requser = $conn->prepare("select * from gsb_frais.utilisateur where login = ? and mdp = ?");
        $requser->execute(array($userconnect, md5($mdpconnect)));
        $userexist = $requser->rowCount();
        if($userexist == 1) {
            $userinfo = $requser->fetch();
            header("Location: profil.php");
        } else {
            $erreur = "<br /><br /> <div class='notification is-danger'>Mauvais nom d'utilisateur ou mot de passe ! </div>";
        }
    } else {
        $erreur = "<br /><br /> <div class='notification is-danger'>Tous les champs doivent être complétés ! </div>";
    }

    $reponse = $conn->query( 'SELECT idr from utilisateur WHERE login="'.$_POST['userconnect'].'" AND mdp="'.md5($_POST['mdpconnect']).'"');
    $data = $reponse->fetch();
    $_SESSION['idr'] = $data['idr'];

    $reponse2 = $conn->query( 'SELECT nom, prenom from utilisateur WHERE login="'.$_POST['userconnect'].'" AND mdp="'.md5($_POST['mdpconnect']).'"');
    $data2 = $reponse2->fetch();
    $_SESSION['nom'] = $data2['nom'];
    $_SESSION['prenom'] = $data2['prenom'];

    $reponse3 = $conn->query( 'SELECT NomRole from role INNER JOIN utilisateur ON role.idr=utilisateur.idr WHERE login="'.$_POST['userconnect'].'" AND mdp="'.md5($_POST['mdpconnect']).'"');
    $data3 = $reponse3->fetch();
    $_SESSION['NomRole'] = $data3['NomRole'];

    $reponse4 = $conn->query( 'SELECT id from utilisateur WHERE login="'.$_POST['userconnect'].'" AND mdp="'.md5($_POST['mdpconnect']).'"');
    $data4 = $reponse4->fetch();
    $_SESSION['id'] = $data4['id'];

    $reponse5 = $conn->query('SELECT adresse, cp, ville from utilisateur WHERE login="'.$_POST['userconnect'].'"AND mdp="'.md5($_POST['mdpconnect']).'"');
    $data5 = $reponse5->fetch();
    $_SESSION['adresse'] = $data5['adresse'];
    $_SESSION['cp'] = $data5['cp'];
    $_SESSION['ville'] = $data5['ville'];


}

?>

<html>
    <head>
        <title>Page de connexion</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    </head>

    <body>
    <center>
        <div class="block animated fadeInDown slow decadeDiv">
            <form class="box connexionBox" method="post">
                <div class="field">
                    <img src="images/bonsoir.png" class="imgGSB" />
                </div>
                <div class="field">
                    <input class="input" type="text" name="userconnect" placeholder="Nom d'utilisateur">
                </div>
                <br />
                <div class="field" style="position: relative; display: flex;">
                    <input class="input" type="password" id="password" name="mdpconnect" placeholder="Mot de passe">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                </div>
                <div class="field">
                    <img src="captcha.php" class="imgCaptcha"/>
                    <input class="input" name="captcha" type="text" placeholder="Réponse au captcha">
                </div>

                <button class="button is-primary buttonConnexion" id="submit" name="formconnexion">Me connecter</button>
                <?php
                    echo $erreur;
                ?>
            </form>
        </div>
    </center>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
            const togglePassword = document.querySelector("#togglePassword");
            const password = document.querySelector("#password");

            togglePassword.addEventListener("click", function () {
                // toggle the type attribute
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);

                // toggle the icon
                this.classList.toggle("bi-eye");
            });

            // prevent form submit
            const form = document.querySelector("form");
            form.addEventListener('submit', function (e) {
                e.preventDefault();
            });
    </script>
    </body>
</html>
