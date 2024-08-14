$(document).ready(function() {
    // Function to load and display clients in the table
    function loadClients() {
        $.ajax({
            url: 'controllers/getClient-controller.php',
            type: 'GET',
            success: function(response) {
                try {
                    var clients = JSON.parse(response);
                    var clientList = $('#clientList');
                    clientList.empty();
    
                    clients.forEach(function(client) {
                        var clientCard = `
                        <div id="clientContainer">
                            <div class="client-card">
                                <div class="d-flex align-items-center p-3">
                                    <img src="resources/${client.photo}" alt="${client.prenom} ${client.nom}" class="client-img">
                                    <div class="client-info ms-3 flex-grow-1">
                                        <h5 class="client-name mb-1">${client.prenom} ${client.nom}</h5>
                                        <a href="tel:${client.numtel}" class="client-phone">${client.numtel}</a>
                                    </div>
                                     <div class="client-actions">
                                <i class="fa-regular fa-pen-to-square editClientIcon" data-id="${client.id}"></i>
                                <i class="fa-regular fa-trash-can deleteClientIcon" data-id="${client.id}"></i>
                            </div>
                                </div>
                            </div> </div>
                `;
                        clientList.append(clientCard);
                    });
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    console.log('Response:', response);
                }
            }
        });
    }
    
    
    // Initially load all clients when the page loads
    loadClients();

    // Add Client
    $('#addClientForm').on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'controllers/addClient-controller.php', // Send data to the controller
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
                        $('#addClientForm')[0].reset(); // Reset form after success
                        $('#dragDropZone').text('Glissez-déposez ou cliquez pour télécharger'); // Reset the drag-drop zone text
                        loadClients(); // Reload the client list
                    } else {
                        notification.removeClass('alert-success').addClass('alert-danger').text(res.message).show();
                    }
                    setTimeout(function() {
                        notification.fadeOut();
                    }, 5000); // Hide notification after 5 seconds
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    console.log('Response:', response);
                    $('#notification').removeClass('alert-success').addClass('alert-danger').text('Erreur lors de l’ajout du client.').show();
                    setTimeout(function() {
                        $('#notification').fadeOut();
                    }, 5000); // Hide notification after 5 seconds
                }
            }
        });
    });

    // Delete Client
    $(document).on('click', '.deleteClientBtn', function() {
        var id = $(this).data('id');

        $.ajax({
            url: 'controllers/deleteClient-controller.php', // Send the ID to the controller
            type: 'POST',
            data: {id: id},
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    var notification = $('#notification');
                    if (res.status === 'success') {
                        notification.removeClass('alert-danger').addClass('alert-success').text(res.message).show();
                        loadClients(); // Reload the client list
                    } else {
                        notification.removeClass('alert-success').addClass('alert-danger').text(res.message).show();
                    }
                    setTimeout(function() {
                        notification.fadeOut();
                    }, 5000); // Hide notification after 5 seconds
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    console.log('Response:', response);
                    $('#notification').removeClass('alert-success').addClass('alert-danger').text('Erreur lors de la suppression du client.').show();
                    setTimeout(function() {
                        $('#notification').fadeOut();
                    }, 5000); // Hide notification after 5 seconds
                }
            }
        });
    });

    // Edit Client
    $(document).on('click', '.editClientBtn', function() {
        var id = $(this).data('id');

        $.ajax({
            url: 'controllers/getClient-controller.php', // Get client data
            type: 'POST',
            data: {id: id},
            success: function(response) {
                // You can use this response to populate an edit form
                console.log(response);
            }
        });
    });
});
