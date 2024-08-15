<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Client</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    

    <link rel="stylesheet" href="resources/graphics/style.css">

    <style>
      
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <div class="navbar-collapse">
            <ul class="nav nav-tabs custom-nav-tabs">
                <li class="nav-item" style="    border-bottom: none !important;">
                    <a class="nav-link active" href="#contacts" data-toggle="tab">CONTACTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#addClient" data-toggle="tab">AJOUTER UN CLIENT</a>
                </li>
            </ul>
            <span class="navbar-brand ml-auto">
                <img src="resources/graphics/logo.png" alt="Logo" style="height: 60px; width: auto; margin-left: 20px; float: right;">
            </span>
        </div>
    </div>
</nav>

<div class="container mt-5 position-relative">
    <div class="notification">
        <p>Notification: Client ajouté avec succès</p>
    </div>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="contacts">
            <!-- List of clients will be loaded here -->
            <div id="clientList"></div>
        </div>
        <div class="tab-pane fade" id="addClient">
            <div class="card">
                <div class="card-header">
                    <h3>Ajouter un Client</h3>
                </div>
                <div class="card-body">
                    <form id="addClientForm" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrer le prénom" required>
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrer le nom" required>
                        </div>
                        <div class="form-group">
                            <label for="numtel">Num Tel</label>
                            <input type="tel" class="form-control" id="numtel" name="numtel" placeholder="Entrer le numéro de téléphone" required pattern="[0-9]+" title="Please enter a valid phone number">
                            </div>
                        <div class="form-group">
                            <label for="pays">Pays</label>
                            <select class="form-control" id="pays" name="pays" required>
                                <option value="">Sélectionnez un pays</option>
                                <option value="FR">France</option>
                                <option value="US">États-Unis</option>
                                <option value="CA">Canada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <div class="drag-drop-zone" id="dragDropZone">
                                Glissez-déposez ou cliquez pour télécharger
                            </div>
                            <input type="file" name="photo">                        </div>
                        <div class="form-group text-right">
                            <button type="button" class="btn annuler" id="cancelButton">Annuler</button>
                            <button type="submit" class="btn ajouter">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Success Modal -->
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Client created successfully!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Erreur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- AJAX Script -->
<script src="ajax.js"></script>

<script>
  $(document).ready(function() {
    // Drag and drop functionality
    $('#dragDropZone').on('click', function() {
        $('#photo').click();
    });

    $('#photo').on('change', function(event) {
        var fileName = event.target.files[0].name;
        $('#dragDropZone').text(fileName);
    });

    // Prevent default behavior for dragover and drop events
    $('#dragDropZone').on('dragover', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).addClass('dragover');
    });

    $('#dragDropZone').on('dragleave', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).removeClass('dragover');
    });

    $('#dragDropZone').on('drop', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).removeClass('dragover');

        var files = event.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            var file = files[0];
            $('#photo')[0].files = files; // Assign the dropped file to the file input
            $('#dragDropZone').text(file.name); // Display the file name in the drag-drop zone
        }
    });

    // Cancel button functionality
    $('#cancelButton').on('click', function() {
        $('#addClientForm')[0].reset();
        $('#dragDropZone').text('Glissez-déposez ou cliquez pour télécharger');
    });
});

</script>

</body>

</html>