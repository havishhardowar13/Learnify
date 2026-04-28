<?php
// about.php
$page_title = "About Us - Learnify";
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4rem 2rem;">
    <div class="hero-content">
        <h1>About Learnify</h1>
        <p>Transforming education through innovative online learning solutions</p>
    </div>
</section>

<!-- Mission Section -->
<section class="section">
    <div class="section-header">
        <h2>Our Mission</h2>
        <hr class="divider">
    </div>
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <p style="font-size: 1.2rem; line-height: 1.8; color: var(--text-light);">
            At Learnify, we believe that education should be accessible, engaging, and transformative. 
            Our mission is to empower learners worldwide with high-quality courses taught by industry experts, 
            helping them acquire the skills needed to thrive in today's competitive job market.
        </p>
    </div>
</section>

<!-- Stats Section -->
<section class="section" style="background: var(--background);">
    <div class="section-header">
        <h2>Why Choose Learnify?</h2>
        <hr class="divider">
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
        <div class="feature-card" style="text-align: center;">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>50,000+ Students</h3>
            <p>Join our growing community of learners from around the world</p>
        </div>
        
        <div class="feature-card" style="text-align: center;">
            <div class="feature-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h3>200+ Courses</h3>
            <p>Comprehensive curriculum covering in-demand skills and technologies</p>
        </div>
        
        <div class="feature-card" style="text-align: center;">
            <div class="feature-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h3>Expert Instructors</h3>
            <p>Learn from industry professionals with real-world experience</p>
        </div>
        
        <div class="feature-card" style="text-align: center;">
            <div class="feature-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3>Career Support</h3>
            <p>Get help with job placement and career advancement</p>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section">
    <div class="section-header">
        <h2>Meet Our Team</h2>
        <hr class="divider">
        <p>The passionate university students behind Learnify</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem;">
        <div class="team-card" style="background: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                <i class="fas fa-user"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Havish</h3>
            <p style="color: var(--primary); font-weight: 600; margin-bottom: 1rem;">Cyber Security Student</p>
            <p style="color: var(--text-light); line-height: 1.6;">
                Passionate about educational technology and creating accessible learning platforms for students worldwide.
            </p>
        </div>
        
        <div class="team-card" style="background: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                <i class="fas fa-user"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Julien</h3>
            <p style="color: var(--primary); font-weight: 600; margin-bottom: 1rem;">Cyber Security Student</p>
            <p style="color: var(--text-light); line-height: 1.6;">
                Focused on curriculum development and creating engaging learning experiences for diverse student needs.
            </p>
        </div>
        
        <div class="team-card" style="background: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                <i class="fas fa-user"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">CJ</h3>
            <p style="color: var(--primary); font-weight: 600; margin-bottom: 1rem;">Cyber Security Student</p>
            <p style="color: var(--text-light); line-height: 1.6;">
                Combines technical expertise with a vision for making quality education accessible to everyone.
            </p>
        </div>
        
        <div class="team-card" style="background: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                <i class="fas fa-user"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Daniel</h3>
            <p style="color: var(--primary); font-weight: 600; margin-bottom: 1rem;">Cyber Security Student</p>
            <p style="color: var(--text-light); line-height: 1.6;">
                Creates intuitive user interfaces and engaging visual content to enhance the learning experience.
            </p>
        </div>
        
        <div class="team-card" style="background: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                <i class="fas fa-user"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Jibran</h3>
            <p style="color: var(--primary); font-weight: 600; margin-bottom: 1rem;">Cyber Security Student</p>
            <p style="color: var(--text-light); line-height: 1.6;">
                Manages community engagement and strategic partnerships to grow Learnify's student network.
            </p>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section" style="background: var(--background);">
    <div class="section-header">
        <h2>Our Values</h2>
        <hr class="divider">
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
            <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; margin-right: 1rem;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 style="color: var(--text-dark);">Quality Education</h3>
            </div>
            <p style="color: var(--text-light); line-height: 1.6;">
                We maintain the highest standards in course content and instructional design to ensure effective learning outcomes.
            </p>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
            <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; margin-right: 1rem;">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 style="color: var(--text-dark);">Student Success</h3>
            </div>
            <p style="color: var(--text-light); line-height: 1.6;">
                Your success is our priority. We provide comprehensive support throughout your learning journey.
            </p>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
            <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; margin-right: 1rem;">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3 style="color: var(--text-dark);">Innovation</h3>
            </div>
            <p style="color: var(--text-light); line-height: 1.6;">
                We continuously evolve our platform and teaching methods to incorporate the latest educational technologies.
            </p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <h2 style="margin-bottom: 1rem;">Ready to Start Your Learning Journey?</h2>
    <p style="margin-bottom: 2rem; font-size: 1.2rem; opacity: 0.9;">
        Join thousands of students who have transformed their careers with Learnify
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="register.php" class="btn" style="background: white; color: var(--primary);">Get Started</a>
        <a href="courses.php" class="btn btn-outline">Browse Courses</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
