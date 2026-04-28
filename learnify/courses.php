<?php
// courses.php - Courses catalog page
$page_title = "Courses - Learnify";
require_once 'includes/header.php';
?>

<section class="section">
    <div class="section-header">
        <h2>All Courses</h2>
        <hr class="divider">
        <p>Browse our complete catalog of courses</p>
    </div>

    <div style="margin-bottom: 2rem; text-align: center;">
        <input type="text" id="searchCourses" placeholder="Search courses..." class="form-control" style="max-width: 400px; margin: 0 auto;">
    </div>

    <div class="courses-grid" id="coursesContainer">
        <!-- Courses will be loaded dynamically -->
    </div>
</section>

<script>
// JavaScript to load and filter courses
document.addEventListener('DOMContentLoaded', function() {
    loadCourses();
    
    document.getElementById('searchCourses').addEventListener('input', function(e) {
        filterCourses(e.target.value);
    });
});

function loadCourses() {
    // This would typically fetch from an API
    const courses = [
        {
            id: 1,
            badge: "Project Experience",
            category: "Web Development",
            title: "Web Development Fundamentals",
            rating: 5,
            reviews: 100,
            description: "Master essential web skills and build real-world projects.",
            price: 50.00
        },
        // Add more courses...
    ];
    
    displayCourses(courses);
}

function displayCourses(courses) {
    const container = document.getElementById('coursesContainer');
    container.innerHTML = courses.map(course => `
        <div class="course-card fade-in-up">
            <div class="course-badge">${course.badge}</div>
            <div class="course-image">
                <i class="fas fa-code"></i>
            </div>
            <div class="course-content">
                <span class="course-category">${course.category}</span>
                <h3 class="course-title">${course.title}</h3>
                <div class="course-stats">
                    <span class="rating">${'★'.repeat(course.rating)}</span>
                    <span>up to ${course.reviews} reviews</span>
                </div>
                <p class="course-description">${course.description}</p>
                <div class="course-price">
                    <span class="price">$${course.price.toFixed(2)}</span>
                    <button class="enroll-btn">Enroll Now</button>
                </div>
            </div>
        </div>
    `).join('');
}

function filterCourses(searchTerm) {
    // Filter logic here
}
</script>

<?php require_once 'includes/footer.php'; ?>
