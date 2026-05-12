// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

// Helper function to check if element is in viewport
function isInViewport(element) {
  if (!element) return false;
  const rect = element.getBoundingClientRect();
  return (
    rect.top < window.innerHeight &&
    rect.bottom > 0 &&
    rect.left < window.innerWidth &&
    rect.right > 0
  );
}

// Helper function to set elements visible if in viewport
function setVisibleIfInViewport(elements) {
  if (!elements || elements.length === 0) return;
  elements.forEach((el) => {
    if (isInViewport(el)) {
      gsap.set(el, {
        opacity: 1,
        visibility: 'visible',
        clearProps: 'all'
      });
    }
  });
}


// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
  console.log('Animation loaded');
  
  // Set default animation properties
  gsap.defaults({
    ease: 'power3.out',
    duration: 1
  });

  // // Hero Section - Fade in on load
  // gsap.fromTo('#header', 
  //   { y: -20, opacity: 0 },
  //   { 
  //     y: 0, 
  //     opacity: 1, 
  //     duration: 0.8,
  //     ease: 'power2.out'
  //   }
  // );

  // What We Do Section - Fade in and slide up
  const whatWeDoSection = document.querySelector('.page-welcome-what-we-do');
  if (whatWeDoSection) {
    const isSectionVisible = isInViewport(whatWeDoSection);
    
    const whatWeDoTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: whatWeDoSection,
        start: 'top 90%',
        once: true
      }
    });

    // Animate decorative images
    const decorativeImages = whatWeDoSection.querySelectorAll('img');
    if (decorativeImages.length > 0) {
      setVisibleIfInViewport(decorativeImages);
      whatWeDoTimeline.fromTo(decorativeImages, {
        y: 50,
        opacity: isSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.2,
        duration: 0.8
      });
    }

    // Animate "ABOUT ADAI" label
    const aboutLabel = whatWeDoSection.querySelector('.space-y-1');
    if (aboutLabel) {
      if (isInViewport(aboutLabel)) {
        gsap.set(aboutLabel, { opacity: 1, visibility: 'visible', clearProps: 'all' });
      }
      whatWeDoTimeline.fromTo(aboutLabel, {
        x: -30,
        opacity: isSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        x: 0,
        opacity: 1,
        duration: 0.4
      }, '-=0.2');
    }

    // Animate heading
    const heading = whatWeDoSection.querySelector('h2');
    if (heading) {
      if (isInViewport(heading)) {
        gsap.set(heading, { opacity: 1, visibility: 'visible', clearProps: 'all' });
      }
      whatWeDoTimeline.fromTo(heading, {
        y: 30,
        opacity: isSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.8
      }, '-=0.2');
    }

    // Animate paragraphs
    const paragraphs = whatWeDoSection.querySelectorAll('p');
    if (paragraphs.length > 0) {
      setVisibleIfInViewport(paragraphs);
      whatWeDoTimeline.fromTo(paragraphs, {
        y: 20,
        opacity: isSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.15,
        duration: 0.7
      }, '-=0.5');
    }

    // Animate service links
    const serviceLinks = whatWeDoSection.querySelectorAll('a[href^="#"]');
    if (serviceLinks.length > 0) {
      setVisibleIfInViewport(serviceLinks);
      whatWeDoTimeline.fromTo(serviceLinks, {
        x: -20,
        opacity: isSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        x: 0,
        opacity: 1,
        stagger: 0.1,
        duration: 0.6
      }, '-=0.4');
    }

    // Animate bg-border divider lines
    const borderLines = whatWeDoSection.querySelectorAll('.bg-border');
    if (borderLines.length > 0) {
      setVisibleIfInViewport(borderLines);
      whatWeDoTimeline.fromTo(borderLines, {
        scaleX: 0,
        opacity: 1,
        immediateRender: false
      }, {
        scaleX: 1,
        opacity: 1,
        transformOrigin: 'left center',
        stagger: 0.15,
        duration: 0.8,
        ease: 'power2.out'
      }, '-=0.3');
    }
  }

  // 1. Declare at the top level so it is accessible everywhere
  let marketplaceSection = null;
  // Marketplace Section - Slide in from sides
  const marketplaceSectionEl = document.querySelector('.page-welcome-marketplace');
  if(marketplaceSectionEl){
       marketplaceSection = gsap.timeline({
        scrollTrigger: {
          trigger: marketplaceSectionEl,
          start: 'top 85%',
          once: true
        }
      });
  }

  // Set initial visibility for marketplace columns
  const marketplaceLeftCol = document.querySelector('#marketplace .grid > div:first-child');
  const marketplaceRightCol = document.querySelector('#marketplace .grid > div:last-child');
  const isMarketplaceVisible = marketplaceSectionEl ? isInViewport(marketplaceSectionEl) : false;
  
  if (marketplaceLeftCol) {
    if (isInViewport(marketplaceLeftCol)) {
      gsap.set(marketplaceLeftCol, {
        opacity: 1,
        visibility: 'visible',
        clearProps: 'all'
      });
    }
  }
  
  if (marketplaceRightCol) {
    if (isInViewport(marketplaceRightCol)) {
      gsap.set(marketplaceRightCol, {
        opacity: 1,
        visibility: 'visible',
        clearProps: 'all'
      });
    }
  }

  // Left column slides from left - only animate x position, keep opacity at 1
  if (marketplaceLeftCol) {
    if(marketplaceSection){
      marketplaceSection.fromTo(marketplaceLeftCol, {
        x: isMarketplaceVisible ? 0 : -100,
        opacity: 1,
        immediateRender: false
      }, {
        x: 0,
        opacity: 1,
        duration: 1
      });
    }
  }

  // Right column slides from right - only animate x position, keep opacity at 1
  if (marketplaceRightCol) {
    if(marketplaceSection){
      marketplaceSection.fromTo(marketplaceRightCol, {
        x: isMarketplaceVisible ? 0 : 100,
        opacity: 1,
        immediateRender: false
      }, {
        x: 0,
        opacity: 1,
        duration: 1
      }, '-=0.8');
    }
  }

  // Animate bg-border divider line in marketplace section
  const marketplaceBorder = document.querySelector('.page-welcome-marketplace .bg-border');
  if (marketplaceBorder) {
    // Set initial state - visible if in viewport
    if (isInViewport(marketplaceBorder)) {
      gsap.set(marketplaceBorder, {
        opacity: 1,
        visibility: 'visible',
        scaleX: 1,
        clearProps: 'all'
      });
    } else {
      gsap.set(marketplaceBorder, {
        opacity: 1,
        visibility: 'visible'
      });
    }
    if(marketplaceSection){
      marketplaceSection.fromTo(marketplaceBorder, {
        scaleX: isMarketplaceVisible ? 1 : 0,
        opacity: 1,
        immediateRender: false
      }, {
        scaleX: 1,
        opacity: 1,
        transformOrigin: 'left center',
        duration: 0.8,
        ease: 'power2.out'
      }, '-=0.5');
    }
  }

  // Animate artwork gallery - ensure it starts visible
  const artworkGallery = document.querySelector('#artwork-gallery');
  if (artworkGallery) {
    const isGalleryVisible = isInViewport(artworkGallery);
    // Set initial state - visible if in viewport
    if (isGalleryVisible) {
      gsap.set(artworkGallery, {
        opacity: 1,
        visibility: 'visible',
        y: 0,
        clearProps: 'all'
      });
    } else {
      gsap.set(artworkGallery, {
        opacity: 1,
        visibility: 'visible'
      });
    }
    
    gsap.fromTo(artworkGallery, {
      opacity: 1,
      y: isGalleryVisible ? 0 : 50,
      immediateRender: false
    }, {
      scrollTrigger: {
        trigger: artworkGallery,
        start: 'top 90%',
        once: true
      },
      opacity: 1,
      y: 0,
      duration: 1
    });
  }

   // 1. Declare at the top level so it is accessible everywhere
  let consultancySection = null;

  // Art Consultancy Section - Fade in with parallax effect
  const consultancySectionEl = document.querySelector('.page-welcome-consultancy');
  // 2. Check if the element exists
  if (consultancySectionEl) {
     consultancySection = gsap.timeline({
      scrollTrigger: {
        trigger: consultancySectionEl,
        start: 'top 80%',
        once: true
      }
    });
  }

  const isConsultancyVisible = consultancySectionEl ? isInViewport(consultancySectionEl) : false;

  const consultancyLabel = document.querySelector('.page-welcome-consultancy .space-y-1');
  if (consultancyLabel) {
    if (isInViewport(consultancyLabel)) {
      gsap.set(consultancyLabel, { opacity: 1, visibility: 'visible', clearProps: 'all' });
    }
    if(consultancySection){
      consultancySection.fromTo(consultancyLabel, {
        y: 30,
        opacity: isConsultancyVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.6
      });
    }
  }

  const consultancyHeading = document.querySelector('.page-welcome-consultancy h2');
  if (consultancyHeading) {
    if (isInViewport(consultancyHeading)) {
      gsap.set(consultancyHeading, { opacity: 1, visibility: 'visible', clearProps: 'all' });
    }
    if(consultancySection){
      consultancySection.fromTo(consultancyHeading, {
        y: 40,
        opacity: isConsultancyVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.8
      }, '-=0.4');
    }
  }

  const consultancyParagraphs = document.querySelectorAll('.page-welcome-consultancy p');
  if (consultancyParagraphs.length > 0) {
    setVisibleIfInViewport(consultancyParagraphs);
    if(consultancySection){
      consultancySection.fromTo(consultancyParagraphs, {
        y: 30,
        opacity: isConsultancyVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.2,
        duration: 0.7
      }, '-=0.5');
    }
  }

  const consultancyLinks = document.querySelectorAll('.page-welcome-consultancy a');
  if (consultancyLinks.length > 0) {
    setVisibleIfInViewport(consultancyLinks);
    if(consultancySection){
      consultancySection.fromTo(consultancyLinks, {
        y: 20,
        opacity: isConsultancyVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.1,
        duration: 0.6
      }, '-=0.4');
    }
  }

  // Animate bg-border divider line in consultancy section
  const consultancyBorder = document.querySelector('.page-welcome-consultancy .bg-border');
  if (consultancyBorder) {
    if (isInViewport(consultancyBorder)) {
      gsap.set(consultancyBorder, {
        opacity: 1,
        visibility: 'visible',
        scaleX: 1,
        clearProps: 'all'
      });
    } else {
      gsap.set(consultancyBorder, {
        opacity: 1,
        visibility: 'visible'
      });
    }
    if(consultancySection){
      consultancySection.fromTo(consultancyBorder, {
        scaleX: isConsultancyVisible ? 1 : 0,
        opacity: 1,
        immediateRender: false
      }, {
        scaleX: 1,
        opacity: 1,
        transformOrigin: 'left center',
        duration: 0.8,
        ease: 'power2.out'
      }, '-=0.6');
    }
  }

  // Parallax effect for background
  // 1. Select the element
  const consultancyBgGsap = document.querySelector('.page-welcome-consultancy');

  // 2. Only run if the element exists
  if (consultancyBgGsap) {
    gsap.to(consultancyBgGsap, {
      scrollTrigger: {
        trigger: consultancyBgGsap, // Use the element variable here
        start: 'top bottom',
        end: 'bottom top',
        scrub: 1
      },
      backgroundPosition: '50% 100%',
      ease: 'none'
    });
  }

  let conciergeSection  = null;
  // Cultural Concierge Section - Staggered animations
  const conciergeSectionEl = document.querySelector('.page-welcome-galleries');
  if(conciergeSectionEl){
     conciergeSection = gsap.timeline({
      scrollTrigger: {
        trigger: conciergeSectionEl,
        start: 'top 85%',
        once: true
      }
    });
  }

  const isConciergeVisible = conciergeSectionEl ? isInViewport(conciergeSectionEl) : false;

  // Set buttons to be visible initially before animation
  const conciergeButtons = document.querySelectorAll('.page-welcome-galleries button, #subscribe-concierge');
  if (conciergeButtons.length > 0) {
    conciergeButtons.forEach((button) => {
      if (isInViewport(button)) {
        gsap.set(button, {
          opacity: 1,
          visibility: 'visible',
          y: 0,
          clearProps: 'all'
        });
      } else {
        gsap.set(button, {
          opacity: 1,
          visibility: 'visible'
        });
      }
    });
  }

  // Animate section header
  const conciergeLabel = document.querySelector('.page-welcome-galleries .space-y-1');
  if (conciergeLabel) {
    if (isInViewport(conciergeLabel)) {
      gsap.set(conciergeLabel, { opacity: 1, visibility: 'visible', x: 0, clearProps: 'all' });
    }
    conciergeSection.fromTo(conciergeLabel, {
      x: -30,
      opacity: isConciergeVisible ? 1 : 0,
      immediateRender: false
    }, {
      x: 0,
      opacity: 1,
      duration: 0.6
    });
  }

  const conciergeHeading = document.querySelector('.page-welcome-galleries h2');
  if (conciergeHeading) {
    if (isInViewport(conciergeHeading)) {
      gsap.set(conciergeHeading, { opacity: 1, visibility: 'visible', y: 0, clearProps: 'all' });
    }
    conciergeSection.fromTo(conciergeHeading, {
      y: 30,
      opacity: isConciergeVisible ? 1 : 0,
      immediateRender: false
    }, {
      y: 0,
      opacity: 1,
      duration: 0.8
    }, '-=0.4');
  }

  const conciergeParagraph = document.querySelector('.page-welcome-galleries p');
  if (conciergeParagraph) {
    if (isInViewport(conciergeParagraph)) {
      gsap.set(conciergeParagraph, { opacity: 1, visibility: 'visible', y: 0, clearProps: 'all' });
    }
    conciergeSection.fromTo(conciergeParagraph, {
      y: 20,
      opacity: isConciergeVisible ? 1 : 0,
      immediateRender: false
    }, {
      y: 0,
      opacity: 1,
      duration: 0.7
    }, '-=0.5');
  }

  // Animate buttons - only animate y position, keep opacity at 1
  if (conciergeButtons.length > 0) {
    conciergeButtons.forEach((button) => {
      conciergeSection.fromTo(button, {
        y: isConciergeVisible ? 0 : 20,
        opacity: 1,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.6
      }, '-=0.4');
    });
  }

  // Animate geometric SVG
  const conciergeSvg = document.querySelector('.page-welcome-galleries svg');
  if (conciergeSvg) {
    const isSvgVisible = isInViewport(conciergeSvg);
    if (isSvgVisible) {
      gsap.set(conciergeSvg, {
        opacity: 1,
        visibility: 'visible',
        scale: 1,
        clearProps: 'all'
      });
    } else {
      gsap.set(conciergeSvg, {
        opacity: 1,
        visibility: 'visible'
      });
    }
    gsap.fromTo(conciergeSvg, {
      scale: isSvgVisible ? 1 : 0.8,
      opacity: isSvgVisible ? 1 : 0,
      immediateRender: false
    }, {
      scrollTrigger: {
        trigger: conciergeSvg,
        start: 'top 90%',
        once: true
      },
      scale: 1,
      opacity: 1,
      duration: 1.5,
      ease: 'power2.out'
    });
  }

  // Animate images with stagger
  const conciergeImages = document.querySelectorAll('.page-welcome-galleries .animate-image-slide');
  if (conciergeImages.length > 0) {
    const triggerEl = document.querySelector('.page-welcome-galleries .flex.items-end');
    const isImagesVisible = triggerEl ? isInViewport(triggerEl) : false;
    setVisibleIfInViewport(conciergeImages);
    gsap.fromTo(conciergeImages, {
      y: isImagesVisible ? 0 : 100,
      opacity: isImagesVisible ? 1 : 0,
      immediateRender: false
    }, {
      scrollTrigger: {
        trigger: triggerEl || conciergeImages[0],
        start: 'top 90%',
        once: true
      },
      y: 0,
      opacity: 1,
      stagger: 0.2,
      duration: 1,
      ease: 'power3.out'
    });
  }

  // Smooth scroll behavior enhancement
  gsap.to('html', {
    scrollBehavior: 'smooth',
    duration: 0
  });

  // Refresh ScrollTrigger on window resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      ScrollTrigger.refresh();
    }, 250);
  });

  // Refresh ScrollTrigger after images load
  window.addEventListener('load', function() {
    ScrollTrigger.refresh();
  });

  // ============================================
  // ANIMATIONS FOR ALL FRONT PAGES
  // ============================================

  // Hero Section Animation (for all front pages)
  const heroSections = document.querySelectorAll('.page-about-hero, .page-consultancy-hero, .page-marketplace-hero, .page-galleries-hero, .page-register-hero');
  heroSections.forEach((heroSection) => {
    const isHeroVisible = isInViewport(heroSection);
    const heroTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: heroSection,
        start: 'top 80%',
        once: true
      }
    });

    // Animate hero title
    const heroTitle = heroSection.querySelector('h1');
    if (heroTitle) {
      if (isInViewport(heroTitle)) {
        gsap.set(heroTitle, { opacity: 1, visibility: 'visible', y: 0, clearProps: 'all' });
      }
      heroTimeline.fromTo(heroTitle, {
        y: 50,
        opacity: isHeroVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: 'power3.out'
      });
    }

    // Animate hero subtitle/description
    const heroSubtitle = heroSection.querySelector('p');
    if (heroSubtitle) {
      if (isInViewport(heroSubtitle)) {
        gsap.set(heroSubtitle, { opacity: 1, visibility: 'visible', y: 0, clearProps: 'all' });
      }
      heroTimeline.fromTo(heroSubtitle, {
        y: 30,
        opacity: isHeroVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.7
      }, '-=0.4');
    }

    // Animate hero badges/labels
    const heroBadges = heroSection.querySelectorAll('span.rounded-full');
    if (heroBadges.length > 0) {
      setVisibleIfInViewport(heroBadges);
      heroTimeline.fromTo(heroBadges, {
        scale: isHeroVisible ? 1 : 0.8,
        opacity: isHeroVisible ? 1 : 0,
        immediateRender: false
      }, {
        scale: 1,
        opacity: 1,
        stagger: 0.1,
        duration: 0.6
      }, '-=0.3');
    }
  });

  // Overview Section Animation (two-column layout)
  const overviewSections = document.querySelectorAll('.page-about-overview, .page-consultancy-overview, .page-marketplace-overview, .page-galleries-overview');
  overviewSections.forEach((section, index) => {
    const isOverviewVisible = isInViewport(section);
    const overviewTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        once: true
      }
    });

    // Animate section indicator (left column with border)
    const sectionIndicator = section.querySelector('.lg\\:col-span-4');
    if (sectionIndicator) {
      const borderLine = sectionIndicator.querySelector('.bg-foreground');
      const label = sectionIndicator.querySelector('h2');
      
      if (borderLine) {
        // Set initial state - visible if in viewport
        if (isInViewport(borderLine)) {
          gsap.set(borderLine, {
            opacity: 1,
            visibility: 'visible',
            scaleX: 1,
            clearProps: 'all'
          });
        } else {
          gsap.set(borderLine, {
            opacity: 1,
            visibility: 'visible'
          });
        }
        
        overviewTimeline.fromTo(borderLine, {
          scaleX: isOverviewVisible ? 1 : 0,
          opacity: 1,
          immediateRender: false
        }, {
          scaleX: 1,
          opacity: 1,
          transformOrigin: 'left center',
          duration: 0.8,
          ease: 'power2.out'
        });
      }
      
      if (label) {
        if (isInViewport(label)) {
          gsap.set(label, { opacity: 1, visibility: 'visible', x: 0, clearProps: 'all' });
        }
        overviewTimeline.fromTo(label, {
          x: -30,
          opacity: isOverviewVisible ? 1 : 0,
          immediateRender: false
        }, {
          x: 0,
          opacity: 1,
          duration: 0.6
        }, '-=0.4');
      }
    }

    // Animate main content (right column)
    const mainContent = section.querySelector('.lg\\:col-span-8');
    if (mainContent) {
      const headings = mainContent.querySelectorAll('h2, h3');
      const paragraphs = mainContent.querySelectorAll('p');
      
      if (headings.length > 0) {
        setVisibleIfInViewport(headings);
        overviewTimeline.fromTo(headings, {
          y: 30,
          opacity: isOverviewVisible ? 1 : 0,
          immediateRender: false
        }, {
          y: 0,
          opacity: 1,
          stagger: 0.15,
          duration: 0.7
        }, '-=0.3');
      }
      
      if (paragraphs.length > 0) {
        setVisibleIfInViewport(paragraphs);
        overviewTimeline.fromTo(paragraphs, {
          y: 20,
          opacity: isOverviewVisible ? 1 : 0,
          immediateRender: false
        }, {
          y: 0,
          opacity: 1,
          stagger: 0.1,
          duration: 0.6
        }, '-=0.4');
      }
    }
  });

  // Section with Images Animation
  const imageSections = document.querySelectorAll('.page-about-empowering, .page-consultancy-services, .page-marketplace-features, .page-galleries-features');
  imageSections.forEach((section) => {
    const isImageSectionVisible = isInViewport(section);
    const imageTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        once: true
      }
    });

    // Animate images
    const images = section.querySelectorAll('img');
    if (images.length > 0) {
      setVisibleIfInViewport(images);
      imageTimeline.fromTo(images, {
        scale: isImageSectionVisible ? 1 : 0.95,
        opacity: isImageSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        scale: 1,
        opacity: 1,
        stagger: 0.2,
        duration: 0.8,
        ease: 'power2.out'
      });
    }

    // Animate headings
    const headings = section.querySelectorAll('h2, h3');
    if (headings.length > 0) {
      setVisibleIfInViewport(headings);
      imageTimeline.fromTo(headings, {
        y: 30,
        opacity: isImageSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.15,
        duration: 0.7
      }, '-=0.5');
    }

    // Animate paragraphs
    const paragraphs = section.querySelectorAll('p');
    if (paragraphs.length > 0) {
      setVisibleIfInViewport(paragraphs);
      imageTimeline.fromTo(paragraphs, {
        y: 20,
        opacity: isImageSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.1,
        duration: 0.6
      }, '-=0.4');
    }
  });

  // Grid Sections Animation (services, features, etc.)
  const gridSections = document.querySelectorAll('.page-about-services .grid, .page-marketplace-database .grid, .page-galleries-why .grid, .page-register-why .grid');
  gridSections.forEach((grid) => {
    const gridItems = grid.querySelectorAll('.grid > div, .grid > article');
    if (gridItems.length > 0) {
      const isGridVisible = isInViewport(grid);
      setVisibleIfInViewport(gridItems);
      gsap.fromTo(gridItems, {
        y: isGridVisible ? 0 : 50,
        opacity: isGridVisible ? 1 : 0,
        immediateRender: false
      }, {
        scrollTrigger: {
          trigger: grid,
          start: 'top 85%',
          once: true
        },
        y: 0,
        opacity: 1,
        stagger: 0.15,
        duration: 0.8,
        ease: 'power2.out'
      });
    }
  });

  // bg-border Animation (for all pages, excluding art-consultancy alternating sections)
  const allBorderLines = document.querySelectorAll('.bg-border');
  if (allBorderLines.length > 0) {
    // Group borders by their parent section, excluding art-consultancy alternating sections
    const borderGroups = new Map();
    allBorderLines.forEach((border) => {
      const section = border.closest('section');
      if (section) {
        // Skip borders in art-consultancy alternating sections (they're handled separately)
        if (section.classList.contains('page-consultancy-private-collection') ||
            section.classList.contains('page-consultancy-bespoke') ||
            section.classList.contains('page-consultancy-philanthropy')) {
          return;
        }
        
        if (!borderGroups.has(section)) {
          borderGroups.set(section, []);
        }
        borderGroups.get(section).push(border);
      }
    });

    borderGroups.forEach((borders, section) => {
      const isSectionVisible = isInViewport(section);
      setVisibleIfInViewport(borders);
      gsap.fromTo(borders, {
        scaleX: isSectionVisible ? 1 : 0,
        opacity: 1,
        immediateRender: false
      }, {
        scrollTrigger: {
          trigger: section,
          start: 'top 85%',
          once: true
        },
        scaleX: 1,
        opacity: 1,
        transformOrigin: 'left center',
        stagger: 0.15,
        duration: 0.8,
        ease: 'power2.out'
      });
    });
  }

  // bg-foreground border lines (section indicators)
  const foregroundBorders = document.querySelectorAll('.bg-foreground.h-px');
  if (foregroundBorders.length > 0) {
    foregroundBorders.forEach((border) => {
      const section = border.closest('section');
      if (section) {
        const isSectionVisible = isInViewport(section);
        // Set initial state - visible if in viewport
        if (isInViewport(border)) {
          gsap.set(border, {
            opacity: 1,
            visibility: 'visible',
            scaleX: 1,
            clearProps: 'all'
          });
        } else {
          gsap.set(border, {
            opacity: 1,
            visibility: 'visible'
          });
        }
        
        gsap.fromTo(border, {
          scaleX: isSectionVisible ? 1 : 0,
          opacity: 1,
          immediateRender: false
        }, {
          scrollTrigger: {
            trigger: section,
            start: 'top 85%',
            once: true
          },
          scaleX: 1,
          opacity: 1,
          transformOrigin: 'left center',
          duration: 0.8,
          ease: 'power2.out'
        });
      }
    });
  }

  // CTA Section Animation
  const ctaSections = document.querySelectorAll('.page-about-cta, .page-consultancy-cta, .page-marketplace-cta, .page-galleries-cta');
  ctaSections.forEach((section) => {
    const isCtaVisible = isInViewport(section);
    const ctaTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        once: true
      }
    });

    // Animate CTA border
    const ctaBorder = section.querySelector('.bg-background.h-px, .bg-foreground.h-px');
    if (ctaBorder) {
      // Set initial state - visible if in viewport
      if (isInViewport(ctaBorder)) {
        gsap.set(ctaBorder, {
          opacity: 1,
          visibility: 'visible',
          scaleX: 1,
          clearProps: 'all'
        });
      } else {
        gsap.set(ctaBorder, {
          opacity: 1,
          visibility: 'visible'
        });
      }
      
      ctaTimeline.fromTo(ctaBorder, {
        scaleX: isCtaVisible ? 1 : 0,
        opacity: 1,
        immediateRender: false
      }, {
        scaleX: 1,
        opacity: 1,
        transformOrigin: 'left center',
        duration: 0.8,
        ease: 'power2.out'
      });
    }

    // Animate CTA label
    const ctaLabel = section.querySelector('p.uppercase');
    if (ctaLabel) {
      if (isInViewport(ctaLabel)) {
        gsap.set(ctaLabel, { opacity: 1, visibility: 'visible', y: 0, clearProps: 'all' });
      }
      ctaTimeline.fromTo(ctaLabel, {
        y: 20,
        opacity: isCtaVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.6
      }, '-=0.4');
    }

    // Animate CTA heading
    const ctaHeading = section.querySelector('h2');
    if (ctaHeading) {
      if (isInViewport(ctaHeading)) {
        gsap.set(ctaHeading, { opacity: 1, visibility: 'visible', y: 0, clearProps: 'all' });
      }
      ctaTimeline.fromTo(ctaHeading, {
        y: 30,
        opacity: isCtaVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        duration: 0.8
      }, '-=0.3');
    }

    // Animate CTA button
    const ctaButton = section.querySelector('a, button');
    if (ctaButton) {
      // Ensure button is visible and set initial state
      if (isInViewport(ctaButton)) {
        gsap.set(ctaButton, {
          opacity: 1,
          visibility: 'visible',
          y: 0,
          clearProps: 'all'
        });
      } else {
        gsap.set(ctaButton, { 
          opacity: 1, 
          visibility: 'visible', 
          display: 'inline-flex',
          scale: 1
        });
      }
      ctaTimeline.fromTo(ctaButton, {
        scale: isCtaVisible ? 1 : 0.9,
        opacity: isCtaVisible ? 1 : 0,
        immediateRender: false
      }, {
        scale: 1,
        opacity: 1,
        duration: 0.6,
        ease: 'power2.out'
      }, '-=0.4');
    }
  });

  // Alternating image/content sections (art-consultancy page)
  const alternatingSections = document.querySelectorAll('.page-consultancy-private-collection, .page-consultancy-bespoke, .page-consultancy-philanthropy');
  alternatingSections.forEach((section, index) => {
    const isAltSectionVisible = isInViewport(section);
    const altTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        once: true
      }
    });

    const imageCol = section.querySelector('.order-1');
    const contentCol = section.querySelector('.order-2');

    if (imageCol) {
      if (isInViewport(imageCol)) {
        gsap.set(imageCol, { opacity: 1, visibility: 'visible', x: 0, clearProps: 'all' });
      }
      altTimeline.fromTo(imageCol, {
        x: index % 2 === 0 ? -50 : 50,
        opacity: isAltSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        x: 0,
        opacity: 1,
        duration: 0.8
      });
    }

    if (contentCol) {
      if (isInViewport(contentCol)) {
        gsap.set(contentCol, { opacity: 1, visibility: 'visible', x: 0, clearProps: 'all' });
      }
      altTimeline.fromTo(contentCol, {
        x: index % 2 === 0 ? 50 : -50,
        opacity: isAltSectionVisible ? 1 : 0,
        immediateRender: false
      }, {
        x: 0,
        opacity: 1,
        duration: 0.8
      }, '-=0.6');
    }

    // Animate content elements
    if (contentCol) {
      const headings = contentCol.querySelectorAll('h3, h4');
      const paragraphs = contentCol.querySelectorAll('p');
      const borders = contentCol.querySelectorAll('.bg-border');

      if (headings.length > 0) {
        setVisibleIfInViewport(headings);
        altTimeline.fromTo(headings, {
          y: 20,
          opacity: isAltSectionVisible ? 1 : 0,
          immediateRender: false
        }, {
          y: 0,
          opacity: 1,
          stagger: 0.1,
          duration: 0.6
        }, '-=0.4');
      }

      if (paragraphs.length > 0) {
        setVisibleIfInViewport(paragraphs);
        altTimeline.fromTo(paragraphs, {
          y: 15,
          opacity: isAltSectionVisible ? 1 : 0,
          immediateRender: false
        }, {
          y: 0,
          opacity: 1,
          stagger: 0.08,
          duration: 0.5
        }, '-=0.3');
      }

      // Animate bg-border lines with proper timing
      if (borders.length > 0) {
        setVisibleIfInViewport(borders);
        altTimeline.fromTo(borders, {
          scaleX: isAltSectionVisible ? 1 : 0,
          opacity: 1,
          immediateRender: false
        }, {
          scaleX: 1,
          opacity: 1,
          transformOrigin: 'left center',
          stagger: 0.2,
          duration: 0.8,
          ease: 'power2.out'
        }, '-=0.4');
      }
    }
  });

  // Flex-row sections (marketplace features, gallery features)
  const flexRowSections = document.querySelectorAll('.page-marketplace-features, .page-galleries-features');
  flexRowSections.forEach((section) => {
    const isFlexSectionVisible = isInViewport(section);
    const flexTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        once: true
      }
    });

    const items = section.querySelectorAll('.flex.flex-col.lg\\:flex-row > div');
    if (items.length > 0) {
      setVisibleIfInViewport(items);
      items.forEach((item, index) => {
        const isReversed = item.closest('.lg\\:flex-row-reverse');
        flexTimeline.fromTo(item, {
          x: isFlexSectionVisible ? 0 : (isReversed ? 50 : -50),
          opacity: isFlexSectionVisible ? 1 : 0,
          immediateRender: false
        }, {
          x: 0,
          opacity: 1,
          duration: 0.8
        }, index === 0 ? 0 : '-=0.6');
      });
    }
  });

  // Icon grid sections (database features)
  const iconGrids = document.querySelectorAll('.page-marketplace-database .grid');
  iconGrids.forEach((grid) => {
    const items = grid.querySelectorAll(':scope > div');
    if (items.length > 0) {
      const isGridVisible = isInViewport(grid);
      setVisibleIfInViewport(items);
      gsap.fromTo(items, {
        y: isGridVisible ? 0 : 30,
        opacity: isGridVisible ? 1 : 0,
        immediateRender: false
      }, {
        scrollTrigger: {
          trigger: grid,
          start: 'top 85%',
          once: true
        },
        y: 0,
        opacity: 1,
        stagger: 0.1,
        duration: 0.7,
        ease: 'power2.out'
      });
    }
  });

  // Stats section animation
  const statsSections = document.querySelectorAll('.page-galleries-stats');
  statsSections.forEach((section) => {
    const statsItems = section.querySelectorAll('.grid > div');
    if (statsItems.length > 0) {
      const isStatsVisible = isInViewport(section);
      setVisibleIfInViewport(statsItems);
      gsap.fromTo(statsItems, {
        scale: isStatsVisible ? 1 : 0.9,
        opacity: isStatsVisible ? 1 : 0,
        immediateRender: false
      }, {
        scrollTrigger: {
          trigger: section,
          start: 'top 85%',
          once: true
        },
        scale: 1,
        opacity: 1,
        stagger: 0.15,
        duration: 0.8,
        ease: 'back.out(1.7)'
      });
    }
  });

  // Contact form sections (excluding register-interest form elements)
  const contactSections = document.querySelectorAll('.page-contact-main');
  contactSections.forEach((section) => {
    const isContactVisible = isInViewport(section);
    const formTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        once: true
      }
    });

    const formElements = section.querySelectorAll('input, textarea, button, select');
    if (formElements.length > 0) {
      setVisibleIfInViewport(formElements);
      formTimeline.fromTo(formElements, {
        y: 20,
        opacity: isContactVisible ? 1 : 0,
        immediateRender: false
      }, {
        y: 0,
        opacity: 1,
        stagger: 0.05,
        duration: 0.5
      });
    }
  });

  // ============================================
  // ANIMATIONS FOR AUTH PAGES
  // ============================================

  // Auth form parent elements slide up animation
  const authFormParents = document.querySelectorAll(
    '.auth-login-card, .auth-forgot-password-card, .auth-reset-password-card, ' +
    '.auth-verify-email-card, .auth-verify-otp-card, .auth-password-updated-card, ' +
    '.auth-confirm-password-wrapper, .auth-register-wrapper, .auth-register-interest-form'
  );
  
  authFormParents.forEach((formParent) => {
    const isFormVisible = isInViewport(formParent);
    if (isFormVisible) {
      gsap.set(formParent, {
        opacity: 1,
        visibility: 'visible',
        y: 0,
        clearProps: 'all'
      });
    } else {
      gsap.set(formParent, {
        opacity: 1,
        visibility: 'visible'
      });
    }
    gsap.fromTo(formParent, {
      y: isFormVisible ? 0 : 50,
      opacity: isFormVisible ? 1 : 0,
      immediateRender: false
    }, {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power3.out',
      delay: 0.2
    });
  });


});