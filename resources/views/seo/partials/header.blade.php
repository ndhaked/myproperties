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
            <li><a href="https://www.facebook.com/dnirbhay" target="_blank">Facebook</a></li>
            <li><a href="https://twitter.com/ndhaked" target="_blank">Twitter</a></li>
            <li><a href="https://plus.google.com/u/0/115712836675253430474" target="_blank">Google+</a></li>
            <li><a href="https://www.linkedin.com/in/ndhaked" target="_blank">Linkedin</a></li>
            <li><a href="skype:live:718c6b5c940cd730">Skype</a></li>
          </ul>
        </nav>
      </div>
      <div class="image">
        <img src="{{ asset('images/dpsidebar.jpg') }}" alt="Nirbhay Dhaked" class="img-responsive">
      </div>
      <div class="content">
        <h5>Nirbhay Dhaked</h5>
        <span>Senior Technology Lead</span>
      </div>
      <div class="text-right">
        <a href="{{ asset('public/Nirbhay Singh SR. Laravel Developer.pdf') }}" download class="button link-button white icon-left">
          <i class="md md-file-download"></i>Download Resume</a>
      </div>
    </header>
    <div class="slide-out-widgets">
      <div class="slide-out-widget">
        <h4 id="sidebar-form-title">Drink A Coffee With Me Today</h4>
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
          <a class="social-icon" href="https://www.facebook.com/dnirbhay" target="_blank"><i class="fa fa-facebook"></i></a>
          <a class="social-icon" href="https://twitter.com/ndhaked" target="_blank"><i class="fa fa-twitter"></i></a>
          <a class="social-icon" href="https://plus.google.com/u/0/115712836675253430474" target="_blank"><i class="fa fa-google-plus"></i></a>
          <a href="https://www.linkedin.com/in/ndhaked" class="social-icon" target="_blank"><i class="fa fa-linkedin"></i></a>
          <a class="social-icon" href="skype:live:718c6b5c940cd730"><i class="fa fa-skype"></i></a>
        </div>
      </div>
    </div>
  </div>
  <header class="header">
    <div class="top clearfix">
      <a href="#" class="available open-sidebar-form">
        <i class="ion-ios-email-outline"></i>
        <span>Connect With Me</span>
      </a>
      <div class="right-icons">
        <a href="#" class="open-search header-open-search">
          <i class="md md-search" style="display:none;"></i>
        </a>
        <a href="{{ asset('public/Nirbhay Singh SR. Laravel Developer.pdf') }}" download class="download">
          <i class="md md-file-download"></i>
        </a>
        <a href="#" class="share">
          <i class="md md-more-vert"></i>
        </a>
      </div>
      <div class="popup">
        <nav class="social-nav">
          <ul class="list-unstyled">
            <li><a href="https://www.facebook.com/dnirbhay" target="_blank">Facebook</a></li>
            <li><a href="https://twitter.com/ndhaked" target="_blank">Twitter</a></li>
            <li><a href="https://plus.google.com/u/0/115712836675253430474" target="_blank">Google+</a></li>
            <li><a href="https://www.linkedin.com/in/ndhaked" target="_blank">Linkedin</a></li>
            <li><a href="skype:live:718c6b5c940cd730">Skype</a></li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="bottom clearfix">
      <div class="title">
        <a href="{{ url('/') }}">Nirbhay Dhaked</a>
      </div>
      <div class="header-action-button-wrapper">
          <a href="#" class="header-action-button action-button" data-title="Drink A Coffee With Me Today">
          <i class="md md-add"></i>
        </a>
      </div>
      <a href="#" class="responsive-menu-open">Menu <i class="fa fa-bars"></i>
      </a>
      <nav class="main-nav">
        <ul class="list-unstyled">
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('/') }}#section2">About</a></li>
          <li><a href="{{ url('/') }}#section3">Skill</a></li>
          <li><a href="{{ url('/') }}#section4">Experience</a></li>
          <li><a href="{{ url('/') }}#section5">Education</a></li>
          <li><a href="{{ url('/') }}#section6">Work</a></li>
          <li><a href="#" class="open-sidebar-form">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <div class="responsive-menu">
    <a href="#" class="responsive-menu-close">Close <i class="ion-android-close"></i>
    </a>
    <nav class="responsive-nav"></nav>
  </div>
