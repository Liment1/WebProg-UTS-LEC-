<?php
session_start();
    require_once 'connection.php';

    if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'user') {
        header("Location: login.php");
        exit();
    } 
    
    if (isset($_POST['error_message'])) {
    $error_message = htmlspecialchars($_POST['error_message']);
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '$error_message',
                confirmButtonText: 'OK'
            });
        });
    </script>";
    }
    
  
$fetchUserSQL = "SELECT name FROM users WHERE user_id = ?"; 
$stmt = $connection->prepare($fetchUserSQL);
$stmt->execute([$_SESSION["user_id"]]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
    error_reporting(E_ALL);
ini_set('display_errors', 1);
$userName = $userData ? $userData['name'] : '';


?>



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

        .borderless {
        border: none;
        border-bottom: 1px solid #ccc; 
        padding: 5px;
        background-color: transparent;
        box-shadow: none;
    }

    .borderless:focus {
        outline: none;
        border-bottom: 1px solid #000; 
    }

    #previewImage {
        cursor: pointer;
        width: 100%;
        max-width: 100%; 
        object-fit: cover;
    }

    #previewImage:hover {
        opacity: 0.8;
    }

    .same-size-btn {
        height: calc(2.25rem + 2px); 
    }

    
</style>

    </style>
</head>
<body>
    <?php
    
    $sql = "SELECT * FROM events WHERE Event_status = 'open'";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    ?>
   
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">EventHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Browse Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user-event.php">My Registrations</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="user-profile.php">My Profile</a>
                </li>
            </ul>
            <span class="navbar-text">
                Welcome, <span id="userName"><?= htmlspecialchars($userName) ?></span>
                <button class="btn btn-outline-light ms-3" onclick="logout()">Logout</button>
            </span>
        </div>
    </div>
</nav>

    <div class="container pe-5">
    <div class="container py-5">
    <!-- <div class="row mb-4">
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
    </div> -->

    <div class="row g-4" id="registeredEvents">
        
    </div>
</div>
        <div class="row g-4" id="eventsContainer">
            <?php
            $EventsData = []; 
            while($Events = $stmt->fetch(PDO::FETCH_ASSOC)){
                $idx = (int) substr($Events['event_id'], 1);
                $EventsData[$idx] = [
                    'event_date' => $Events['event_date'],
                    'banner_url' => $Events['banner_url'],
                    'banner_name' => $Events['banner_name'],
                    'event_status' => $Events['event_status'],
                    'event_name' => $Events['event_name'],
                    'location' => $Events['location'],
                    'description' => $Events['description'],
                    'event_time' => $Events['event_time'],
                    'status' => $Events['event_status'],
                    'curr_participants' => $Events['curr_participants'],
                    'max_participants' => $Events['max_participants']
                ];
                $featured = false; 
                $ymd = explode('-',$Events['event_date']); 
                $month = $ymd[1];
                $date = $ymd[2]; 
                $CompleteTime = explode(':',$Events['event_time']); 
                $eventTime = $CompleteTime[0].':'.$CompleteTime[1];
                
                $badgeClass = 'bg-primary'; 
                if ($Events['event_status'] === 'open') {
                    $badgeClass = 'bg-success'; 
                } elseif ($Events['event_status'] === 'cancelled') {
                    $badgeClass = 'bg-warning'; 
                } elseif ($Events['event_status'] === 'closed') {
                    $badgeClass = 'bg-danger'; 
                }
                ?>
                <!-- display -->
                <div class="col-md-4">
    <div class="card event-card" onclick="updateEvent(<?= $idx ?>)" style="cursor: pointer;">
        <div class="date-badge">
            <div class="month"><?= htmlspecialchars($month) ?></div>
            <div class="day"><?= htmlspecialchars($date) ?></div>
        </div>
        <img src=<?= 'banner/'.htmlspecialchars($Events['banner_url']) ?> class="card-img-top" alt=<?= $Events['banner_name'] ?>>
        <span class="category-badge badge <?= $badgeClass ?>"><?= htmlspecialchars( $Events['event_status']) ?></span>
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars( $Events['event_name']) ?></h5>
            <p class="card-text text-muted">
                <i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($Events['location']) ?><br>
                <i class="fas fa-clock me-2"></i><?= htmlspecialchars($eventTime) ?>
            </p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold"><?= htmlspecialchars($Events['curr_participants']).'/'. htmlspecialchars($Events['max_participants']) ?></span>
            </div>
        </div>
    </div>
</div>

            <?php
            }
            ?>
        </div>
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
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.4.0/purify.min.js"></script>

<script>
const EventsData = <?php echo json_encode($EventsData); ?>;

function formatDate(dateString) {
    const date = new Date(dateString);
    const month = date.toLocaleString('default', { month: 'short' });
    const day = date.getDate();
    return { month, day };
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
        window.location.href = 'logout.php';
    });
}

function updateEvent(eventId) {
    const event = EventsData[eventId];
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    const modalBody = document.querySelector('#eventModal .modal-body');
    const modalHeader = document.querySelector('#eventModal .modal-header');

    modalHeader.innerHTML = `
        <h5 class="modal-title fs-2 fw-bold">Event Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    `;

    const sanitizedEventName = DOMPurify.sanitize(event.event_name);
    const sanitizedBannerUrl = DOMPurify.sanitize("banner/".concat(event.banner_url));
    const sanitizedBannerName = DOMPurify.sanitize(event.banner_name);
    const sanitizedDescription = DOMPurify.sanitize(event.description);
    const sanitizedMaxParticipants = DOMPurify.sanitize(event.max_participants);
    const sanitizedEventDate = DOMPurify.sanitize(event.event_date);
    const sanitizedEventTime = DOMPurify.sanitize(event.event_time);
    const sanitizedLocation = DOMPurify.sanitize(event.location);
    const sanitizedstatus = DOMPurify.sanitize(event.status);

    modalBody.innerHTML = `
        <div class="modal-header pt-0">
            <h2 class="fs-4 fw-bold">${sanitizedEventName}</h2>
        </div>

        <div class="d-block mb-4">
            <img src="${sanitizedBannerUrl}" class="mb-4" alt="${sanitizedBannerName}" style="max-width: 100%;">
        </div>

        <div class="row">
            <div class="col-md-8">
                <h5>About This Event</h5>
                <p>${sanitizedDescription}</p>

                <h6 class="mt-4">Capacity</h6>
                <p>${event.curr_participants}/${sanitizedMaxParticipants}</p>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Event Details</h6>
                        <ul class="event-details-list">
                            <li><i class="fas fa-calendar"></i> ${sanitizedEventDate}</li>
                            <li><i class="fas fa-clock"></i> ${sanitizedEventTime}</li>
                            <li><i class="fas fa-map-marker-alt"></i> ${sanitizedLocation}</li>
                            <li><i class="fas fa-chart-simple"></i> Status: ${sanitizedstatus.charAt(0).toUpperCase() + sanitizedstatus.slice(1)}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">

    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>


    <div class="d-flex">
        <!-- Register button -->
        <form action="register-proses.php" method="POST" class="d-inline">
            <input type="hidden" name="event_id" value="${eventId}">
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>


</div>

        </div>
    `;

    modal.show();
}

</script>

</body>
</html> 