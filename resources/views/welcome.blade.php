@extends('layouts.portfolio')
@section('content') 
<div class="search-overlay"></div>
  <div class="search">
    <a href="#" class="search-close">
      <i class="md md-close"></i>
    </a>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <h4>Just Start Typing Text and Press Enter</h4>
        <form class="search-form">
          <input type="text" id="search" name="search" class="text-center" />
        </form>
      </div>
    </div>
  </div>
  <div class="slide-out-overlay"></div>
  <div class="slide-out">
    <header class="slide-out-header clearfix">
      <div class="clearfix">
        <a href="#" class="slide-out-close pull-left">
          <i class="md md-close"></i>
        </a>
        <a href="#" class="slide-out-share pull-right">
          <i class="md md-more-vert"></i>
        </a>
      </div>
      <div class="slide-out-popup">
        <nav class="social-nav">
          <ul class="list-unstyled">
            <li>
              <a href="https://www.facebook.com/dnirbhay" target="_blank">Facebook</a>
            </li>
            <li>
              <a href="www.twitter.com/ndhaked" target="_blank">Twitter</a>
            </li>
            <li>
              <a href="https://plus.google.com/u/0/115712836675253430474" target="_blank">Google+</a>
            </li>
            <li>
              <a href="www.linkedin.com/in/ndhaked" target="_blank">Linkedin</a>
            </li>
            <li>
              <a href="skype:live:718c6b5c940cd730">Skype</a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="image">
        <img src="images/dpsidebar.jpg" alt="alt text" class="img-responsive">
      </div>
      <div class="content">
        <h5>Nirbhay Dhaked</h5>
        <span>Senior Technology Lead</span>
      </div>
      <div class="text-right">
        <a href="public/Nirbhay Singh SR. Laravel Developer.pdf" download class="button link-button white icon-left">
          <i class="md md-file-download"></i>Download Resume </a>
        <br>
        <a href="download-area/riyaz.zip" class="button link-button white icon-left" style="display:none;">
          <i class="md md-file-download"></i>Download Android App </a>
      </div>
    </header>
    <div class="slide-out-widgets">
      <div class="slide-out-widget">
        <h4>Drink A Coffee With Me Today</h4>
        <form action="{{ route('contactusSubmit') }}" method="post" class="form-horizontal contact-form">
            <div class="form-group">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="contact-name" name="contact-name" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                    <input type="email" class="contact-email" name="contact-email" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Phone</label>
                <div class="col-sm-9">
                    <input type="text" class="contact-phone" name="contact_phone" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Message</label>
                <div class="col-sm-9">
                    <textarea name="contact-message" class="contact-message" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <button type="submit" class="button solid-button purple">Send Message</button>
                </div>
            </div>
            
            <div class="contact-loading alert alert-info form-alert" style="display:none;">
                <span class="message">Sending Request...</span>
                <button type="button" class="close" onclick="$(this).parent().hide();">×</button>
            </div>
            <div class="contact-success alert alert-success form-alert" style="display:none;">
                <span class="message">Success!</span>
                <button type="button" class="close" onclick="$(this).parent().hide();">×</button>
            </div>
            <div class="contact-error alert alert-danger form-alert" style="display:none;">
                <span class="message">Error!</span>
                <button type="button" class="close" onclick="$(this).parent().hide();">×</button>
            </div>
        </form>
      </div>
      <div class="slide-out-widget">
        <h4>Connect on Social Network</h4>
        <div class="instagram">
          <a class="social-icon" href="https://www.facebook.com/dnirbhay" target="_blank">
            <i class="fa fa-facebook"></i>
          </a>
          <a class="social-icon" href="https://twitter.com/ndhaked" target="_blank">
            <i class="fa fa-twitter"></i>
          </a>
          <a class="social-icon" href="https://plus.google.com/u/0/115712836675253430474" target="_blank">
            <i class="fa fa-google-plus"></i>
          </a>
          <a href="https://www.linkedin.com/in/ndhaked" class="social-icon" target="_blank">
            <i class="fa fa-linkedin" target="_blank"></i>
          </a>
          <a class="social-icon" href="skype:live:718c6b5c940cd730">
            <i class="fa fa-skype"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  <header class="header">
    <div class="top clearfix">
      <a href="#section7" class="available">
        <i class="ion-ios-email-outline"></i>
        <span>Connect to me</span>
      </a>
      <div class="right-icons">
        <a href="#" class="open-search header-open-search">
          <i class="md md-search" style="display:none;"></i>
        </a>
        <a href="public/Nirbhay Singh SR. Laravel Developer.pdf" download class="download">
          <i class="md md-file-download"></i>
        </a>
        <a href="#" class="share">
          <i class="md md-more-vert"></i>
        </a>
      </div>
      <div class="popup">
        <nav class="social-nav">
          <ul class="list-unstyled">
            <li>
              <a href="https://www.facebook.com/dnirbhay" target="_blank">Facebook</a>
            </li>
            <li>
              <a href="https://twitter.com/ndhaked" target="_blank">Twitter</a>
            </li>
            <li>
              <a href="https://plus.google.com/u/0/115712836675253430474" target="_blank">Google+</a>
            </li>
            <li>
              <a href="https://www.linkedin.com/in/ndhaked" target="_blank">Linkedin</a>
            </li>
            <li>
              <a href="skype:live:718c6b5c940cd730">Skype</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="bottom clearfix">
      <div class="title">
        <a href="{{URL::to('/')}}">Nirbhay Dhaked</a>
      </div>
      <div class="header-action-button-wrapper">
        <a href="#" class="header-action-button action-button">
          <i class="md md-add"></i>
        </a>
      </div>
      <a href="#" class="responsive-menu-open">Menu <i class="fa fa-bars"></i>
      </a>
      <nav class="main-nav">
        <ul class="list-unstyled">
          <li class="active">
            <a href="#section1">Home</a>
          </li>
          <li>
            <a href="#section2">About</a>
          </li>
          <li>
            <a href="#section3">Skill</a>
          </li>
          <li>
            <a href="#section4">Experience</a>
          </li>
          <li>
            <a href="#section5">Education</a>
          </li>
          <li>
            <a href="#section6">Work</a>
          </li>
          <li>
            <a href="#section7">Contact</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>
  <div class="responsive-menu">
    <a href="#" class="responsive-menu-close">Close <i class="ion-android-close"></i>
    </a>
    <nav class="responsive-nav"></nav>
  </div>
  <div class="sections">
    <div class="sections-wrapper clearfix">
      <section id="section1" class="no-padding-bottom active">
        <div class="container">
          <div class="row">
            <div class="col-sm-7 vertical-center padding-fix">
              <h1>Senior PHP Architect <sup>&</sup> Laravel <sup>Expert</sup>
              </h1>
              <p>I am a dedicated <strong>Senior Technology Lead & Laravel Expert</strong> with 12+ years of extensive experience. I don't just write code; I engineer robust, scalable web applications that drive business growth. From complex API ecosystems to high-traffic database architectures, my expertise lies in transforming raw requirements into efficient, secure, and maintainable software. Proficient in Agile methodologies and MySQL optimization, I help innovative companies build the future of their IT infrastructure. </p>
              <p class="button-row">
                <a href="public/Nirbhay Singh SR. Laravel Developer.pdf" download class="button solid-button purple">Download CV</a>
                <a href="download-area/riyaz.zip" class="button solid-button white" style="display:none;">Download Android App</a>
              </p>
            </div>
            <div class="col-sm-5 vertical-center">
                <div class="image"><img src="images/mydphome.png" alt="alt text" class="img-responsive"></div>
            </div>
          </div>
        </div>
      </section>
      <section id="section2">
        <div class="container">
          <h2>About Me</h2>
          <div class="row">
            <div class="col-sm-3">
        <img src="images/abtimgdp.png" alt="Nirbhay Dhaked" class="img-responsive section-img">
      </div>
            <div class="col-sm-9">
              <h3 class="small-margin-bottom">Nirbhay Dhaked</h3>
              <h5>Senior Technology Lead (Backend)</h5>
              <p>With over a decade of hands-on experience in the software industry, I specialize in building custom web applications that are as powerful as they are user-friendly. Currently serving as a <strong>Senior Laravel Developer & Technology Lead</strong>, my technical foundation is built on deep MySQL knowledge, API development, and system architecture, ensuring that every project I touch is optimized for performance and scalability. </p>
              <div class="signature"></div>
              <ul class="list-unstyled text-uppercase">
                <li>
                  <b>Date Of Birth:</b> 14 July 1991
                </li>
                <li>
                  <b>Phone:</b> +91 8209-99-0511
                </li>
                <li>
                  <b>Email:</b> nirbhaydhaked@gmail.com
                </li>
                <li>
                  <b>Address:</b> FN. 201, Sky Nation Project Patrakar Colony, Jaipur, Rajasthan (India)
                </li>
                <li>
                  <b>Website:</b> http://www.laravelexpert.in
                </li>
              </ul>
              <div class="spacer"></div>
              <h3>Core Competencies</h3>
              <div class="row">
                <div class="col-sm-4">
                  <div class="service">
                    <div class="icon">
                      <i class="ion-code-working"></i>
                    </div>
                    <h5>API Development</h5>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="service">
                    <div class="icon">
                      <i class="ion-ios-speedometer"></i>
                    </div>
                    <h5>Database Optimization</h5>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="service">
                    <div class="icon">
                      <i class="ion-cube"></i>
                    </div>
                    <h5>SaaS Architecture</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="section3">
        <div class="container">
          <h2>My Skills Values</h2>
          <div class="row">
            <div class="col-sm-6">
              <h4>Technical Skills</h4>
              <label class="progress-bar-label">Php</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                  <span>95%</span>
                </div>
              </div>
              <label class="progress-bar-label">Laravel Framework</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                  <span>95%</span>
                </div>
              </div>
              <label class="progress-bar-label">MySQL / Database Design</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                  <span>90%</span>
                </div>
              </div>
              <label class="progress-bar-label">REST API Development</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100">
                  <span>92%</span>
                </div>
              </div>
              <label class="progress-bar-label">jQuery / Ajax</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                  <span>85%</span>
                </div>
              </div>
              <label class="progress-bar-label">HTML5 / CSS3</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                  <span>80%</span>
                </div>
              </div>
              <label class="progress-bar-label">Server Management (Linux)</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                  <span>75%</span>
                </div>
              </div>
              <label class="progress-bar-label">Git / Version Control</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                  <span>85%</span>
                </div>
              </div>
              <label class="progress-bar-label">Redis / Caching</label>
              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                  <span>70%</span>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <h4>Core Competencies</h4>
              <div class="row">
                <div class="col-sm-6">
                  <ul class="list-icons purple bold-list">
                    <li>
                      <i class="md-arrow-forward"></i>Laravel (All Versions)
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>System Architecture
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Database Optimization
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Third-party Integrations
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Payment Gateways
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>SaaS Development
                    </li>
                  </ul>
                </div>
                <div class="col-sm-6">
                  <ul class="list-icons purple bold-list">
                    <li>
                      <i class="md-arrow-forward"></i>RESTful APIs
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Agile Methodology
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>CRM Solutions
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Bug Debugging
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Code Refactoring
                    </li>
                    <li>
                      <i class="md-arrow-forward"></i>Team Leadership
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="spacer"></div>
          <h4>Language Skills</h4>
          <div class="row">
            <div class="col-sm-6">
              <div class="circle-progress-wrapper clearfix">
                <div class="circle-progress">
                  <input type="text" class="dial" value="95" data-color="#2196f3" data-from="0" data-to="95" />
                </div>
                <div class="circle-progress-label-wrapper">
                  <label class="circle-progress-label">Hindi</label>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="circle-progress-wrapper clearfix">
                <div class="circle-progress">
                  <input type="text" class="dial" value="85" data-color="#2196f3" data-from="0" data-to="85" />
                </div>
                <div class="circle-progress-label-wrapper">
                  <label class="circle-progress-label">English (Professional)</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="section4">
        <div class="container">
          <h2>Professional Experience</h2>
          <div class="experience-timeline">
            <div class="experience-block clearfix">
              <div class="meta">
                <span class="year">Nov 2025 - Present</span>
                <span class="company">Mobiiworld</span>
              </div>
              <div class="content">
                <h5>Senior Laravel Developer & Tech Lead</h5>
                <p>Building scalable application architectures including modular structures, service layers, repositories, and queues. Responsible for designing and implementing RESTful APIs, authentication systems, and secure data flows. Optimizing application performance using query optimization, Redis caching, and load balancing techniques while ensuring system reliability and high availability in production.</p>
              </div>
              <div class="icon">
                <i class="ion-code"></i>
              </div>
              <div class="line"></div>
            </div>
            <div class="experience-block clearfix">
              <div class="meta">
                <span class="year">Jan 2025 - Nov 2025</span>
                <span class="company">Optima Tax Relief</span>
              </div>
              <div class="content">
                <h5>Software Engineer (Laravel Module Lead)</h5>
                <p>Led and managed the development of robust web applications using Laravel. Oversaw the architecture and deployment of modules, ensuring high performance and maintainability. Mentored a team of developers, conducted code reviews, and collaborated with stakeholders to deliver solutions that met critical business requirements. Focused on scalable architectures and automated testing.</p>
              </div>
              <div class="icon">
                <i class="ion-laptop"></i>
              </div>
              <div class="line"></div>
            </div>
            <div class="experience-block clearfix">
              <div class="meta">
                <span class="year">Dec 2017 - Nov 2024</span>
                <span class="company">Konstant Infosolutions Pvt Ltd</span>
              </div>
              <div class="content">
                <h5>Sr. Laravel Developer & Tech Lead</h5>
                <p>Served for 7 years, progressing from Senior Developer to Tech Lead. Specialized in API Development and complex Laravel frameworks. Led backend teams in delivering high-quality web solutions, optimizing database performance, and implementing advanced security protocols for international clients.</p>
              </div>
              <div class="icon">
                <i class="ion-trophy"></i>
              </div>
              <div class="line"></div>
            </div>
            <div class="experience-block clearfix">
              <div class="meta">
                <span class="year">Jun 2016 - Dec 2017</span>
                <span class="company">Arka Softwares</span>
              </div>
              <div class="content">
                <h5>Senior Software Engineer</h5>
                <p>Worked as a key member of the engineering team, developing efficient and affordable software solutions. Gained significant experience in handling full lifecycle software development projects and client communication.</p>
              </div>
              <div class="icon">
                <i class="ion-easel"></i>
              </div>
              <div class="line"></div>
            </div>
            <div class="experience-block clearfix">
              <div class="meta">
                <span class="year">Sep 2015 - Jun 2016</span>
                <span class="company">WDP Technologies Pvt. Ltd.</span>
              </div>
              <div class="content">
                <h5>PHP Developer</h5>
                <p>Joined as a Senior Developer focusing on the Laravel framework. Responsible for coding core modules and transitioning legacy PHP applications to modern MVC architecture.</p>
              </div>
              <div class="icon">
                <i class="ion-code-working"></i>
              </div>
              <div class="line"></div>
            </div>
            <div class="experience-block clearfix">
              <div class="meta">
                <span class="year">Sep 2014 - Sep 2015</span>
                <span class="company">eCare SofTech Pvt. Ltd.</span>
              </div>
              <div class="content">
                <h5>Web Programmer and Developer</h5>
                <p>Developed various industrial management software and dynamic websites. Built a strong foundation in core PHP, database management, and web application logic.</p>
              </div>
              <div class="icon">
                <i class="ion-monitor"></i>
              </div>
              <div class="line"></div>
            </div>
          </div>
        </div>
      </section>
      <section id="section5">
        <div class="container">
          <h2>Educational Journey</h2>
          <div class="education clearfix">
            <div class="item">
              <div class="marker"></div>
              <div class="content mca">
                <span>RTU, KOTA (R.I.E.T, JAIPUR)</span>
                <h4>MCA (Honours)</h4>
                <p>Advanced specialization in computer applications and backend systems architecture.</p>
              </div>
              <div class="year">Completed</div>
            </div>
            <div class="item">
              <div class="marker"></div>
              <div class="content bca">
                <span>UOR (DCTE, JAIPUR)</span>
                <h4>BCA</h4>
                <p>Comprehensive foundation in computer science and software development logic.</p>
              </div>
              <div class="year">Completed (60.22%)</div>
            </div>
            <div class="item">
              <div class="marker"></div>
              <div class="content th12">
                <span>RBSE, Ajmer (GOVT SR SEC HIGH SCH, BAYANA)</span>
                <h4>Senior Secondary (12th Grade)</h4>
              </div>
              <div class="year">Completed (60.15%)</div>
            </div>
            <div class="item">
              <div class="marker"></div>
              <div class="content th10">
                <span>RBSE, Ajmer (GOVT SR SC SCH, VIRAMPRA)</span>
                <h4>Secondary (10th Grade)</h4>
              </div>
              <div class="year">Completed (63.67%)</div>
            </div>
            <div class="line"></div>
          </div>
          <h2>Interests</h2>
          <div class="row">
            <div class="col-sm-3 col-xs-6">
              <div class="icon-box">
                <div class="icon">
                  <i class="ion-ios-monitor-outline"></i>
                </div>
                <h6>Tech Research</h6>
              </div>
            </div>
            <div class="col-sm-3 col-xs-6">
              <div class="icon-box">
                <div class="icon">
                  <i class="ion-code-working"></i>
                </div>
                <h6>Open Source</h6>
              </div>
            </div>
            <div class="col-sm-3 col-xs-6">
              <div class="icon-box">
                <div class="icon">
                  <i class="ion-plane"></i>
                </div>
                <h6>Travelling</h6>
              </div>
            </div>
            <div class="col-sm-3 col-xs-6">
              <div class="icon-box">
                <div class="icon">
                  <i class="ion-ios-book-outline"></i>
                </div>
                <h6>Reading</h6>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="section6">
        <div class="container">
          <h2>Projects & Applications</h2>
          <div class="wb-grid">
            <div class="wb-center">
              <div class="grid">
                <div id="js-filters-juicy-projects" class="cbp-l-filters-button">
                  <div data-filter="*" class="cbp-filter-item-active cbp-filter-item"> All Projects <div class="cbp-filter-counter"></div>
                  </div>
                  <div data-filter=".websites" class="cbp-filter-item"> Web Applications <div class="cbp-filter-counter"></div>
                  </div>
                </div>
                <div id="js-grid-juicy-projects" class="cbp">
                  <div class="cbp-item websites">
                    <div class="cbp-caption">
                      <div class="cbp-caption-defaultWrap">
                        <img src="images/web/small/1-small.jpg" alt="">
                      </div>
                      <div class="cbp-caption-activeWrap">
                        <div class="cbp-l-caption-alignCenter">
                          <div class="cbp-l-caption-body">
                            <a href="#" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="CRM Application">view details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="cbp-l-grid-projects-title">CRM System</div>
                    <div class="cbp-l-grid-projects-desc">Laravel / Vue.js</div>
                  </div>
                  <div class="cbp-item websites">
                    <div class="cbp-caption">
                      <div class="cbp-caption-defaultWrap">
                        <img src="images/web/small/2-small.jpg" alt="">
                      </div>
                      <div class="cbp-caption-activeWrap">
                        <div class="cbp-l-caption-alignCenter">
                          <div class="cbp-l-caption-body">
                            <a href="#" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="E-commerce Platform">view details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="cbp-l-grid-projects-title">E-commerce Platform</div>
                    <div class="cbp-l-grid-projects-desc">PHP / MySQL</div>
                  </div>
                  <div class="cbp-item websites">
                    <div class="cbp-caption">
                      <div class="cbp-caption-defaultWrap">
                        <img src="images/web/small/3-small.jpg" alt="">
                      </div>
                      <div class="cbp-caption-activeWrap">
                        <div class="cbp-l-caption-alignCenter">
                          <div class="cbp-l-caption-body">
                            <a href="#" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="Admin Dashboard">view details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="cbp-l-grid-projects-title">SaaS Dashboard</div>
                    <div class="cbp-l-grid-projects-desc">Laravel / API</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="section7">
        <div class="container">
          <h2>Get In Touch</h2>
          <div class="row">
            <div class="col-sm-5">
              <h5>Contact Details</h5>
              <ul class="list-icons list-unstyled">
                <li>
                  <i class="ion-ios-location-outline"></i>FN. 201 2nd Floor Sky Nation Project, <br>Patrakar Colony, Jaipur, <br>Rajasthan (India)
                </li>
                <li>
                  <i class="ion-iphone"></i>Phone: +91 8209-99-0511
                </li>
                <li>
                  <i class="ion-ios-email-outline"></i>Email: <a href="mailto:nirbhaydhaked@gmail.com">nirbhaydhaked@gmail.com</a>
                </li>
                <li>
                  <i class="ion-social-skype-outline "></i>Skype: <a href="skype:live:718c6b5c940cd730">live:718c6b5c940cd730</a>
                </li>
                <li>
                  <i class="ion-ios-home-outline"></i>Website: <a href="https://laravelexpert.in/">laravelexpert.in</a>
                </li>
              </ul>
              <div class="spacer"></div>
              <div class="social-icons">
                <a class="social-icon" href="https://www.facebook.com/dnirbhay" target="_blank">
                  <i class="fa fa-facebook"></i>
                </a>
                <a class="social-icon" href="https://twitter.com/ndhaked" target="_blank">
                  <i class="fa fa-twitter"></i>
                </a>
                <a class="social-icon" href="https://plus.google.com/u/0/115712836675253430474" target="_blank">
                  <i class="fa fa-google-plus"></i>
                </a>
                <a href="https://www.linkedin.com/in/ndhaked" class="social-icon" target="_blank">
                  <i class="fa fa-linkedin" target="_blank"></i>
                </a>
                <a class="social-icon" href="skype:live:718c6b5c940cd730">
                  <i class="fa fa-skype"></i>
                </a>
              </div>
              <div class="spacer"></div>
            </div>
            <div class="col-sm-7">
              <h5>Contact Form</h5>
              <form action="{{ route('contactusSubmit') }}" method="post" class="form-horizontal contact-form">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="contact-name" name="contact-name" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="contact-email" name="contact-email" />
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Phone</label>
                    <div class="col-sm-10">
                        <input type="text" class="contact-phone" name="contact_phone" />
                    </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Message</label>
                  <div class="col-sm-10">
                    <textarea name="contact-message" class="contact-message" rows="3"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" class="button solid-button purple">Send Message</button>
                  </div>
                </div>
                <div class="contact-loading alert alert-info form-alert">
                  <span class="message">Sending Request...</span>
                  <button type="button" class="close" data-hide="alert" aria-label="Close">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
                <div class="contact-success alert alert-success form-alert">
                  <span class="message">Success!</span>
                  <button type="button" class="close" data-hide="alert" aria-label="Close">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
                <div class="contact-error alert alert-danger form-alert">
                  <span class="message">Error!</span>
                  <button type="button" class="close" data-hide="alert" aria-label="Close">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3560.2666209407475!2d75.7324065752899!3d26.83147086357751!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db56819373c3b%3A0x5a19d8d201274576!2sSky%20Nation!5e0!3m2!1sen!2sin!4v1770454897446!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>
        </div>
      </section>
    </div>
  </div>
  <footer class="footer">
    <div class="top">
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <h4>Address</h4>
            <p>FN. 201 2nd Floor Sky Nation Project, <br>Patrakar Colony, Jaipur, <br>Rajasthan (India) </p>
          </div>
          <div class="col-sm-4">
            <h4>Connect</h4>
            <div class="social-icons">
              <a class="social-icon" href="https://www.facebook.com/dnirbhay" target="_blank">
                <i class="fa fa-facebook"></i>
              </a>
              <a class="social-icon" href="https://twitter.com/ndhaked" target="_blank">
                <i class="fa fa-twitter"></i>
              </a>
              <a class="social-icon" href="https://plus.google.com/u/0/115712836675253430474" target="_blank">
                <i class="fa fa-google-plus"></i>
              </a>
              <a href="https://www.linkedin.com/in/ndhaked" class="social-icon" target="_blank">
                <i class="fa fa-linkedin" target="_blank"></i>
              </a>
              <a class="social-icon" href="skype:live:718c6b5c940cd730">
                <i class="fa fa-skype"></i>
              </a>
            </div>
          </div>
          <div class="col-sm-4">
            <h4>Contact</h4>
            <p>Phone:- +91 8209-99-0511 <br />Email: nirbhaydhaked@gmail.com <br />Skype: live:718c6b5c940cd730 </p>
          </div>
          <div class="row" style="margin-top: 20px; padding-top: 15px; text-align: center;">
            <div class="col-sm-12">
              <a href="{{ url('/laravel-expert-in-jaipur') }}" style="color: rgba(255,255,255,0.8); margin: 0 12px; font-size: 13px; text-decoration: none;">Laravel Expert in Jaipur</a>
              <a href="{{ url('/hire-laravel-developer-in-jaipur') }}" style="color: rgba(255,255,255,0.8); margin: 0 12px; font-size: 13px; text-decoration: none;">Hire Laravel Developer in Jaipur</a>
              <a href="{{ url('/senior-laravel-developer-india') }}" style="color: rgba(255,255,255,0.8); margin: 0 12px; font-size: 13px; text-decoration: none;">Senior Laravel Developer India</a>
              <a href="{{ url('/laravel-api-development') }}" style="color: rgba(255,255,255,0.8); margin: 0 12px; font-size: 13px; text-decoration: none;">Laravel API Development</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bottom">Copyright © Nirbhay Dhaked. All Rights Reserved.</div>
  </footer>
@endsection
@section('uniquePageScript')
@endsection