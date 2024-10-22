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
    // require __DIR__ . '../../vendor/autoload.php';
    // session_start();
    // if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'user') {
    //     header("Location: src/login.php");  
    //     exit();
    // } 
    require_once __DIR__ .  '../../connection.php';
    $sql = "SELECT * FROM Events";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $idx = 1;
    ?>
   
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="event-manage.php">Admin Page</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="event-manage.php">Event Management </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="User-manage.php">User Management</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <span id="userName">User</span>
                    <button class="btn btn-outline-light ms-3" onclick="logout('login.php')">Logout</button>
                </span>
            </div>
        </div>
    </nav>

    <div class="container pe-5">
        <div class="search-container mb-4">
            <div class="row">
                <div class="col-md-5 mb-3">
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
                <div class="col-md-1 d-flex pb-3 align-items-center">
                    <button class="btn btn-primary btn-md" onclick="addEvents()">Add</button>
                </div>
            </div>
        </div>  
        <div class="row g-4" id="eventsContainer">
            <?php
            $EventsData = []; 
            while($Events = $stmt->fetch(PDO::FETCH_ASSOC)){
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
                <div class="card event-card">
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
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $idx ?>)">Delete</button>
                            <button class="btn btn-warning btn-sm" onclick="updateEvent(<?= $idx ?>)">Edit</button>
                            <form action="event-export.php" method="POST">
                                <input type="text" name="event_ID" value=<?= $idx ?> hidden> 
                                <input type="submit" class="btn btn-success btn-sm" value="Export">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $idx += 1;
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
        window.location.href = 'src/login.php'; 
    });
}

function confirmDelete(itemId) {
    const modalHtml = `
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this event?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="event-delete.php" method="POST">
                            <input type="hidden" name="event_id" id="deleteIdField" value="${itemId}">
                            <input type="submit" class="btn btn-danger" id="confirmDelete" value="Delete">
                        </form>
                    </div>
                </div>
            </div>
        </div>

    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();

    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

function updateEvent(eventId) {
    const event = EventsData[eventId];
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    const modalBody = document.querySelector('#eventModal .modal-body');
    const modalHeader = document.querySelector('#eventModal .modal-header');

    modalHeader.innerHTML = `
        <h5 class="modal-title fs-2 fw-bold">Edit Event</h5>
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
    <form action="event-update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value="${eventId}">
        <input type="hidden" name="default_banner_url" value="${event.banner_url}"> <!-- Hidden field for default image -->
        <input type="hidden" name="default_banner_name" value="${event.banner_name}"> <!-- Hidden field for default image -->

        <div class="modal-header pt-0">
            <input type="text" name="event_name" value="${sanitizedEventName}" class="form-control borderless fw-bold" />
        </div>

        <label for="imageUpload" class="d-block">
            <img src="${sanitizedBannerUrl}" class="mb-4" alt="${sanitizedBannerName}" id="previewImage" style="cursor: pointer; max-width: 100%;">
        </label>
        <input type="file" name="banner" id="imageUpload" style="display: none;" accept="image/*">

        <div class="row">
            <div class="col-md-8">
                <h5>About This Event</h5>
                <textarea name="description" rows="3" class="form-control borderless">${sanitizedDescription}</textarea>

                <h6 class="mt-4">Capacity</h6>
                <div class="input-group w-25">
                    <span class="input-group-text">${event.curr_participants}/</span>
                    <input type="number" name="max_capacity" value="${sanitizedMaxParticipants}" class="form-control borderless" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Event Details</h6>
                        <ul class="event-details-list">
                            <li><i class="fas fa-calendar"></i> 
                                <input type="date" name="date" value="${sanitizedEventDate}" class="form-control borderless" />
                            </li>
                            <li><i class="fas fa-clock"></i> 
                                <input type="time" name="time" value="${sanitizedEventTime}" class="form-control borderless" />
                            </li>
                            <li><i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="location" value="${sanitizedLocation}" class="form-control borderless" />
                            </li>
                            <li><i class="fas fa-chart-simple"></i>
                                <select name="status" class="form-control borderless">
                                    <option value="open" ${sanitizedstatus === 'open' ? 'selected' : ''}>Open</option>
                                    <option value="closed" ${sanitizedstatus === 'closed' ? 'selected' : ''}>Closed</option>
                                    <option value="cancelled" ${sanitizedstatus === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4 float-end">Update Event</button>
    </form>
    `;

    modal.show();

    const imageInput = document.getElementById('imageUpload');
    const previewImage = document.getElementById('previewImage');

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0]; 
        if (file) {
            const reader = new FileReader(); 

            reader.onload = function(e) {
                previewImage.src = e.target.result; 
            }

            reader.readAsDataURL(file); 
        }
    });
}

function addEvents() {
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    const modalBody = document.querySelector('#eventModal .modal-body');
    const modalHeader = document.querySelector('#eventModal .modal-header');

    // Set modal title
    modalHeader.innerHTML = `
        <h5 class="modal-title fs-2 fw-bold">Add Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    `;

    // Clear form values and set the form action to event-add.php
    modalBody.innerHTML = `
    <form id="addEventForm" action="event-add.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value=""> <!-- Clear event_id -->

        <div class="modal-header pt-0">
            <input type="text" name="event_name" value="" class="form-control borderless fw-bold" placeholder="Enter event name" />
        </div>

        <div class="input-group mb-3">
            <label class="input-group-text" for="banner">Insert Image</label>
            <input type="file" class="form-control" name="banner" id="imageUpload" accept="image/*">
        </div>

        <!-- Image preview -->
        <div class="mb-3">
            <img id="previewImage" src="" alt="Image Preview" style="display:none; max-width: 100%; height: auto;" />
        </div>

        <div class="row">
            <div class="col-md-8">
                <h5>About This Event</h5>
                <textarea name="description" rows="3" class="form-control borderless" placeholder="Enter description"></textarea>

                <h6 class="mt-4">Capacity</h6>
                <div class="input-group w-50">
                    <input type="number" name="max_capacity" value="" class="form-control borderless" placeholder="Enter max capacity" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Event Details</h6>
                        <ul class="event-details-list">
                            <li><i class="fas fa-calendar"></i> 
                                <input type="date" name="date" value="" class="form-control borderless" />
                            </li>
                            <li><i class="fas fa-clock"></i> 
                                <input type="time" name="time" value="" class="form-control borderless" />
                            </li>
                            <li><i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="location" value="" class="form-control borderless" placeholder="Enter location" />
                            </li>
                            <li><i class="fas fa-chart-simple"></i>
                                <select name="status" class="form-control borderless">
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4 float-end">Add</button> <!-- Changed to Add -->
    </form>
    `;

    // Show the modal
    modal.show();

    // Get the image input and preview elements
    const imageInput = document.getElementById('imageUpload');
    const previewImage = document.getElementById('previewImage');

    // Add change event listener for image input
    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader(); 

            // When the file is read, display the image preview
            reader.onload = function(e) {
                previewImage.src = e.target.result; 
                previewImage.style.display = 'block'; // Show the image
            }

            reader.readAsDataURL(file); // Read the file as a Data URL (base64)
        } else {
            previewImage.style.display = 'none'; // Hide the preview if no file is selected
        }
    });
}


</script>

</body>
</html>