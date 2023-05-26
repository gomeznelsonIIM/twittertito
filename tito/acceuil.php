<?php
    session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter</title>
    <link rel="stylesheet" href="acceuil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="sidebar">
        <a href="#">Twitter</a>
        <a href="profil.php">Profil</a>
        
    </div>
    <div class="container text-center">
        <div class="search-form ms-auto">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Rechercher un message">
                <button type="submit">Rechercher</button>
            </form>
        </div>
        
<!-- Affichage du statut de connexion -->
<div class="login-status <?php echo (isset($_SESSION['utilisateur']) ? 'connecter en tant que' : 'disconnecter'); ?>">
            <?php echo (isset($_SESSION['utilisateur']) ? 'Connected as '.$_SESSION['utilisateur'] : 'Not connected'); ?>
            </div>

        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=twitter', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

        // Récupération des données de la base de données
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Requête pour récupérer les messages filtrés
        $query = "SELECT id, pseudo, msg, date_heure_message FROM message";
        if (!empty($search)) {
            $query .= " WHERE msg LIKE :search";
        }
        $query .= " ORDER BY date_heure_message DESC";
        $statement = $pdo->prepare($query);
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $statement->bindParam(':search', $searchParam);
        }
        $statement->execute();
        $donnees = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Affichage la liste -->
        <ul class="message-list">
            <?php foreach ($donnees as $row) { ?>
                <li class="post">
                    <strong>Pseudo:</strong> <?php echo $row['pseudo']; ?><br>
                    <strong>Message:</strong> <?php echo $row['msg']; ?><br>
                    <strong>Date et heure:</strong> <?php echo $row['date_heure_message']; ?><br>
                </li>
            <?php } ?>
        </ul>
    </div>
    </header>
    <!-- poster-->
    <div class="mt-auto">
        <button id="connect_btn" class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#connectModal">Poster</button>
    </div>

    <!-- Ouverture popup de post -->
    <div class="modal fade" id="connectModal" tabindex="-1" aria-labelledby="connectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="connectModalLabel">Twitter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="confirmCloseModal(event)"></button>
                </div>
                <div class="modal-body">
                    <form id="postForm" method="post" enctype="multipart/form-data">
                        <input type="text" name="pseudo" placeholder="Pseudo" required>
                        <textarea name="message" placeholder="Message (280 characters maximum)" required maxlength="280"></textarea>
                        <select name="tag">
                            <option disabled selected>Sélectionner un tag</option>
                            <option value="Nature">Nature</option>
                            <option value="Mode">Mode</option>
                            <option value="Voyage">voyage</option>
                            <option value="Beauté">Beauté</option>
                            <option value="Sport">Sport</option>
                            <option value="Technologie">Technologie</option>
                            <option value="Musique">Musique</option>
                            <option value="Politique">Politique</option>
                            <option value="Politique">Olympiques</option>
                            <option value="Politique">Jeux</option>
                        </select>
                        <button id="connect_btn_modal" type="submit">Poster</button>
                        <input type="file" name="post_image" accept="image/png, image/jpeg, image/gif" required>
                        <!--  image upload -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des tags en haut de page-->
    <div class="tags">
        <span class="tag" data-tag="all">All</span>
        <span class="tag" data-tag="Nature">Nature</span>
        <span class="tag" data-tag="Mode">Mode</span>
        <span class="tag" data-tag="Jeux">Jeux</span>
        <span class="tag" data-tag="Voyage">Voyage</span>
        <span class="tag" data-tag="Beauté">Beauté</span>
        <span class="tag" data-tag="Sport">Sport</span>
        <span class="tag" data-tag="Technologie">Technologie</span>
        <span class="tag" data-tag="Musique">Musique</span>
        <span class="tag" data-tag="Olympiques">Olympiques</span>
        <span class="tag" data-tag="Politique">Politique</span>
    </div>

    <?php
    // Si le formulaire a été posté
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si le formulaire de suppression a été soumis
        if (isset($_POST['delete_message'])) {
            // Récupérer l'identifiant du message à supprimer
            $messageId = $_POST['delete_message'];

            // Préparer la requête de suppression
            $statement = $pdo->prepare("DELETE FROM message WHERE id = ?");
            $statement->bindParam(1, $messageId);

            // Exéc la requête de suppression
            $statement->execute();
        } else {
            // Préparation de la requête d'insertion
            $statement = $pdo->prepare("INSERT INTO message (pseudo, msg, tag, date_heure_message) VALUES (?, ?, ?, NOW())");

            //  paramètres
            $statement->bindParam(1, $_POST['pseudo']);
            $statement->bindParam(2, $_POST['message']);
            $statement->bindParam(3, $_POST['tag']);

            // Exécution de la requête d'insertion
            $statement->execute();
        }
    }

    // Récupération des données de la base de données
    $requete = $pdo->query("SELECT id, pseudo, msg, date_heure_message FROM message ORDER BY date_heure_message DESC");
    $donnees = $requete->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Affichage des données dans une liste -->
    <div class="container">
        <ul>
            <?php foreach ($donnees as $row) { ?>
                <li>
                    <strong>Pseudo:</strong> <?php echo $row['pseudo']; ?><br>
                    <strong>Message:</strong> <?php echo $row['msg']; ?><br>
                    <strong>Date et heure:</strong> <?php echo $row['date_heure_message']; ?><br>
                    <!-- Formulaire de suppression -->
                    <form method="post" action="">
                        <input type="hidden" name="delete_message" value="<?php echo $row['id']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="tag.js"></script>
    <script>
        $(window).on('load', function () {
            $('.tag[data-tag="all"]').click();
        });
    </script>
</body>

</html>
