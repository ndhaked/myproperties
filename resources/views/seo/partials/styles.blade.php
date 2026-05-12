<style>
  /* Override portfolio's section scrolling behavior */
  .seo-page-content {
    float: none !important;
    padding: 0 !important;
  }
  .seo-page-content section {
    width: 100% !important;
    float: none !important;
  }
  /* ── Hero ── */
  .seo-hero {
    background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 50%, #311b92 100%);
    color: #fff;
    padding: 90px 0 70px;
    position: relative;
    overflow: hidden;
  }
  .seo-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
    border-radius: 50%;
  }
  .seo-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
    border-radius: 50%;
  }
  .seo-hero h1 {
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    z-index: 1;
  }
  .seo-hero-sub {
    font-size: 17px;
    margin-bottom: 28px;
    opacity: 0.92;
    line-height: 1.7;
    position: relative;
    z-index: 1;
  }
  .seo-hero .solid-button {
    background: #fff;
    color: #7b1fa2;
    border: none;
    font-weight: 600;
    padding: 12px 36px;
    border-radius: 4px;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
  }
  .seo-hero .solid-button:hover {
    background: #f3e5f5;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  }
  .seo-hero .seo-badge {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    color: #fff;
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 20px;
    letter-spacing: 0.5px;
    position: relative;
    z-index: 1;
  }

  /* ── Sections ── */
  .seo-section {
    padding: 70px 0;
  }
  .seo-section h2 {
    margin-bottom: 12px;
    font-weight: 700;
    color: #333;
    font-size: 28px;
  }
  .seo-section .section-lead {
    font-size: 16px;
    color: #777;
    margin-bottom: 35px;
    max-width: 700px;
  }
  .seo-section-alt {
    background: #f8f9fa;
  }

  /* ── Feature Cards ── */
  .seo-feature-card {
    background: #fff;
    border-radius: 8px;
    padding: 30px 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    height: 100%;
  }
  .seo-feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(123,31,162,0.12);
    border-color: #e1bee7;
  }
  .seo-feature-card .card-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    font-size: 24px;
    color: #7b1fa2;
  }
  .seo-feature-card h5 {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    font-size: 16px;
  }
  .seo-feature-card p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
  }

  /* ── Stats ── */
  .seo-stat {
    padding: 30px 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease;
  }
  .seo-stat:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.08);
  }
  .seo-stat h3 {
    font-size: 38px;
    color: #fff;
    font-weight: 700;
    margin-bottom: 5px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
  }
  .seo-stat p {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin: 0;
  }
  .seo-stats-section {
    background: #0f172a; /* Deep tech blue base */
    background-image: 
      radial-gradient(at 0% 0%, rgba(74, 20, 140, 0.4) 0, transparent 50%), 
      radial-gradient(at 50% 0%, rgba(26, 35, 126, 0.4) 0, transparent 50%), 
      radial-gradient(at 100% 0%, rgba(74, 20, 140, 0.4) 0, transparent 50%), 
      radial-gradient(at 0% 100%, rgba(26, 35, 126, 0.4) 0, transparent 50%), 
      radial-gradient(at 100% 100%, rgba(74, 20, 140, 0.4) 0, transparent 50%),
      url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    position: relative;
    overflow: hidden;
    padding: 100px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }
  .seo-stats-section::after {
    content: "";
    position: absolute;
    top: 10%; left: 0; right: 0; bottom: 0;
    background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
    pointer-events: none;
  }

  /* ── CTA ── */
  .seo-cta {
    background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 50%, #311b92 100%);
    color: #fff;
    padding: 70px 0;
    position: relative;
    overflow: hidden;
  }
  .seo-cta::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -15%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
    border-radius: 50%;
  }
  .seo-cta h2 {
    color: #fff;
    font-size: 30px;
    position: relative;
    z-index: 1;
  }
  .seo-cta p {
    font-size: 17px;
    margin-bottom: 28px;
    opacity: 0.92;
    position: relative;
    z-index: 1;
  }
  .seo-cta .solid-button {
    background: #fff;
    color: #7b1fa2;
    font-weight: 600;
    padding: 12px 36px;
    border-radius: 4px;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
  }
  .seo-cta .solid-button:hover {
    background: #f3e5f5;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  }

  /* ── Tech Stack Pills ── */
  .tech-pill {
    display: inline-block;
    background: #f3e5f5;
    color: #7b1fa2;
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    margin: 4px 3px;
    transition: all 0.2s ease;
  }
  .tech-pill:hover {
    background: #7b1fa2;
    color: #fff;
  }

  /* ── Highlight Box ── */
  .seo-highlight-box {
    background: linear-gradient(135deg, #f3e5f5 0%, #fff 100%);
    border-left: 4px solid #7b1fa2;
    padding: 25px 30px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 20px;
  }
  .seo-highlight-box h4 {
    color: #7b1fa2;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 16px;
  }
  .seo-highlight-box p {
    color: #555;
    margin: 0;
    font-size: 14px;
    line-height: 1.6;
  }

  /* ── Process Steps ── */
  .process-step {
    text-align: center;
    padding: 20px 15px;
    position: relative;
  }
  .process-step .step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%);
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
  }
  .process-step h5 {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
  }
  .process-step p {
    color: #777;
    font-size: 13px;
    line-height: 1.5;
  }

  /* ── Testimonial / Quote ── */
  .seo-quote {
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    position: relative;
    margin-bottom: 20px;
  }
  .seo-quote::before {
    content: '\201C';
    font-size: 60px;
    color: #e1bee7;
    position: absolute;
    top: 10px;
    left: 20px;
    line-height: 1;
    font-family: Georgia, serif;
  }
  .seo-quote p {
    font-style: italic;
    color: #555;
    padding-left: 30px;
    font-size: 15px;
    line-height: 1.7;
    margin-bottom: 10px;
  }
  .seo-quote .quote-author {
    text-align: right;
    color: #7b1fa2;
    font-weight: 600;
    font-size: 13px;
  }

  /* ── Divider ── */
  .section-divider {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #7b1fa2, #e1bee7);
    margin-bottom: 25px;
    border-radius: 2px;
  }

  @media (max-width: 767px) {
    .seo-hero h1 { font-size: 28px; }
    .seo-hero-sub { font-size: 15px; }
    .seo-section { padding: 50px 0; }
    .seo-cta { padding: 50px 0; }
    .seo-feature-card { margin-bottom: 20px; }
  }
</style>
<script>
  $(document).ready(function() {
    $('.open-sidebar-form, .header-action-button').on('click', function(e) {
      e.preventDefault();
      var title = $(this).data('title');
      if (!title) {
        title = $(this).text().trim();
      }
      if (title) {
        $('#sidebar-form-title').text(title);
      }
      $('.slide-out-overlay').fadeIn(250);
      $('.slide-out').addClass('open');
    });
  });
</script>
