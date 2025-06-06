<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Academie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="container">
    <nav>
      <ul class="navbar-main">
        <li><a href="index.php">Accueil</a></li>

        <li class="dropdown">
          <a href="bestiaire.php">Bestiaire&nbsp;&#9662;</a>
          <ul class="dropdown-menu">
            <li><a href="bestiaire.php">Tous les monstres</a></li>
            <li><a href="ajoutbestiaire.php">Ajouter un monstre</a></li>
          </ul>
        </li>

        <?php if (!isset($_SESSION['user_id'])): ?>
          <li><a href="inscription.php">Inscription</a></li>
        <?php endif; ?>
               
        <li><a href="codex.php">Codex</a></li>
                  <!-- // Afficher "Modifier" uniquement si role_id == 2 -->
        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
          <li class="dropdown">
            <a href="panneau_admin.php">Panneau Admin&nbsp;&#9662;</a>
            <ul class="dropdown-menu">
              <li><a href="panneau_admin.php">Liste des utilisateurs</a></li>
              <li><a href="supression_monstre.php">Supprimer monstres</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="navbar-user">
      <ul>
        <li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <form action="deconexion.php" method="post">
              <button type="submit" class="btn-magique">Déconnexion</button>
            </form>
            <p>Bonjour <?= htmlspecialchars($_SESSION['nom'] ?? 'Invité') ?></p>
          <?php else: ?>
            <a href="connexion.php" class="btn-magique">Connexion</a>
            <p>Bonjour Invité</p>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </header>
</body>
</html>

