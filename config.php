<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "portfolio_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create tables if they don't exist
$tables = [
    "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS projects (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) NOT NULL,
        tags TEXT NOT NULL,
        project_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS testimonials (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        client_name VARCHAR(100) NOT NULL,
        client_position VARCHAR(100) NOT NULL,
        client_company VARCHAR(100) NOT NULL,
        testimonial TEXT NOT NULL,
        client_image VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS services (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        icon VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $table) {
    if ($conn->query($table) !== TRUE) {
        echo "Error creating table: " . $conn->error;
    }
}

// Insert sample data if tables are empty
$check_projects = $conn->query("SELECT COUNT(*) as count FROM projects");
$projects_count = $check_projects->fetch_assoc()['count'];

if ($projects_count == 0) {
    // Sample projects
    $sample_projects = [
        [
            'E-commerce Website', 
            'web', 
            'A fully responsive e-commerce website with product filtering, cart functionality, and payment integration.',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80',
            'HTML,CSS,JavaScript,React',
            'https://example.com/project1'
        ],
        [
            'Mobile App', 
            'app', 
            'A cross-platform mobile app for task management with offline capabilities and cloud synchronization.',
            'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1674&q=80',
            'React Native,Firebase,Redux',
            'https://example.com/project2'
        ],
        [
            'Dashboard UI', 
            'ui', 
            'An admin dashboard with data visualization, user management, and real-time updates.',
            'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80',
            'Figma,React,Chart.js',
            'https://example.com/project3'
        ]
    ];
    
    $project_stmt = $conn->prepare("INSERT INTO projects (title, category, description, image, tags, project_url) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($sample_projects as $project) {
        $project_stmt->bind_param("ssssss", $project[0], $project[1], $project[2], $project[3], $project[4], $project[5]);
        $project_stmt->execute();
    }
}

// Insert sample testimonials if empty
$check_testimonials = $conn->query("SELECT COUNT(*) as count FROM testimonials");
$testimonials_count = $check_testimonials->fetch_assoc()['count'];

if ($testimonials_count == 0) {
    // Sample testimonials
    $sample_testimonials = [
        [
            'Sarah Johnson',
            'CEO',
            'TechStart',
            'Abdul is an exceptional web developer. He delivered our e-commerce website ahead of schedule and exceeded all our expectations. His attention to detail and creative solutions made our project a success.',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80'
        ],
        [
            'Michael Brown',
            'Marketing Director',
            'Innovate Inc.',
            'Working with Abdul was a pleasure. He understood our requirements perfectly and created a beautiful, functional website that has significantly increased our online presence and sales.',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80'
        ],
        [
            'Emily Davis',
            'Product Manager',
            'AppWorks',
            'Abdul\'s expertise in UI/UX design transformed our app. The user interface is intuitive, visually appealing, and has received excellent feedback from our users. I highly recommend his services.',
            'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80'
        ]
    ];
    
    $testimonial_stmt = $conn->prepare("INSERT INTO testimonials (client_name, client_position, client_company, testimonial, client_image) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($sample_testimonials as $testimonial) {
        $testimonial_stmt->bind_param("sssss", $testimonial[0], $testimonial[1], $testimonial[2], $testimonial[3], $testimonial[4]);
        $testimonial_stmt->execute();
    }
}

// Insert sample services if empty
$check_services = $conn->query("SELECT COUNT(*) as count FROM services");
$services_count = $check_services->fetch_assoc()['count'];

if ($services_count == 0) {
    // Sample services
    $sample_services = [
        [
            'Web Development',
            'I create responsive websites that are fast, easy to use, and built with best practices. My focus is creating dynamic, engaging interfaces.',
            'fas fa-laptop-code'
        ],
        [
            'Responsive Design',
            'Your site will look good on any device. I create responsive designs that adapt to any screen size, from desktop to mobile.',
            'fas fa-mobile-alt'
        ],
        [
            'UI/UX Design',
            'I design beautiful user interfaces and create great user experiences. My designs are intuitive and user-friendly.',
            'fas fa-paint-brush'
        ],
        [
            'Backend Development',
            'I develop robust backend systems that power your web applications, ensuring they\'re secure, scalable, and efficient.',
            'fas fa-server'
        ],
        [
            'E-commerce Solutions',
            'I build online stores with secure payment gateways, inventory management, and user-friendly interfaces.',
            'fas fa-shopping-cart'
        ],
        [
            'SEO Optimization',
            'I optimize your website for search engines to improve visibility and drive more traffic to your business.',
            'fas fa-search'
        ]
    ];
    
    $service_stmt = $conn->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
    
    foreach ($sample_services as $service) {
        $service_stmt->bind_param("sss", $service[0], $service[1], $service[2]);
        $service_stmt->execute();
    }
}

echo "Database and tables created successfully with sample data!";
?>