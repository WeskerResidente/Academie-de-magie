<?php
include("essentiel.php");
include("nav.php");

if (!empty($_POST['nom']) && !empty($_POST['password'])) {

    $nom = htmlspecialchars($_POST['nom']);
    $mdp = sha1($_POST['password']);

    $requestRead = $bdd->prepare('SELECT id, nom, password, role_id FROM users WHERE nom = ?');
    $requestRead->execute([$nom]);
    $data = $requestRead->fetch();

if ($data && $mdp === $data['password']) {
    $_SESSION['user_id']  = $data['id'];
    $_SESSION['nom']      = $data['nom'];
    $_SESSION['role_id']  = $data['role_id']; 
    header('Location: index.php');
    exit;

    } else {
        echo "<p style='color:red;'>Nom ou mot de passe incorrect.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
  <div class="page-connexion">
    <h3>Connexion</h3>
    <form action="connexion.php" method="post">
      <p>Nom</p>
      <input type="text" name="nom" placeholder="Nom" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">

      <p>Mot de passe</p>
      <input type="password" name="password" placeholder="Mot de passe" required>

      <button type="submit">Se connecter</button>
    </form>
  </div>

</body>
</html>
