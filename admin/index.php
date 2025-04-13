<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database configuration
require_once '../config.php';

// Get counts for dashboard
$project_count = $conn->query("SELECT COUNT(*) as count FROM projects")->fetch_assoc()['count'];
$testimonial_count = $conn->query("SELECT COUNT(*) as count FROM testimonials")->fetch_assoc()['count'];
$service_count = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$message_count = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF5722;
            --orange-light: #FF7F50;
            --orange-dark: #E64A19;
            --charcoal: #333333;
            --charcoal-light: #444444;
            --charcoal-dark: #222222;
            --white: #FFFFFF;
            --off-white: #F5F5F5;
            --gradient: linear-gradient(135deg, var(--orange) 0%, #FF9800 100%);
            --gradient-dark: linear-gradient(135deg, var(--orange-dark) 0%, #E65100 100%);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--off-white);
            color: var(--charcoal);
            line-height: 1.6;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--charcoal-dark);
            color: var(--white);
            padding: 20px 0;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--white);
        }

        .sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient);
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
        }

        .sidebar-logo-text {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu-item {
            padding: 10px 20px;
            transition: var(--transition);
        }

        .sidebar-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu-item.active {
            background-color: var(--orange);
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--white);
            text-decoration: none;
            font-size: 1rem;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 1.8rem;
            color: var(--charcoal-dark);
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--charcoal);
            font-size: 1rem;
        }

        .user-dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--white);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            min-width: 150px;
            display: none;
            z-index: 10;
        }

        .user-dropdown-content a {
            display: block;
            padding: 10px 15px;
            color: var(--charcoal);
            text-decoration: none;
            transition: var(--transition);
        }

        .user-dropdown-content a:hover {
            background-color: var(--off-white);
        }

        .user-dropdown:hover .user-dropdown-content {
            display: block;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .dashboard-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            color: var(--white);
        }

        .dashboard-card-icon.projects {
            background-color: #4CAF50;
        }

        .dashboard-card-icon.testimonials {
            background-color: #2196F3;
        }

        .dashboard-card-icon.services {
            background-color: #FF9800;
        }

        .dashboard-card-icon.messages {
            background-color: #9C27B0;
        }

        .dashboard-card-content h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .dashboard-card-content p {
            color: var(--charcoal-light);
            font-size: 0.9rem;
        }

        .content-section {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-header h2 {
            font-size: 1.5rem;
            color: var(--charcoal-dark);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            background-color: var(--orange);
            color: var(--white);
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn:hover {
            background-color: var(--orange-dark);
        }

        .btn-secondary {
            background-color: var(--charcoal-light);
        }

        .btn-secondary:hover {
            background-color: var(--charcoal);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: var(--off-white);
            font-weight: 600;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .table-actions a {
            color: var(--charcoal);
            text-decoration: none;
            transition: var(--transition);
        }

        .table-actions a:hover {
            color: var(--orange);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 2px rgba(255, 87, 34, 0.2);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="sidebar-logo">
                    <div class="sidebar-logo-icon">AYS</div>
                    <div class="sidebar-logo-text">Admin Panel</div>
                </a>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item active">
                    <a href="index.php" class="sidebar-menu-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="projects.php" class="sidebar-menu-link">
                        <i class="fas fa-briefcase"></i>
                        <span>Projects</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="testimonials.php" class="sidebar-menu-link">
                        <i class="fas fa-quote-right"></i>
                        <span>Testimonials</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="services.php" class="sidebar-menu-link">
                        <i class="fas fa-cogs"></i>
                        <span>Services</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="messages.php" class="sidebar-menu-link">
                        <i class="fas fa-envelope"></i>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="settings.php" class="sidebar-menu-link">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-dropdown">
                    <button class="user-dropdown-btn">
                        <i class="fas fa-user-circle"></i>
                        <span>Admin</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-dropdown-content">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="dashboard-card-icon projects">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $project_count; ?></h3>
                        <p>Projects</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-icon testimonials">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $testimonial_count; ?></h3>
                        <p>Testimonials</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-icon services">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $service_count; ?></h3>
                        <p>Services</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-icon messages">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $message_count; ?></h3>
                        <p>Messages</p>
                    </div>
                </div>
            </div>
            <div class="content-section">
                <div class="content-header">
                    <h2>Recent Messages</h2>
                    <a href="messages.php" class="btn">
                        <i class="fas fa-eye"></i>
                        <span>View All</span>
                    </a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get recent messages
                        $messages_result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
                        
                        if ($messages_result->num_rows > 0) {
                            while ($message = $messages_result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($message['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($message['email']) . '</td>';
                                echo '<td>' . htmlspecialchars($message['subject']) . '</td>';
                                echo '<td>' . date('M d, Y', strtotime($message['created_at'])) . '</td>';
                                echo '<td class="table-actions">';
                                echo '<a href="view_message.php?id=' . $message['id'] . '" title="View"><i class="fas fa-eye"></i></a>';
                                echo '<a href="delete_message.php?id=' . $message['id'] . '" title="Delete" onclick="return confirm(\'Are you sure you want to delete this message?\')"><i class="fas fa-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No messages found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="content-section">
                <div class="content-header">
                    <h2>Recent Projects</h2>
                    <a href="projects.php" class="btn">
                        <i class="fas fa-eye"></i>
                        <span>View All</span>
                    </a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get recent projects
                        $projects_result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5");
                        
                        if ($projects_result->num_rows > 0) {
                            while ($project = $projects_result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($project['title']) . '</td>';
                                echo '<td>' . htmlspecialchars($project['category']) . '</td>';
                                echo '<td>' . date('M d, Y', strtotime($project['created_at'])) . '</td>';
                                echo '<td class="table-actions">';
                                echo '<a href="edit_project.php?id=' . $project['id'] . '" title="Edit"><i class="fas fa-edit"></i></a>';
                                echo '<a href="delete_project.php?id=' . $project['id'] . '" title="Delete" onclick="return confirm(\'Are you sure you want to delete this project?\')"><i class="fas fa-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">No projects found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Toggle dropdown
        document.querySelector('.user-dropdown-btn').addEventListener('click', function() {
            document.querySelector('.user-dropdown-content').classList.toggle('show');
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.user-dropdown-btn') && !event.target.matches('.user-dropdown-btn *')) {
                const dropdowns = document.querySelectorAll('.user-dropdown-content');
                dropdowns.forEach(dropdown => {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>
</html>