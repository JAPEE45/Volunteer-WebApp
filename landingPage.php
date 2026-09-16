<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Philippine Red Cross - Volunteer Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
      overflow-x: hidden;
      background: #ffffff;
    }

    /* Navigation */
    nav {
      position: fixed;
      top: 0;
      width: 100%;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 20px rgba(220, 20, 60, 0.1);
      z-index: 1000;
      transition: all 0.3s ease;
    }

    nav.scrolled {
      box-shadow: 0 4px 30px rgba(220, 20, 60, 0.15);
    }

    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo-container img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 2px solid #dc143c;
    }

    .logo-text {
      font-size: 1.25rem;
      font-weight: 700;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .nav-links {
      display: flex;
      gap: 2rem;
      list-style: none;
    }

    .nav-links a {
      color: #2d3748;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-links a::after {
      content: "";
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      transition: width 0.3s ease;
    }

    .nav-links a:hover::after {
      width: 100%;
    }

    .nav-cta {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 700;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
      transition: all 0.3s ease;
    }

    .nav-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(220, 20, 60, 0.4);
    }

    /* Hero Section */
    .hero {
      min-height: 100vh;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 50%, #fff5f5 100%);
      display: flex;
      align-items: center;
      padding-top: 80px;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: "❤️";
      position: absolute;
      font-size: 40rem;
      opacity: 0.03;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      animation: pulse 4s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: translate(-50%, -50%) scale(1); }
      50% { transform: translate(-50%, -50%) scale(1.1); }
    }

    .hero-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 4rem 2rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
      position: relative;
    }

    .hero-content h1 {
      font-size: 3.5rem;
      font-weight: 800;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }

    .hero-content p {
      font-size: 1.25rem;
      color: #475569;
      margin-bottom: 2rem;
      line-height: 1.8;
    }

    .hero-buttons {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .btn-primary {
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      padding: 1rem 2.5rem;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 700;
      font-size: 1.1rem;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(220, 20, 60, 0.4);
    }

    .btn-secondary {
      background: white;
      color: #dc143c;
      padding: 1rem 2.5rem;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 700;
      font-size: 1.1rem;
      border: 2px solid #dc143c;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-secondary:hover {
      background: #dc143c;
      color: white;
      transform: translateY(-2px);
    }

    .hero-image {
      position: relative;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    .hero-illustration {
      width: 100%;
      height: auto;
      filter: drop-shadow(0 20px 40px rgba(220, 20, 60, 0.2));
    }

    /* Features Section */
    .features {
      padding: 6rem 2rem;
      background: white;
    }

    .features-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .section-title {
      text-align: center;
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1rem;
    }

    .section-subtitle {
      text-align: center;
      font-size: 1.2rem;
      color: #64748b;
      margin-bottom: 4rem;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }

    .feature-card {
      background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
      padding: 2.5rem;
      border-radius: 20px;
      border: 2px solid #fee;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(160, 0, 0, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(220, 20, 60, 0.15);
      border-color: #dc143c;
    }

    .feature-card:hover::before {
      opacity: 1;
    }

    .feature-icon {
      font-size: 3rem;
      margin-bottom: 1.5rem;
      position: relative;
    }

    .feature-card h3 {
      font-size: 1.5rem;
      color: #dc143c;
      margin-bottom: 1rem;
      font-weight: 700;
      position: relative;
    }

    .feature-card p {
      color: #475569;
      line-height: 1.8;
      font-size: 1rem;
      position: relative;
    }

    /* Stats Section */
    .stats {
      padding: 6rem 2rem;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      color: white;
      position: relative;
      overflow: hidden;
    }

    .stats::before {
      content: "🌟";
      position: absolute;
      font-size: 30rem;
      opacity: 0.05;
      top: 50%;
      right: -10%;
      transform: translateY(-50%);
    }

    .stats-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 3rem;
      position: relative;
    }

    .stat-card {
      text-align: center;
      padding: 2rem;
      border-radius: 20px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-5px);
    }

    .stat-number {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
      display: block;
    }

    .stat-label {
      font-size: 1.25rem;
      opacity: 0.9;
    }

    /* CTA Section */
    .cta-section {
      padding: 6rem 2rem;
      background: linear-gradient(135deg, #fff5f5 0%, #fee 100%);
    }

    .cta-container {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      background: white;
      padding: 4rem 3rem;
      border-radius: 30px;
      box-shadow: 0 20px 60px rgba(220, 20, 60, 0.15);
      border: 3px solid #fee;
    }

    .cta-container h2 {
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1.5rem;
    }

    .cta-container p {
      font-size: 1.25rem;
      color: #64748b;
      margin-bottom: 2.5rem;
      line-height: 1.8;
    }

    /* Footer */
    footer {
      background: linear-gradient(180deg, #1a1a1a 0%, #000000 100%);
      color: white;
      padding: 3rem 2rem 1.5rem;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 3rem;
      margin-bottom: 2rem;
    }

    .footer-section h4 {
      color: #dc143c;
      font-size: 1.25rem;
      margin-bottom: 1rem;
      font-weight: 700;
    }

    .footer-section p,
    .footer-section a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      line-height: 2;
      display: block;
      transition: color 0.3s ease;
    }

    .footer-section a:hover {
      color: #dc143c;
    }

    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 1rem;
    }

    .social-links a {
      width: 40px;
      height: 40px;
      background: rgba(220, 20, 60, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .social-links a:hover {
      background: #dc143c;
      transform: translateY(-3px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.6);
    }

    /* Mobile Menu */
    .mobile-menu-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #dc143c;
      cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 968px) {
      .hero-container {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .hero-content h1 {
        font-size: 2.5rem;
      }

      .hero-buttons {
        justify-content: center;
      }

      .hero-image {
        order: -1;
      }

      .nav-links {
        display: none;
      }

      .mobile-menu-toggle {
        display: block;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }

      .section-title {
        font-size: 2rem;
      }

      .cta-container {
        padding: 3rem 2rem;
      }

      .cta-container h2 {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .hero-content h1 {
        font-size: 2rem;
      }

      .hero-content p {
        font-size: 1rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
      }

      .stat-number {
        font-size: 2.5rem;
      }

      .footer-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav id="navbar">
    <div class="nav-container">
      <div class="logo-container">
        <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
        <span class="logo-text">Red Cross VMS</span>
      </div>
      <ul class="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
      <a href="login.php" class="nav-cta">Login</a>
      <button class="mobile-menu-toggle">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-container">
      <div class="hero-content">
        <h1>Empowering Volunteers, Saving Lives</h1>
        <p>A comprehensive volunteer management system for the Philippine Red Cross. Manage, track, and coordinate volunteer activities efficiently.</p>
        <div class="hero-buttons">
          <a href="login.php" class="btn-primary">
            Get Started <i class="fas fa-arrow-right"></i>
          </a>
          <a href="#features" class="btn-secondary">
            Learn More <i class="fas fa-chevron-down"></i>
          </a>
        </div>
      </div>
      <div class="hero-image">
        <svg class="hero-illustration" viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
          <!-- Medical Cross -->
          <rect x="220" y="120" width="60" height="200" fill="#dc143c" rx="10"/>
          <rect x="190" y="150" width="120" height="60" fill="#dc143c" rx="10"/>
          
          <!-- People silhouettes -->
          <circle cx="150" cy="280" r="25" fill="#475569"/>
          <path d="M 150 305 Q 140 305 135 320 L 135 360 L 165 360 L 165 320 Q 160 305 150 305" fill="#475569"/>
          
          <circle cx="350" cy="280" r="25" fill="#475569"/>
          <path d="M 350 305 Q 340 305 335 320 L 335 360 L 365 360 L 365 320 Q 360 305 350 305" fill="#475569"/>
          
          <!-- Heart -->
          <path d="M 250 180 C 240 165 220 165 210 180 C 200 195 210 210 250 240 C 290 210 300 195 290 180 C 280 165 260 165 250 180" fill="#fff" opacity="0.8"/>
        </svg>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="features-container">
      <h2 class="section-title">Powerful Features</h2>
      <p class="section-subtitle">Everything you need to manage volunteers effectively</p>
      
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">👥</div>
          <h3>Volunteer Management</h3>
          <p>Efficiently manage volunteer profiles, track their information, and monitor deployment status in real-time.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">📅</div>
          <h3>Event Coordination</h3>
          <p>Create, schedule, and manage events with location mapping and volunteer assignment capabilities.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">🗺️</div>
          <h3>Interactive Maps</h3>
          <p>Visualize volunteer locations and event sites with integrated mapping for better coordination.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">🔐</div>
          <h3>Account Security</h3>
          <p>Secure account management with role-based access control and approval workflows.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">📊</div>
          <h3>Analytics Dashboard</h3>
          <p>Comprehensive dashboard with charts and statistics for data-driven decision making.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">📱</div>
          <h3>Mobile Responsive</h3>
          <p>Access the system from any device with a fully responsive design that works everywhere.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="stats-container">
      <div class="stat-card">
        <span class="stat-number">1000+</span>
        <span class="stat-label">Active Volunteers</span>
      </div>
      <div class="stat-card">
        <span class="stat-number">50+</span>
        <span class="stat-label">Events Managed</span>
      </div>
      <div class="stat-card">
        <span class="stat-number">24/7</span>
        <span class="stat-label">System Availability</span>
      </div>
      <div class="stat-card">
        <span class="stat-number">100%</span>
        <span class="stat-label">Commitment</span>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section" id="about">
    <div class="cta-container">
      <h2>Ready to Make a Difference?</h2>
      <p>Join the Philippine Red Cross volunteer management system and be part of a community dedicated to saving lives and helping others.</p>
      <a href="login.php" class="btn-primary">
        Get Started Today <i class="fas fa-heart"></i>
      </a>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact">
    <div class="footer-container">
      <div class="footer-section">
        <h4>Philippine Red Cross</h4>
        <p>Volunteer Management System</p>
        <p>Empowering volunteers to save lives and serve communities across the Philippines.</p>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      
      <div class="footer-section">
        <h4>Quick Links</h4>
        <a href="#home">Home</a>
        <a href="#features">Features</a>
        <a href="#about">About Us</a>
        <a href="login.php">Login</a>
      </div>
      
      <div class="footer-section">
        <h4>Contact Info</h4>
        <p><i class="fas fa-map-marker-alt"></i> Masbate, Bicol Region, Philippines</p>
        <p><i class="fas fa-phone"></i> +63 XXX XXX XXXX</p>
        <p><i class="fas fa-envelope"></i> info@redcross.org.ph</p>
      </div>
      
      <div class="footer-section">
        <h4>Support</h4>
        <a href="#">Help Center</a>
        <a href="#">Documentation</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p>&copy; 2025 Philippine Red Cross. All rights reserved.</p>
    </div>
  </footer>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
</body>
</html>