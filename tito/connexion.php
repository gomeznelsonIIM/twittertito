<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <title>Connexion</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4">Connexion</h1>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <?php
                $pdo = new PDO('mysql:host=localhost;dbname=twitter', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            
                // Vérifier si le formulaire a été soumis
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Récupérer les données de l'utilisateur
                    $email = $_POST['email'];
                    $mot_de_passe = $_POST['mot_de_passe'];
            
                    // Préparer et exécuter la requête
                    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch();
            
                    // Valider les identifiants de connexion
                    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                        // Connexion réussie
                        // Démarrer une session et stocker l'ID de l'utilisateur
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
            
                        // Rediriger vers la page d'accueil ou toute autre page souhaitée
                        header('Location: acceuil.php');
                        exit;
                    } else {
                        // Identifiants de connexion invalides
                        echo '<div class="alert alert-danger">Identifiants invalides. Veuillez réessayer.</div>';
                    }
                }
            ?>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>

    <script src="java.js"></script>
</body>
</html>
