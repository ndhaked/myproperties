<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    	<meta charset="utf-8">
    	<meta name="csrf-token" content="{{ csrf_token() }}">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="@yield('meta_description', 'Looking for a Laravel Expert in Jaipur? Nirbhay Singh is a Senior Laravel Developer with 12+ years of experience building scalable SaaS, REST APIs and enterprise web applications.')" />
	    <meta name="keywords" content="@yield('meta_keywords', 'Laravel Expert, Laravel Developer, Jaipur, PHP Developer, Senior Developer, REST API, SaaS, Web Application, Nirbhay Dhaked')" />
	    <meta name="author" content="Nirbhay Dhaked" />
	    <meta name="robots" content="index, follow" />
	    <title>@yield('title', 'Laravel Expert in Jaipur | Nirbhay Singh – Senior Laravel Developer')</title>

	    <!-- Canonical -->
			<link rel="canonical" href="@yield('canonical', 'https://laravelexpert.in/')" />

			<!-- Open Graph (Facebook, LinkedIn) -->
			<meta property="og:title" content="@yield('title', 'Laravel Expert in Jaipur | Nirbhay Singh')" />
			<meta property="og:description" content="@yield('meta_description', 'Senior Laravel Developer with 12+ years experience in SaaS, API development and backend architecture.')" />
			<meta property="og:url" content="@yield('canonical', 'https://laravelexpert.in/')" />
			<meta property="og:type" content="website" />
			<meta property="og:site_name" content="Nirbhay Dhaked – Laravel Expert" />
			<meta property="og:locale" content="en_US" />

			<!-- Twitter Card -->
			<meta name="twitter:card" content="summary" />
			<meta name="twitter:title" content="@yield('title', 'Laravel Expert in Jaipur | Nirbhay Singh')" />
			<meta name="twitter:description" content="@yield('meta_description', 'Senior Laravel Developer with 12+ years experience.')" />

			<!-- JSON-LD Structured Data -->
			@hasSection('structured_data')
				@yield('structured_data')
			@else
			<script type="application/ld+json">
			{
			  "@@context": "https://schema.org",
			  "@@type": "ProfessionalService",
			  "name": "Laravel Expert in Jaipur",
			  "url": "https://laravelexpert.in",
			  "description": "Senior Laravel Developer with 12+ years experience in SaaS, APIs and scalable web applications.",
			  "address": {
			    "@@type": "PostalAddress",
			    "addressLocality": "Jaipur",
			    "addressCountry": "IN"
			  },
			  "founder": {
			    "@@type": "Person",
			    "name": "Nirbhay Dhaked"
			  },
			  "areaServed": "Jaipur"
			}
			</script>
			@endif

	    <link href="portfolio/css/bootstrap.min.css" rel="stylesheet">
	    <link href="portfolio/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css" rel="stylesheet">
	    <link href="portfolio/css/font-awesome.min.css" rel="stylesheet">
	    <link href="portfolio/fonts/ionicons/css/ionicons.min.css" rel="stylesheet">
	    <link href="portfolio/css/owl.carousel.css" rel="stylesheet">
	    <link href="portfolio/css/owl.theme.default.css" rel="stylesheet">
	    <link href="portfolio/css/style.css" rel="stylesheet">
        <link rel="icon" type="image/svg+xml" href="{{ asset('public/favicon.svg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}">
        <script>
            const RECAPTCHA_SITE_KEY = "{{ config('services.recaptcha.site_key') }}";
        </script>
        <script type="text/javascript">
          var _publicPath = "{{url('/')}}";
          var REQUEST_URL = "{{Request::url()}}";
          var App_ENV = "{{config('app.env')}}";
          var _authUserId_ = "{{optional(auth()->user())->id}}";
       </script>
    		<link rel="stylesheet" type="text/css" href="portfolio/css/cubeportfolio.min.css">
        <script>
      (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
          (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
          m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
      })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
      ga('create', 'UA-73740395-1', 'auto');
      ga('send', 'pageview');
   
		document.onkeydown = function (e) {
			e = e || window.event;
			if (e.ctrlKey) {
				var c = e.which || e.keyCode;
				switch (c) {
					case 83:
						e.preventDefault();     
						e.stopPropagation();
					break;
				}
			}
		};

	function ieClicked() {
		if (document.all) {
			return false;
		}
	}
	function firefoxClicked(e) {
		if(document.layers||(document.getElementById&&!document.all)) {
			if (e.which==2||e.which==3) {
				return false;
			}
		}
	}
	if (document.layers){
		document.captureEvents(Event.MOUSEDOWN);
		document.onmousedown=firefoxClicked;
	}else{
		document.onmouseup=firefoxClicked;
		document.oncontextmenu=ieClicked;
	}
	document.oncontextmenu=new Function("return false")
 

