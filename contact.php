<?php
// contact.php
$page_title = "Contact Us - Learnify";
require_once 'includes/header.php';

// Contact form processing
$success = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }
    
    if (empty($subject)) {
        $errors['subject'] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors['message'] = "Message is required";
    } elseif (strlen($message) < 10) {
        $errors['message'] = "Message must be at least 10 characters long";
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // In a real application, you would:
        // 1. Save to database
        // 2. Send email notification
        // 3. Maybe send auto-response
        
        $success = true;
        
        // For demo purposes, we'll just show success message
        // You can implement actual email sending here
    }
}
?>

<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4rem 2rem;">
    <div class="hero-content">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Get in touch with any questions or feedback.</p>
    </div>
</section>

<div class="section">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; max-width: 1200px; margin: 0 auto;">
        
        <!-- Contact Information -->
        <div>
            <h2 style="color: var(--text-dark); margin-bottom: 2rem;">Get In Touch</h2>
            
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; margin-right: 1rem; flex-shrink: 0;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Our Location</h3>
                        <p style="color: var(--text-light); line-height: 1.6;">
                            Réduit<br>
                            Moka<br>
                            Mauritius
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; margin-right: 1rem; flex-shrink: 0;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Phone Number</h3>
                        <p style="color: var(--text-light); line-height: 1.6;">
                            +230 5773 7096<br>
                            Mon-Fri: 9:00 AM - 6:00 PM
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; margin-right: 1rem; flex-shrink: 0;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Email Address</h3>
                        <p style="color: var(--text-light); line-height: 1.6;">
                            support@Learnify.com<br>
                            info@Learnify.com
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="background: var(--background); padding: 1.5rem; border-radius: 10px;">
                <h3 style="color: var(--text-dark); margin-bottom: 1rem;">Support Hours</h3>
                <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 0.5rem;">
                    <strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM MUT
                </p>
                <p style="color: var(--text-light); line-height: 1.6; margin-bottom: 0.5rem;">
                    <strong>Saturday:</strong> 10:00 AM - 4:00 PM MUT
                </p>
                <p style="color: var(--text-light); line-height: 1.6;">
                    <strong>Sunday:</strong> Closed
                </p>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--card-shadow);">
            <h2 style="color: var(--text-dark); margin-bottom: 1.5rem;">Send us a Message</h2>
            
            <?php if ($success): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <i class="fas fa-check-circle"></i> Thank you for your message! We'll get back to you within 24 hours.
                </div>
            <?php endif; ?>
            
            <form id="contactForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control <?php echo isset($errors['name']) ? 'error' : ''; ?>" 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                           required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['name']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control <?php echo isset($errors['email']) ? 'error' : ''; ?>" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" 
                           id="subject" 
                           name="subject" 
                           class="form-control <?php echo isset($errors['subject']) ? 'error' : ''; ?>" 
                           value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                           required>
                    <?php if (isset($errors['subject'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['subject']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" 
                              name="message" 
                              class="form-control <?php echo isset($errors['message']) ? 'error' : ''; ?>" 
                              rows="6" 
                              required
                              placeholder="Please describe your inquiry in detail..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <div class="error-message show"><?php echo htmlspecialchars($errors['message']); ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn enroll-btn" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<section class="section" style="background: var(--background);">
    <div class="section-header">
        <h2>Frequently Asked Questions</h2>
        <hr class="divider">
        <p>Quick answers to common questions</p>
    </div>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: var(--card-shadow);">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">How do I enroll in a course?</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    Simply browse our courses, select the one you're interested in, and click "Enroll Now." 
                    You'll need to create an account if you don't already have one.
                </p>
            </div>
            
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Are the certificates recognized?</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    Yes! Our certificates are widely recognized by employers and can be verified online. 
                    They demonstrate your commitment to professional development.
                </p>
            </div>
            
            <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Can I access courses on mobile devices?</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    Absolutely! Our platform is fully responsive and works seamlessly on smartphones, tablets, and desktop computers.
                </p>
            </div>
            
            <div style="padding: 1.5rem;">
                <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">What if I need help during my course?</h3>
                <p style="color: var(--text-light); line-height: 1.6;">
                    We offer multiple support channels including discussion forums, direct messaging with instructors, 
                    and our dedicated student support team available during business hours.
                </p>
            </div>
        </div>
    </div>
</section>

<script>
// Client-side validation for contact form
document.getElementById('contactForm').addEventListener('submit', function(e) {
    let isValid = true;
    const form = e.target;
    
    // Clear previous errors
    const errorMessages = form.querySelectorAll('.error-message');
    const fields = form.querySelectorAll('.form-control');
    
    errorMessages.forEach(error => error.classList.remove('show'));
    fields.forEach(field => field.classList.remove('error'));
    
    // Name validation
    const name = document.getElementById('name');
    if (!name.value.trim()) {
        showError('name', 'Name is required');
        isValid = false;
    }
    
    // Email validation
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        showError('email', 'Email is required');
        isValid = false;
    } else if (!emailRegex.test(email.value)) {
        showError('email', 'Please enter a valid email address');
        isValid = false;
    }
    
    // Subject validation
    const subject = document.getElementById('subject');
    if (!subject.value.trim()) {
        showError('subject', 'Subject is required');
        isValid = false;
    }
    
    // Message validation
    const message = document.getElementById('message');
    if (!message.value.trim()) {
        showError('message', 'Message is required');
        isValid = false;
    } else if (message.value.trim().length < 10) {
        showError('message', 'Message must be at least 10 characters long');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorElement = field.parentNode.querySelector('.error-message');
    
    field.classList.add('error');
    errorElement.textContent = message;
    errorElement.classList.add('show');
}
</script>

<?php require_once 'includes/footer.php'; ?>
