<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Client</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Client</h2>
        <form id="addClientForm">
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="numtel">Num Tel:</label>
                <input type="text" class="form-control" id="numtel" name="numtel" required>
            </div>
            <div class="form-group">
                <label for="pays">Pays:</label>
                <input type="text" class="form-control" id="pays" name="pays" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" class="form-control" id="photo" name="photo" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
        <div id="notification"></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../ajax.js"></script>
</body>
</html>