shortcut = {
all_shortcuts: {},
  add: function (e, t, n) {
	var r = {
	  type: "keydown",
	  propagate: !1,
	  disable_in_input: !1,
	  target: document,
	  keycode: !1
	};
	if (n) for (var i in r) "undefined" == typeof n[i] && (n[i] = r[i]);
	else n = r;
	r = n.target, "string" == typeof n.target && (r = document.getElementById(n.target)), e = e.toLowerCase(), i = function (r) {
	  r = r || window.event;
	  if (n.disable_in_input) {
		var i;
		r.target ? i = r.target : r.srcElement && (i = r.srcElement), 3 == i.nodeType && (i = i.parentNode);
		if ("INPUT" == i.tagName || "TEXTAREA" == i.tagName) return
	  }
	  r.keyCode ? code = r.keyCode : r.which && (code = r.which), i = String.fromCharCode(code).toLowerCase(), 188 == code && (i = ","), 190 == code && (i = ".");
	  var s = e.split("+"),
		o = 0,
		u = {
		  "`": "~",
		  1: "!",
		  2: "@",
		  3: "#",
		  4: "$",
		  5: "%",
		  6: "^",
		  7: "&",
		  8: "*",
		  9: "(",
		  0: ")",
		  "-": "_",
		  "=": "+",
		  ";": ":",
		  "'": '"',
		  ",": "<",
		  ".": ">",
		  "/": "?",
		  "\\": "|"
		}, f = {
		  esc: 27,
		  escape: 27,
		  tab: 9,
		  space: 32,
		  "return": 13,
		  enter: 13,
		  backspace: 8,
		  scrolllock: 145,
		  scroll_lock: 145,
		  scroll: 145,
		  capslock: 20,
		  caps_lock: 20,
		  caps: 20,
		  numlock: 144,
		  num_lock: 144,
		  num: 144,
		  pause: 19,
		  "break": 19,
		  insert: 45,
		  home: 36,
		  "delete": 46,
		  end: 35,
		  pageup: 33,
		  page_up: 33,
		  pu: 33,
		  pagedown: 34,
		  page_down: 34,
		  pd: 34,
		  left: 37,
		  up: 38,
		  right: 39,
		  down: 40,
		  f1: 112,
		  f2: 113,
		  f3: 114,
		  f4: 115,
		  f5: 116,
		  f6: 117,
		  f7: 118,
		  f8: 119,
		  f9: 120,
		  f10: 121,
		  f11: 122,
		  f12: 123
		}, l = !1,
		c = !1,
		h = !1,
		p = !1,
		d = !1,
		v = !1,
		m = !1,
		y = !1;
	  r.ctrlKey && (p = !0), r.shiftKey && (c = !0), r.altKey && (v = !0), r.metaKey && (y = !0);
	  for (var b = 0; k = s[b], b < s.length; b++) "ctrl" == k || "control" == k ? (o++, h = !0) : "shift" == k ? (o++, l = !0) : "alt" == k ? (o++, d = !0) : "meta" == k ? (o++, m = !0) : 1 < k.length ? f[k] == code && o++ : n.keycode ? n.keycode == code && o++ : i == k ? o++ : u[i] && r.shiftKey && (i = u[i], i == k && o++);
	  if (o == s.length && p == h && c == l && v == d && y == m && (t(r), !n.propagate)) return r.cancelBubble = !0, r.returnValue = !1, r.stopPropagation && (r.stopPropagation(), r.preventDefault()), !1
	}, this.all_shortcuts[e] = {
	  callback: i,
	  target: r,
	  event: n.type
	}, r.addEventListener ? r.addEventListener(n.type, i, !1) : r.attachEvent ? r.attachEvent("on" + n.type, i) : r["on" + n.type] = i
  },
  remove: function (e) {
	var e = e.toLowerCase(),
	  t = this.all_shortcuts[e];
	delete this.all_shortcuts[e];
	if (t) {
	  var e = t.event,
		n = t.target,
		t = t.callback;
	  n.detachEvent ? n.detachEvent("on" + e, t) : n.removeEventListener ? n.removeEventListener(e, t, !1) : n["on" + e] = !1
	}
  }
},
	shortcut.add("Ctrl+U",function(){
 
}),
	shortcut.add("Ctrl+C",function(){

})
    </script>
    </head>
    <body>
	      @if(env('APP_ENV') === 'production')
	        <!-- add google tags here -->
	      @endif
          @yield('content')
        <script src="portfolio/js/jquery-1.11.2.min.js"></script>
			  <script src="portfolio/js/bootstrap.min.js"></script>
			  <script src="portfolio/js/jquery.inview.min.js"></script>
			  <script src="portfolio/js/smoothscroll.js"></script>
			  <script src="portfolio/js/jquery.knob.min.js"></script>
			  <script src="portfolio/js/owl.carousel.min.js"></script>
			  <script src="portfolio/js/isotope.pkgd.min.js"></script>
			  <script src="portfolio/js/imagesloaded.pkgd.min.js"></script>
			  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
			  <script src="portfolio/js/scripts.js"></script>
			  <script type="text/javascript" src="portfolio/js/jquery-latest.min.js"></script>
			  <script type="text/javascript" src="portfolio/js/jquery.cubeportfolio.min.js"></script>
			  <script type="text/javascript" src="portfolio/js/main.js"></script>
         <!--  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
          @yield('uniquePageScript')
            <!-- <script src="https://cdn.jsdelivr.net/npm/lobibox@1.2.7/dist/js/lobibox.min.js"></script> -->
          @yield('script')
          @include('chatbot::chat-widget')
    </body>
</html>
