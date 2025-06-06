<?php
include("essentiel.php");
include("nav.php");

// Récupérer la liste des éléments depuis la base
try {
    $elementsStmt = $bdd->query("SELECT id, type FROM elements ORDER BY type");
    $allElements  = $elementsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erreur SQL (éléments) : " . $e->getMessage());
}

// Initialisation des messages
$messageSuccess = '';
$messageError   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom      = trim($_POST['nom'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $choix    = $_POST['element'] ?? []; 

    // Vérification des champs obligatoires
    if ($nom === '' || $password === '') {
        $messageError = "Les champs « Nom » et « Mot de passe » sont obligatoires.";
    } else {
        $hashedPwd = sha1($password);

        $sqlInsertUser = "
            INSERT INTO users (nom, password, role_id)
            VALUES (:nom, :password, :role_id)
        ";
        $stmtUser = $bdd->prepare($sqlInsertUser);

        try {
            // Démarrer une transaction
            $bdd->beginTransaction();

            // Insérer l’utilisateur
            $stmtUser->execute([
                ':nom'      => htmlspecialchars($nom, ENT_QUOTES),
                ':password' => $hashedPwd,
                ':role_id'  => 3
            ]);

            // Récupérer l’ID du nouvel utilisateur
            $newUserId = (int) $bdd->lastInsertId();

            //Si des éléments ont été cochés, les insérer dans user_elements
            if (!empty($choix)) {
                $sqlInsertUE = "
                    INSERT INTO user_elements (user_id, element_id)
                    VALUES (:uid, :eid)
                ";
                $stmtUE = $bdd->prepare($sqlInsertUE);

                foreach ($choix as $elementId) {
                    $eid = (int) $elementId;
                    $valid = false;
                    foreach ($allElements as $e) {
                        if ((int)$e['id'] === $eid) {
                            $valid = true;
                            break;
                        }
                    }
                    if ($valid) {
                        $stmtUE->execute([
                            ':uid' => $newUserId,
                            ':eid' => $eid
                        ]);
                    }
                }
            }
            $bdd->commit();
            $messageSuccess = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } catch (\PDOException $e) {
            $bdd->rollBack();
            $messageError = "Erreur lors de l’inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="page-inscription">
    <h2>Inscription</h2>


    <?php if ($messageError): ?>
      <div class="message erreur"><?= htmlspecialchars($messageError, ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if ($messageSuccess): ?>
      <div class="message succes"><?= htmlspecialchars($messageSuccess, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form action="inscription.php" method="post">

      <div class="form-group">
        <label for="nom">Nom :</label><br>
        <input
          type="text"
          id="nom"
          name="nom"
          placeholder="Entrez votre nom"
          required
          value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom'], ENT_QUOTES) : '' ?>"
        >
      </div>

      <div class="form-group">
        <label for="password">Mot de passe :</label><br>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Entrez un mot de passe"
          required
        >
      </div>

      <div class="form-group">
        <label>Spécialisation (éléments) :</label><br>
        <div class="filters">
          <?php foreach ($allElements as $elem): ?>
            <?php

              $checked = '';
              if (!empty($_POST['element']) && in_array($elem['id'], $_POST['element'], true)) {
                  $checked = 'checked';
              }
            ?>
            <label>
              <input
                type="checkbox"
                name="element[]"
                value="<?= (int)$elem['id'] ?>"
                <?= $checked ?>
              >
              <?= htmlspecialchars($elem['type'], ENT_QUOTES) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn-enregistrer">Enregistrer</button>
      </div>
    </form>
  </div>
</body>
</html>
