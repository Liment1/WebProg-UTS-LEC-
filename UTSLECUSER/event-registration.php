<?php
// session_start();

// if (!isset($_SESSION["registeredEvents"])) {
//     $_SESSION["registeredEvents"] = [
//         [
//             "id" => 1,
//             "title" => "Summer Music Festival 2024",
//             "image" => "gambar1.jpg",
//             "date" => "2024-07-15",
//             "time" => "16:00",
//             "location" => "Central Park, New York",
//             "ticketNumber" => "SMF2024-001",
//             "status" => "upcoming",
//             "registrationDate" => "2024-03-15",
//             "ticketType" => "VIP Access",
//             "price" => "$150",
//             "description" => "Get ready for the biggest music festival of the summer!",
//             "additionalInfo" => [
//                 "dresscode" => "Casual",
//                 "parking" => "Available",
//                 "requirements" => ["Valid ID", "Ticket confirmation email"]
//             ]
//         ],
//     ];
// }

// $registeredEvents = $_SESSION["registeredEvents"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registered Events</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .event-card {
            transition: all 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .event-image {
            height: 200px;
            object-fit: cover;
        }
        
        .modal-body img {
            max-width: 100%;
            height: auto;
        }
        
        .registration-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .ticket-number {
            font-family: monospace;
            font-size: 1.2em;
            color: #0d6efd;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href=".">EventHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Browse Events</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">My Registrations</a></li>
                <li class="nav-item"><a class="nav-link" href="user-profile.php">My Profile</a></li>
            </ul>
            <span class="navbar-text">Welcome, <span id="userName">User</span>
                <button class="btn btn-outline-light ms-3" onclick="logout()">Logout</button>
            </span>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">My Registered Events</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <select class="form-select" id="statusFilter">
                <option value="all">All Registrations</option>
                <option value="upcoming">Upcoming Events</option>
                <option value="past">Past Events</option>
                <option value="cancelled">Cancelled Events</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search events..." id="searchEvents">
        </div>
    </div>

    <div class="row g-4" id="registeredEvents"></div>
</div>

<div class="modal fade" id="eventDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="cancelRegistrationBtn">Cancel Registration</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
let registeredEvents = <?php echo json_encode($registeredEvents); ?>;

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function displayRegisteredEvents(events = registeredEvents) {
    const container = document.getElementById('registeredEvents');
    container.innerHTML = '';

    events.forEach(event => {
        const card = `
            <div class="col-md-4">
                <div class="card event-card">
                    <img src="${event.image}" class="card-img-top event-image" alt="${event.title}">
                    <div class="status-badge">
                        <span class="badge bg-${event.status === 'upcoming' ? 'success' : 'secondary'}">${event.status}</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${event.title}</h5>
                        <p class="card-text">
                            <i class="fas fa-calendar me-2"></i>${formatDate(event.date)}<br>
                            <i class="fas fa-clock me-2"></i>${event.time}<br>
                            <i class="fas fa-map-marker-alt me-2"></i>${event.location}
                        </p>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="showEventDetails(${event.id})">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}

function showEventDetails(eventId) {
    const event = registeredEvents.find(e => e.id === eventId);
    const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
    const modalBody = document.querySelector('#eventDetailsModal .modal-body');

    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <img src="${event.image}" class="img-fluid rounded" alt="${event.title}">
            </div>
            <div class="col-md-6">
                <h4>${event.title}</h4>
                <p class="text-muted">Registration Date: ${formatDate(event.registrationDate)}</p>
                <div class="registration-details">
                    <h6>Registration Details</h6>
                    <p class="ticket-number">Ticket #: ${event.ticketNumber}</p>
                    <p>Ticket Type: ${event.ticketType}</p>
                    <p>Price: ${event.price}</p>
                </div>
                <div class="mt-3">
                    <h6>Event Information</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-calendar me-2"></i>${formatDate(event.date)}</li>
                        <li><i class="fas fa-clock me-2"></i>${event.time}</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>${event.location}</li>
                    </ul>
                </div>
                <div class="mt-3">
                    <h6>Additional Information</h6>
                    <ul>
                        <li>Dress Code: ${event.additionalInfo.dresscode}</li>
                        <li>Parking: ${event.additionalInfo.parking}</li>
                        <li>Requirements:
                            <ul>
                                ${event.additionalInfo.requirements.map(req => `<li>${req}</li>`).join('')}
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    `;

    document.getElementById('cancelRegistrationBtn').onclick = () => confirmCancelRegistration(eventId);
    modal.show();
}

function confirmCancelRegistration(eventId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this registration!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelRegistration(eventId);
        }
    });
}

function cancelRegistration(eventId) {
    registeredEvents = registeredEvents.filter(event => event.id !== eventId);
    displayRegisteredEvents();
    Swal.fire('Cancelled!', 'Your registration has been cancelled.', 'success');
}

function logout() {
    // Logout logic here
}

document.getElementById('statusFilter').addEventListener('change', (e) => {
    const status = e.target.value;
    const filteredEvents = status === 'all' 
        ? registeredEvents 
        : registeredEvents.filter(event => event.status === status);
    displayRegisteredEvents(filteredEvents);
});

document.getElementById('searchEvents').addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const filteredEvents = registeredEvents.filter(event => 
        event.title.toLowerCase().includes(searchTerm) ||
        event.location.toLowerCase().includes(searchTerm)
    );
    displayRegisteredEvents(filteredEvents);
});

document.addEventListener('DOMContentLoaded', () => {
    displayRegisteredEvents();
});
</script>
</body>
</html>
