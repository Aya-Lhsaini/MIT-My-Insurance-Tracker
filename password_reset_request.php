<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du Mot de Passe</title>
    <style>

* {
  font-family: "Arial", sans-serif;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
  background: #dde5f4;
}

.container {
  background: #ffffff;
  padding: 2em;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 400px;
  text-align: center;
}

h2 {
  margin-bottom: 1em;
  color: #333;
}

.form-group {
  margin-bottom: 1em;
  text-align: left;
}

.form-group label {
  display: block;
  margin-bottom: 0.5em;
  color: #555;
}

.form-group input {
  width: 100%;
  padding: 0.5em;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

.submit-btn {
  padding: 0.7em;
  background: #253297;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  width: 100%;
  font-size: 1em;
}

.submit-btn:hover {
  background: #1a2a78;
}


    </style>
</head>
<body>
    <div class="container">
        <h2>Récuperation du Mot de Passe</h2>
        <form action="reset_password.php" method="post">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="matricule">Matricule :</label>
                <input type="text" id="matricule" name="matricule" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="submit-btn">Récuperer le Mot de Passe</button>
        </form>
    </div>
</body>
</html>

