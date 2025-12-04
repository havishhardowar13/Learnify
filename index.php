<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnify - Learn Skills for Your Future</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
   <?php require_once 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content fade-in-up">
            <h1>Learn Skills for Your Future</h1>
            <p>Access high-quality courses from expert instructors, earn certificates, and advance your career.</p>
            <div class="hero-buttons">
                <a href="courses.php" class="btn btn-primary" style="background: white; color: #667eea;">Explore Courses</a>
                <a href="register.php" class="btn btn-outline">Start Learning</a>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="section">
        <div class="section-header">
            <h2>Featured Courses</h2>
            <hr class="divider">
            <p>Discover our regular courses designed to boost your career!</p>
        </div>

        <div class="courses-grid">
            <!-- Course 1 -->
            <div class="course-card fade-in-up">
                <div class="course-badge">Project Experience</div>
                <div class="course-image">
                    <i class="fas fa-code"></i>
                </div>
                <div class="course-content">
                    <span class="course-category">Web Development</span>
                    <h3 class="course-title">Web Development Fundamentals</h3>
                    <div class="course-stats">
                        <span class="rating">★★★★★</span>
                        <span>up to 100 reviews</span>
                    </div>
                    <p class="course-description">
                        Master essential web skills and build real-world projects. Perfect for beginners starting their coding journey.
                    </p>
                    <div class="course-price">
                        <span class="price">$50.00</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <!-- Course 2 -->
            <div class="course-card fade-in-up">
                <div class="course-badge">Project Team</div>
                <div class="course-image">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="course-content">
                    <span class="course-category">Graphic Design</span>
                    <h3 class="course-title">Graphic Design Essentials</h3>
                    <div class="course-stats">
                        <span class="rating">★★★★★</span>
                        <span>up to 70 reviews</span>
                    </div>
                    <p class="course-description">
                        Learn industry-standard design tools and create stunning visuals. Build your portfolio with real projects.
                    </p>
                    <div class="course-price">
                        <span class="price">$50.00</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <!-- Course 3 -->
            <div class="course-card fade-in-up">
                <div class="course-badge">Features</div>
                <div class="course-image">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="course-content">
                    <span class="course-category">Data Science</span>
                    <h3 class="course-title">Data Science with Python</h3>
                    <div class="course-stats">
                        <span class="rating">★★★★★</span>
                        <span>up to 10 reviews</span>
                    </div>
                    <p class="course-description">
                        Master Python for data analysis, visualization, and machine learning. Work with real datasets and build predictive models.
                    </p>
                    <div class="course-price">
                        <span class="price">$120.00</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>

            <!-- Course 4 -->
            <div class="course-card fade-in-up">
                <div class="course-badge">Data Metrics</div>
                <div class="course-image">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="course-content">
                    <span class="course-category">Digital Marketing</span>
                    <h3 class="course-title">Digital Marketing Mastery</h3>
                    <div class="course-stats">
                        <span class="rating">★★★★★</span>
                        <span>up to 90 reviews</span>
                    </div>
                    <p class="course-description">
                        Learn data-driven marketing strategies, analytics, and campaign optimization for business growth.
                    </p>
                    <div class="course-price">
                        <span class="price">$75.00</span>
                        <button class="enroll-btn">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section features">
        <div class="section-header">
            <h2>Why Choose Learnify?</h2>
            <hr class="divider">
            <p>We provide the best learning experience for our students</p>
        </div>

        <div class="features-grid">
            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3>Expert Instructors</h3>
                <p>Learn from industry experts with years of practical experience and teaching expertise.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Certified Courses</h3>
                <p>Earn recognized certificates upon course completion to boost your career prospects.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Learn at Your Pace</h3>
                <p>Access course materials anytime, anywhere with our flexible learning schedule.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once 'includes/footer.php'; ?>

    <script>
        // Add fade-in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, observerOptions);

        // Observe all elements with fade-in-up class
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.fade-in-up');
            animatedElements.forEach(el => {
                el.style.opacity = "0";
                el.style.transform = "translateY(30px)";
                el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
                observer.observe(el);
            });

            // Add hover effects to course cards
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
