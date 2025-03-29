<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link rel="stylesheet" href="src/css/users.css">

  <title>Document</title>
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
      <li class="active">
        <a href="csv.php">
          <i class="fa fa-file-o"></i>
          <span class="tooltip">Csv</span>
        </a>
      </li>
      <li>
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
  <div class="main-content">
    <div class="header">
      <h1>Création de Compte</h1>
    </div>


    <div class="card">
      <div class="card-title">Informations d'identification</div>

      <form id="account-form">
        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role" required>
            <option value="" disabled selected>Sélectionnez un rôle</option>
            <option value="admin">Admin</option>
            <option value="operateur">Opérateur</option>
          </select>
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirmer le mot de passe</label>
          <input type="password" id="confirm-password" name="confirm-password" required>
        </div>

        <div class="button-group">
          <button type="button" class="btn btn-secondary">
            <span>Annuler</span>
          </button>
          <button type="submit" class="btn btn-primary">
            <span>Créer le compte</span>
          </button>
        </div>
      </form>
    </div>


  </div>

  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>

</body>

</html>
