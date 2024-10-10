<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Browser</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .event-card {
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
            height: 100%;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .date-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            line-height: 1;
        }

        .date-badge .month {
            font-size: 14px;
            font-weight: bold;
            color: #dc3545;
        }

        .date-badge .day {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .modal-body img {
            max-height: 300px;
            width: 100%;
            object-fit: cover;
        }

        .featured-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1;
        }

        .search-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .event-details-list {
            list-style: none;
            padding: 0;
        }

        .event-details-list li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .event-details-list li i {
            position: absolute;
            left: 0;
            top: 4px;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'user') {
        header("Location: login.php");  
        exit();
    } 

    ?>
   
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EventHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Browse Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="event-registration.html">My Registrations</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="user-profile.html">My Profile</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <span id="userName">User</span>
                    <button class="btn btn-outline-light ms-3" onclick="logout('index.html')">Logout</button>
                </span>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="search-container mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search events...">
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="music">Music</option>
                        <option value="tech">Technology</option>
                        <option value="sport">Sports</option>
                        <option value="art">Arts & Culture</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <select class="form-select" id="dateFilter">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="row g-4" id="eventsContainer">
        
        </div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="registerButton">Register</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

const events = [
    {
        id: 1,
        title: "Summer Music Festival 2024",
        image: "https://via.placeholder.com/400x300",
        category: "music",
        date: "2024-07-15",
        time: "16:00",
        location: "Central Park, New York",
        price: "$50",
        description: "Join us for the biggest music festival of the summer featuring top artists from around the world!",
        featured: true,
        schedule: [
            "4:00 PM - Opening Act",
            "5:30 PM - Local Bands Showcase",
            "7:00 PM - Main Performance",
            "9:00 PM - Headline Act",
            "11:00 PM - Closing Ceremony"
        ],
        amenities: ["Food Courts", "Parking", "VIP Areas", "First Aid"],
        organizer: "EventPro Productions"
    },
    {
        id: 2,
        title: "Tech Innovation Summit",
        image: "https://via.placeholder.com/400x300",
        category: "tech",
        date: "2024-08-20",
        time: "09:00",
        location: "Convention Center, San Francisco",
        price: "$299",
        description: "Discover the latest technological innovations and network with industry leaders.",
        featured: false,
        schedule: [
            "9:00 AM - Registration",
            "10:00 AM - Keynote Speech",
            "11:30 AM - Panel Discussion",
            "1:00 PM - Networking Lunch",
            "2:30 PM - Workshops"
        ],
        amenities: ["Wi-Fi", "Lunch Included", "Conference Materials"],
        organizer: "TechCon Events"
    },
   
];

function formatDate(dateString) {
    const date = new Date(dateString);
    const month = date.toLocaleString('default', { month: 'short' });
    const day = date.getDate();
    return { month, day };
}

function createEventCards() {
    const container = document.getElementById('eventsContainer');
    container.innerHTML = '';

    events.forEach(event => {
        const formattedDate = formatDate(event.date);
        const card = `
            <div class="col-md-4" onclick="showEventDetails(${event.id})">
                <div class="card event-card">
                    ${event.featured ? '<div class="featured-badge"><span class="badge bg-warning">Featured</span></div>' : ''}
                    <div class="date-badge">
                        <div class="month">${formattedDate.month}</div>
                        <div class="day">${formattedDate.day}</div>
                    </div>
                    <img src="${event.image}" class="card-img-top" alt="${event.title}">
                    <span class="category-badge badge bg-primary">${event.category}</span>
                    <div class="card-body">
                        <h5 class="card-title">${event.title}</h5>
                        <p class="card-text text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i>${event.location}<br>
                            <i class="fas fa-clock me-2"></i>${event.time}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">${event.price}</span>
                            <button class="btn btn-outline-primary btn-sm">Learn More</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}
function logout() {
    Swal.fire({
        title: 'Logging out...',
        text: 'You will be redirected to the login page.',
        icon: 'info',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'index.html'; // Redirect to login page
    });
}

function showEventDetails(eventId) {
    const event = events.find(e => e.id === eventId);
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    const modalTitle = document.querySelector('#eventModal .modal-title');
    const modalBody = document.querySelector('#eventModal .modal-body');
    const registerButton = document.getElementById('registerButton');

    modalTitle.textContent = event.title;
    modalBody.innerHTML = `
        <img src="${event.image}" class="mb-4" alt="${event.title}">
        <div class="row">
            <div class="col-md-8">
                <h5>About This Event</h5>
                <p>${event.description}</p>
                
                <h5 class="mt-4">Schedule</h5>
                <ul class="event-details-list">
                    ${event.schedule.map(item => `<li><i class="fas fa-clock"></i>${item}</li>`).join('')}
                </ul>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Event Details</h6>
                        <ul class="event-details-list">
                            <li><i class="fas fa-calendar"></i>${event.date}</li>
                            <li><i class="fas fa-clock"></i>${event.time}</li>
                            <li><i class="fas fa-map-marker-alt"></i>${event.location}</li>
                            <li><i class="fas fa-ticket-alt"></i>${event.price}</li>
                            <li><i class="fas fa-user"></i>${event.organizer}</li>
                        </ul>
                        
                        <h6 class="mt-4">Amenities</h6>
                        <div class="d-flex flex-wrap gap-2">
                            ${event.amenities.map(amenity => 
                                `<span class="badge bg-light text-dark">${amenity}</span>`
                            ).join('')}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    registerButton.setAttribute('data-event-id', event.id);

    modal.show();
}



document.addEventListener('DOMContentLoaded', () => {
    createEventCards();

    document.getElementById('searchInput').addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filteredEvents = events.filter(event => 
            event.title.toLowerCase().includes(searchTerm) ||
            event.description.toLowerCase().includes(searchTerm)
        );
        
      
    });

    document.getElementById('registerButton').addEventListener('click', (e) => {
        const eventId = e.target.getAttribute('data-event-id');
        window.location.href = `event-registration.html?eventId=${eventId}`;
    });
});
</script>

</body>
</html>