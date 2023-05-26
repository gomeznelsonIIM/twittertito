<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4">Inscription</h1>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe (8 caractères minimum)</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required minlength="8">
            </div>
            <div class="mb-3">
                <label for="mot_de_passe_confirmation" class="form-label">Confirmation du mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" required>
            </div>
            <?php if(isset($erreur)) { ?>
                <div class="alert alert-danger"><?php echo $erreur; ?></div>
            <?php } ?>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>

    <script src="java.js"></script>
</body>
</html>

<?php
// Je me connecte à la base de données :
$pdo = new PDO('mysql:host=localhost;dbname=twitter', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// Si le formulaire a été posté :
if($_POST) {
    // Vérification du mot de passe
    if($_POST['mot_de_passe'] != $_POST['mot_de_passe_confirmation']) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Préparation de la requête d'insertion :
        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");

        //  paramètres :
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
        $photo = $_POST['photo'];
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $prenom);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $mot_de_passe);

        // la requête :
        $stmt->execute();

        // Redirection vers la page connexion :
        header('Location: connexion.php');
        exit;
    }
} else {
    ?>


    <?php
}
?>