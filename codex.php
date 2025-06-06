<?php
include("essentiel.php");
include("nav.php");

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

try {
    $sql = "
        SELECT
          c.id                  AS codex_id,
          c.nom                 AS nom_sort,
          c.image               AS image_sort,
          e.type                AS element_type,
          GROUP_CONCAT(u.nom SEPARATOR ', ') AS users_with_element
        FROM codex AS c
        JOIN elements AS e
          ON c.element_id = e.id
        LEFT JOIN user_elements AS ue
          ON ue.element_id = c.element_id
        LEFT JOIN users AS u
          ON u.id = ue.user_id
        GROUP BY c.id, c.nom, c.image, e.type
        ORDER BY c.id DESC
    ";

    $stmt = $bdd->query($sql);
    $allSorts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Codex des Sorts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="page-codex">
    <h3>Codex des Sorts</h3>

    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
      <div class="action-add">
        <a href="ajoutcodex.php" class="btn-magique">Ajouter un nouveau sort</a>
      </div>
    <?php endif; ?>

    <?php if (empty($allSorts)): ?>
      <p>Aucun sort enregistré pour l’instant.</p>
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
        <p>
          <a href="ajoutcodex.php" class="btn-magique">Ajouter un premier sort</a>
        </p>
      <?php endif; ?>
    <?php else: ?>
      <ul class="liste-codex">
        <?php foreach ($allSorts as $sort): ?>
          <li class="item-codex">
            <h4>
              <?= e($sort['nom_sort']) ?>
              <small>(Élément : <?= e($sort['element_type']) ?>)</small>
            </h4>

            <?php if (!empty($sort['image_sort'])): ?>
              <div class="image-sort">
                <img
                  src="assets/img/<?= e($sort['image_sort']) ?>"
                  alt="Image du sort <?= e($sort['nom_sort']) ?>"
                >
              </div>
            <?php endif; ?>

            <p>
              <strong>Utilisateurs possédant l’élément « <?= e($sort['element_type']) ?> » :</strong><br>
              <?php
                if (empty($sort['users_with_element'])) {
                  echo "<em>Aucun utilisateur</em>";
                } else {
                  echo e($sort['users_with_element']);
                }
              ?>
            </p>

            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
              <p class="action-modifier">
                <a href="modifierCodex.php?id=<?= (int)$sort['codex_id'] ?>" class="btn-magique">Modifier</a>
              </p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
      <div class="action-add">
        <a href="ajoutcodex.php" class="btn-magique">Ajouter un nouveau sort</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
