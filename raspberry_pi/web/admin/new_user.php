//                file new_user.php               
// ===============================================
//          Original Author: fateh kabbani        
// ===============================================

// COMMIT HISTORY:
// ============================================================
// 2025-04-02 - changed folder name removed the space - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-30 - Add user creation functionality: implement form validation, AJAX submission, and backend processing - fateh kabbani
//   raspberry pi/web/admin/new_user.php | 12 ++++++------
//   1 file changed, 6 insertions(+), 6 deletions(-)
//
// 2025-03-30 - Refactor sidebar navigation: extract to a separate component and update links for consistency - fateh kabbani
//   raspberry pi/web/admin/new_user.php | 30 ++----------------------------
//   1 file changed, 2 insertions(+), 28 deletions(-)
//
// 2025-03-29 - Refactor user management: remove admin dashboard, update links, and add new user creation functionality with password generation - fateh kabbani
//   raspberry pi/web/admin/new_user.php | 40 +++++++++++++++++++++++--------------
//   1 file changed, 25 insertions(+), 15 deletions(-)
//
// 2025-03-29 - Refactor admin dashboard and user management: move files to admin directory and update CSS/JS - fateh kabbani
//   1 file changed, 0 insertions(+), 0 deletions(-)
//
// 2025-03-29 - Add user account creation page and styling - fateh kabbani
//   raspberry pi/web/users.php | 94 ++++++++++++++++++++++++++++++++++++++++++++++
//   1 file changed, 94 insertions(+)
//
// ============================================================

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="src/css/dashboard.css">
  <link rel="stylesheet" href="src/css/new_user.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>Création de Compte</title>
</head>

<body>
  <?php include 'components/sidebar.php'; ?>

  <div class="main-content">
    <div class="header">
      <h1>Création de Compte</h1>
    </div>

    <div class="card">
      <div class="card-title">Informations d'identification</div>

      <div id="account-form">
        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role" required>
            <option value="" disabled selected>Sélectionnez un rôle</option>
            <option value="1">Admin</option>
            <option value="0">Opérateur</option>
          </select>
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <div class="password-container">
            <input type="password" id="password" name="password" required>
            <i class="fa-regular fa-eye-slash toggle-password" data-target="password"></i>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirmer le mot de passe</label>
          <div class="password-container">
            <input type="password" id="confirm-password" name="confirm-password" required>
            <i class="fa-regular fa-eye-slash toggle-password" data-target="confirm-password"></i>
          </div>
        </div>

        <div class="button-group">
          <button type="button" class="btn btn-secondary" onclick="window.location.href='users.php'">
            <span>Annuler</span>
          </button>
          <div>
            <button type="button" class="btn btn-third" id="generatePassword">
              <i class="fas fa-key"></i>
              <span>Générer un mot de passe</span>
            </button>
            <button id="new_user" class="btn btn-primary">
              <i class="fas fa-user-plus"></i>
              <span>Créer le compte</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/0e4bc9cea5.js" crossorigin="anonymous"></script>
  <script src="src/js/new_user.js"></script>
</body>

</html>
