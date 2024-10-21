<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 700px;
        }
        .profile-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-weight: bold;
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-section h2 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        #eventHistory .list-group-item {
            border: none;
            border-bottom: 1px solid #dee2e6;
            padding-left: 0;
            padding-right: 0;
        }
        #eventHistory .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EventHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Browse Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="event-registration.php">My Registrations</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="user-profile.php">My Profile</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <span id="userName">User</span>
                    <button class="btn btn-outline-light ms-3" onclick="logout('login.php')">Logout</button>
                </span>
            </div>
        </div>
    </nav>


<div class="container py-5">
    <h1>User Profile Management</h1>

    <div class="profile-section">
        <h2>View Profile</h2>
        <div id="profileInfo">
            <p><strong>Name:</strong> <span id="displayName"></span></p>
            <p><strong>Email:</strong> <span id="displayEmail"></span></p>
        </div>
    </div>

    <div class="profile-section">
        <h2>Edit Profile</h2>
        <form id="editProfileForm">
            <div class="mb-3">
                <label for="inputName" class="form-label">Name</label>
                <input type="text" class="form-control" id="inputName" required>
            </div>
            <div class="mb-3">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" required>
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">New Password (leave blank if unchanged)</label>
                <input type="password" class="form-control" id="inputPassword">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <div class="profile-section">
        <h2>Event Registration History</h2>
        <ul id="eventHistory" class="list-group">
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>

let userData = {
    name: "John Doe",
    email: "john.doe@example.com",
    eventHistory: [
        { id: 1, name: "Summer Music Festival 2024", date: "2024-07-15" },
        { id: 2, name: "Tech Innovation Summit", date: "2024-08-20" }
    ]
};
function logout() {
    Swal.fire({
        title: 'Logging out...',
        text: 'You will be redirected to the login page.',
        icon: 'info',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'login.php'; 
    });
}

function updateProfileDisplay() {
    document.getElementById('displayName').textContent = userData.name;
    document.getElementById('displayEmail').textContent = userData.email;
}

function populateEditForm() {
    document.getElementById('inputName').value = userData.name;
    document.getElementById('inputEmail').value = userData.email;
}

function updateEventHistory() {
    const historyList = document.getElementById('eventHistory');
    historyList.innerHTML = '';
    userData.eventHistory.forEach(event => {
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item';
        listItem.innerHTML = `
            <strong>${event.name}</strong><br>
            <small class="text-muted">Date: ${event.date}</small>
        `;
        historyList.appendChild(listItem);
    });
}

document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
   
    userData.name = document.getElementById('inputName').value;
    userData.email = document.getElementById('inputEmail').value;
    
    const newPassword = document.getElementById('inputPassword').value;
    if (newPassword) {
        
        console.log("Password changed to:", newPassword);
    }

    updateProfileDisplay();
    
    
    Swal.fire({
        icon: 'success',
        title: 'Profile Updated',
        text: 'Your profile information has been updated successfully!',
        confirmButtonColor: '#007bff'
    });
});

document.addEventListener('DOMContentLoaded', function() {
    updateProfileDisplay();
    populateEditForm();
    updateEventHistory();
});
</script>

</body>
</html>
