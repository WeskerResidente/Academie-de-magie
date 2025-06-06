<?php
include("essentiel.php");
include("nav.php");

if (!empty($_POST['nom']) && !empty($_POST['password'])) {

    $nom      = htmlspecialchars(trim($_POST['nom']));
    $password = sha1($_POST['password']);

    $elements = isset($_POST['element']) ? $_POST['element'] : [];
    if (!empty($elements)) {
        $safe_list      = array_map('htmlspecialchars', $elements);
        $specialisation = implode(',', $safe_list);
    } else {
        $specialisation = null;
    }

    $sql = "INSERT INTO users (nom, password, specialisation, role_id)
            VALUES (:nom, :password, :specialisation, :role_id)";
    $stmt = $bdd->prepare($sql);

    $success = $stmt->execute([
        'nom'           => $nom,
        'password'      => $password,
        'specialisation'=> $specialisation,
        'role_id'       => 3
    ]);

    if ($success) {
        echo "<p style='color:green;'>Inscription réussie !</p>";
    } else {
        echo "<p style='color:red;'>Erreur lors de l'inscription.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscription</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

  <div class="page-inscription">
    <h2>Inscription</h2>

    <form action="inscription.php" method="post">
      <p>Nom</p>
      <input type="text" name="nom" placeholder="Nom" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">

      <p>Mot de passe</p>
      <input type="password" name="password" placeholder="Mot de passe" required>

      <div class="filters">
        <label>
          <input type="checkbox" value="6" name="element[]"/> Feu
        </label>
        <label>
          <input type="checkbox" value="8" name="element[]"/> Lumière
        </label>
        <label>
          <input type="checkbox" value="5" name="element[]"/> Eau
        </label>
        <label>
          <input type="checkbox" value="7" name="element[]"/> Air
        </label>
      </div>

      <button type="submit" name="submit" class="btn-enregistrer">Enregistrer</button>
    </form>
  </div>
</body>
</html>

</html>
