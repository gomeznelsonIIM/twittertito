<?php session_start();




if (isset($_POST['logout'])) {
    // Supprime 'utilisateur' de la session
    unset($_SESSION['utilisateur']);
    header('Location: connexion.php');
    exit;
}
?>


<div class="fixed-bottom text-end m-3">
    <form method="post">
        <button type="submit" name="logout" class="btn btn-danger btn-lg">Se déconnecter</button>
    </form>
</div>