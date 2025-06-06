<?php
include("essentiel.php");
include("nav.php");

// On récupère la liste des monstres
try {
    $sql = "
        SELECT
          b.id,
          b.nom           AS nom_monstre,
          b.type          AS type_monstre,
          b.description   AS desc_monstre,
          b.image         AS image_monstre,
          b.created_id        AS created_id, 
          u.nom           AS nom_createur
        FROM bestiaire AS b
        JOIN users AS u
          ON b.created_id = u.id
        ORDER BY b.id DESC
    ";
    $stmt = $bdd->query($sql);
    $allMonstres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des monstres</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="page-ajout-monstre">
    <h2>Tous les monstres</h2>

    <ul class="liste-bestiaires">
      <?php foreach ($allMonstres as $monstre): ?>
        <li class="item-bestiaire">
          <h4>
            <?= htmlspecialchars($monstre['nom_monstre']) ?>
            <small>(Type : <?= htmlspecialchars($monstre['type_monstre']) ?>)</small>
          </h4>

          <?php if (!empty($monstre['desc_monstre'])): ?>
            <p class="description">
              <?= nl2br(htmlspecialchars($monstre['desc_monstre'])) ?>
            </p>
          <?php endif; ?>

          <?php if (!empty($monstre['image_monstre'])): ?>
            <div class="image-container">
              <img
                src="assets/img/<?= htmlspecialchars($monstre['image_monstre']) ?>"
                alt="Image de <?= htmlspecialchars($monstre['nom_monstre']) ?>"
              >
            </div>
          <?php endif; ?>

          <p class="createur">
            Créé par : <strong><?= htmlspecialchars($monstre['nom_createur']) ?></strong>
          </p>

          <?php
          // Afficher "Modifier" uniquement si role_id == 2 Ou si c'est l'eleve qui la crée
          if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $monstre['created_id'])):
          ?>
            <p>
              <a href="modifiermonstre.php?id=<?= $monstre['id'] ?>" class="btn-magique">
                Modifier
              </a>
            </p>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <div>
      <a href="ajoutbestiaire.php" class="btn-magique">
        Ajouter un nouveau monstre
      </a>
    </div>
  </div>
</body>
</html>
