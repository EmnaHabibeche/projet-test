$(document).ready(function() {
    function loadClients() {
        $.ajax({
            url: 'controllers/getClient-controller.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Raw Response:', response); // Debugging line
                if (Array.isArray(response)) {
                    console.log('Parsed Clients Data:', response); // Debugging line
                    var clientList = $('#clientList');
                    clientList.empty();
            
                    response.forEach(function(client) {
                        console.log('Client object:', client);

                        console.log('Image URL:', client.photo); 
                        var clientCard = `
                        <div id="clientContainer">
                            <div class="client-card">
                                <div class="d-flex align-items-center p-3">
                                    <img src="${client.photo}" alt="${client.prenom} ${client.nom}" class="client-img">
                                    <div class="client-info ms-3 flex-grow-1">
                                        <h5 class="client-name mb-1">${client.prenom} ${client.nom}</h5>
                                        <a href="tel:${client.numtel}" class="client-phone">${client.numtel}</a>
                                    </div>
                                    <div class="client-actions">
                                        <i class="fa-regular fa-pen-to-square editClientIcon" data-id="${client.id}"></i>
                                        <i class="fa-regular fa-trash-can deleteClientIcon" data-id="${client.id}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                        clientList.append(clientCard);
                    });
                } else {
                    console.error('Expected an array but got:', response);
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Response Text:', xhr.responseText);
                console.log('Status Code:', xhr.status);
                console.log('Status Text:', xhr.statusText);
                console.error('AJAX Error:', status, error);
            }
        });
    }

    loadClients();
// Handle form submission
$('#addClientForm').on('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var clientId = $('#addClientForm').attr('data-id');
    var url = clientId ? 'controllers/updateClient-controller.php' : 'controllers/addClient-controller.php';
    
    if (clientId) {
        formData.append('id', clientId);
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            try {
                var res = JSON.parse(response);
                var notification = $('#notification');
                if (res.status === 'success') {
                    notification.removeClass('alert-danger').addClass('alert-success').text(res.message).show();
                    $('#addClientForm')[0].reset();
                    $('#addClientForm').removeAttr('data-id');
                    $('.ajouter').text('Ajouter');
                    $('#dragDropZone').text('Glissez-déposez ou cliquez pour télécharger');
                    loadClients();
                } else {
                    notification.removeClass('alert-success').addClass('alert-danger').text(res.message).show();
                }
                setTimeout(function() {
                    notification.fadeOut();
                }, 5000);
            } catch (e) {
                console.error('Error parsing JSON response:', e);
                console.log('Response:', response);
                $('#notification').removeClass('alert-success').addClass('alert-danger').text("Erreur lors de l'opération.").show();
setTimeout(function() {
$('#notification').fadeOut();
}, 5000); 
            }
        }
    });
    loadClients();
});

    loadClients();

    $(document).ready(function() {
        // Confirm Deletion
        $('#confirmDeleteBtn').on('click', function() {
            var id = $(this).data('id');
            console.log("Confirm delete button clicked");
            console.log("Confirming delete for client ID:", id);
            
            var confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            
            $.ajax({
                url: 'controllers/deleteClient-controller.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    console.log("Raw response:", response); // Debugging
                    try {
                        var res = JSON.parse(response);
                        console.log("Parsed response:", res); // Debugging
                        if (res.status === 'success') {
                            // Hide the modal before showing the notification
                            confirmDeleteModal.hide();
    
                            // Delay the notification to ensure modal hides first
                            setTimeout(function() {
                                showNotification(res.message, true);
                                loadClients(); // Reload the client list
                            }, 300);
                        } else {
                            showNotification(res.message, false);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        console.log('Response:', response);
                        showNotification('Erreur lors de la suppression du client.', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response Text:', xhr.responseText); 
                    console.log('Status Code:', xhr.status);
                    showNotification('Erreur lors de la suppression du client.', false);
                }
            });
        });
    
        // Show the modal when the delete icon is clicked
        $(document).on('click', '.deleteClientIcon', function() {
            var id = $(this).data('id');
            console.log('Delete icon clicked. Client ID:', id);
            $('#confirmDeleteModal').modal('show');
            $('#confirmDeleteBtn').data('id', id);
        });
    });
    
    

    $(document).on('click', '.editClientIcon', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'controllers/getClient-controller.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(client) {
                $('#prenom').val(client.prenom);
                $('#nom').val(client.nom);
                $('#numtel').val(client.numtel);
                $('#pays').val(client.pays);
                $('#addClientForm').attr('data-id', client.id);
                $('.ajouter').text('Mettre à jour');
                $('.nav-link[href="#addClient"]').tab('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#errorMessage').text('Erreur lors de la récupération des données du client.');
                $('#errorModal').modal('show');
            }
        });
    
        loadClients();
    });
});
function showNotification(message, isSuccess) {
    var notificationElement = $('#notification');
    notificationElement.text(message);
    notificationElement.removeClass('alert-success alert-danger');
    notificationElement.addClass(isSuccess ? 'alert-success' : 'alert-danger');
    notificationElement.show();
    setTimeout(function() {
        notificationElement.fadeOut();
    }, 5000);
}

