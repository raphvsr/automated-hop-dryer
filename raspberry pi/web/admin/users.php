<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: ../../login.php');
  exit();
}
if ($_SESSION['admin'] != 1) {
  header('Location: ../../index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Utilisateurs</title>
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link rel="stylesheet" href="src/css/users.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
  <nav class="sidebar-navigation">
    <ul>
      <li>
        <a href="dashboard.php">
          <i class="fa fa-home"></i>
          <span class="tooltip">Accueil</span>
        </a>
      </li>
      <li>
        <a href="csv.php">
          <i class="fa fa-file-o"></i>
          <span class="tooltip">Csv</span>
        </a>
      </li>
      <li class="active">
        <a href="users.php">
          <i class="fa fa-user-o"></i>
          <span class="tooltip">Utilisateur</span>
        </a>
      </li>
      <li>
        <a href="settings.php">
          <i class="fa fa-sliders"></i>
          <span class="tooltip">Paramètres</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="users-container">
    <h1>Gestion des Utilisateurs</h1>

    <div class="users-card">
      <div class="card-header">
        <h2 class="section-title">Liste des Utilisateurs</h2>
        <a href="new_user.php">
          <button class="btn btn-add">
            <i class="fas fa-plus"></i> Ajouter un utilisateur
          </button>
        </a>
      </div>

      <div class="table-container">
        <table class="users-table">
          <thead>
            <tr>
              <th>Nom d'utilisateur</th>
              <th>Rôle</th>
              <th>Date de création</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once '../backend/database.php';

            $sql = "SELECT * FROM users ORDER BY created_at DESC";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
              $role = $row['role'] == 1 ? 'Administrateur' : 'Operateur';
              $created_at = date('d/m/Y', strtotime($row['created_at']));
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td>
                  <span class="role-badge <?php echo $row['role'] == 1 ? 'admin' : 'Opt'; ?>"><?php echo $role; ?></span>
                </td>
                <td><?php echo $created_at; ?></td>
                <td class="actions">
                  <button class="btn btn-edit" onclick="editUser(<?php echo $row['id']; ?>)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-delete" onclick="deleteUser(<?php echo $row['id']; ?>)">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="userModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Ajouter un utilisateur</h2>
      <form id="userForm">
        <input type="hidden" id="userId" name="userId">
        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password">
          <small>(Laisser vide pour conserver l'ancien mot de passe)</small>
        </div>
        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role">
            <option value="0">Utilisateur</option>
            <option value="1">Administrateur</option>
          </select>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-save">Enregistrer</button>
          <button type="button" class="btn btn-cancel" onclick="closeModal()">Annuler</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../js/users.js"></script>
  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
</body>

</html>
