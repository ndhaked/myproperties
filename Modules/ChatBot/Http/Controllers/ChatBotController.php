<?php

namespace Modules\ChatBot\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chatbot::index');
    }

    /**
     * Handle the chatbot message request.
     */
    public function handleMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        
        $result = $this->getAiResponse($userMessage);

        return response()->json([
            'status' => 'success',
            'message' => $result['response'],
            'quick_replies' => $result['quick_replies'] ?? [],
        ]);
    }

    /**
     * Pick a random response from an array of responses.
     */
    private function randomResponse(array $responses): string
    {
        return $responses[array_rand($responses)];
    }

    /**
     * Sophisticated response logic for the portfolio.
     */
    private function getAiResponse($message)
    {
        $message = strtolower(trim($message));
        
        // Time-aware greeting prefix
        $hour = (int) now()->format('H');
        if ($hour >= 5 && $hour < 12) {
            $timeGreeting = "Good Morning ☀️";
        } elseif ($hour >= 12 && $hour < 17) {
            $timeGreeting = "Good Afternoon 🌤️";
        } elseif ($hour >= 17 && $hour < 21) {
            $timeGreeting = "Good Evening 🌆";
        } else {
            $timeGreeting = "Hello 🌙";
        }

        // Knowledge Base - Priority ordered (Specific -> Generic)
        // Each intent supports multiple response variations for a human-like feel
        $kb = [
            'good_morning' => [
                'keywords' => ['good morning', 'morning nirbhay', 'gm nirbhay', 'gm', 'suprabhat', 'subah'],
                'responses' => [
                    "Very Good Morning ☀️ and have a wonderful day! How can I assist you today?",
                    "Good Morning! ☀️ Welcome! Feel free to ask me anything about Nirbhay's expertise or services.",
                    "Morning! ☀️ Great to see you here. Want to know about Nirbhay's **experience**, **skills**, or **projects**?",
                ],
                'quick_replies' => ['View Experience', 'Download Resume', 'Contact Nirbhay'],
            ],
            'good_afternoon' => [
                'keywords' => ['good afternoon', 'afternoon'],
                'responses' => [
                    "Good Afternoon 🌤️! Hope your day is going well. How can I help you?",
                    "Good Afternoon! 🌤️ Looking to know more about Nirbhay's work? I'm here to help!",
                ],
                'quick_replies' => ['View Skills', 'View Projects', 'Book Meeting'],
            ],
            'good_evening' => [
                'keywords' => ['good evening', 'evening', 'good night', 'night'],
                'responses' => [
                    "Good Evening 🌆! Thanks for visiting. How can I assist you today?",
                    "Good Evening! 🌆 Feel free to explore Nirbhay's portfolio. Ask me anything!",
                ],
                'quick_replies' => ['View Experience', 'View Skills', 'Contact Info'],
            ],
            'current_company' => [
                'keywords' => ['current company', 'currently working', 'where working now', 'present company', 'which company', 'working at'],
                'responses' => [
                    "Nirbhay is currently working at **MOBIIWORLD Pvt. Limited** (Nov 2025 - Present) as a **Senior Laravel Developer & Tech Lead** 🏢",
                    "Currently, Nirbhay holds the position of **Senior Laravel Developer & Tech Lead** at **MOBIIWORLD Pvt. Limited** since November 2025.",
                ],
                'quick_replies' => ['Full Experience', 'View Skills', 'Download CV'],
            ],
            'availability' => [
                'keywords' => ['available', 'availability', 'freelance', 'hire now', 'open for work', 'open to work', 'can we hire', 'looking for work'],
                'responses' => [
                    "Yes! ✅ Nirbhay is open to freelance opportunities, consulting, and long-term collaborations. Reach out via **WhatsApp (+91 8209-99-0511)** or the contact form to discuss your project.",
                    "Absolutely! Nirbhay is available for new projects. Whether it's freelance, contract, or full-time consulting — he's ready. Let's connect! 🤝",
                ],
                'quick_replies' => ['Contact Nirbhay', 'Book Meeting', 'View Services'],
            ],
            'meeting' => [
                'keywords' => ['meeting', 'book call', 'schedule call', 'zoom meeting', 'consultation', 'appointment', 'discuss project', 'connect'],
                'responses' => [
                    "Great idea! 📅 You can schedule a meeting with Nirbhay via:\n- **WhatsApp**: +91 8209-99-0511\n- **Email**: nirbhaydhaked@gmail.com\n- Or just drop a message through the **Contact Form** below!",
                    "Let's set up a meeting! 🤝 Reach out via WhatsApp at **+91 8209-99-0511** or email **nirbhaydhaked@gmail.com** to book a slot.",
                ],
                'quick_replies' => ['Contact Info', 'View Services', 'Download Resume'],
            ],
            'pricing' => [
                'keywords' => ['price', 'pricing', 'cost', 'budget', 'charges', 'rate', 'salary', 'hourly rate', 'how much', 'quote', 'estimate'],
                'responses' => [
                    "💰 Pricing depends on the project scope, timeline, and complexity. Nirbhay offers competitive rates for:\n- **Hourly Consulting**\n- **Fixed-price Projects**\n- **Monthly Retainers**\n\nShare your requirements for a tailored quote!",
                    "Every project is unique! 💰 Share your requirements and Nirbhay will provide a transparent, competitive quote. He offers hourly, fixed, and retainer pricing models.",
                ],
                'quick_replies' => ['Contact Nirbhay', 'View Services', 'Book Meeting'],
            ],
            'remote' => [
                'keywords' => ['remote', 'work remotely', 'onsite', 'hybrid', 'international clients', 'work from home', 'overseas'],
                'responses' => [
                    "🌍 Nirbhay works seamlessly with clients across the globe — India, UAE, USA, and beyond. He's comfortable with **remote**, **hybrid**, and **timezone-flexible** collaboration.",
                    "Absolutely! 🌍 Nirbhay has extensive experience working with international clients remotely. Tools like Slack, Zoom, Jira, and Git keep collaboration smooth.",
                ],
                'quick_replies' => ['Contact Info', 'View Experience', 'Book Meeting'],
            ],
            'leadership' => [
                'keywords' => ['team lead', 'leadership', 'manage team', 'technical lead', 'architect', 'mentoring', 'code review'],
                'responses' => [
                    "🎯 Nirbhay is an experienced **Tech Lead** who has:\n- Led cross-functional teams of 5-15 developers\n- Designed scalable system architectures\n- Conducted code reviews & mentored junior devs\n- Managed sprint planning & delivery pipelines",
                    "Leadership is one of Nirbhay's core strengths! 🎯 He's led teams at multiple companies, handled architecture decisions, and ensured code quality through rigorous reviews.",
                ],
                'quick_replies' => ['View Experience', 'View Projects', 'View Skills'],
            ],
            'ai' => [
                'keywords' => ['ai', 'artificial intelligence', 'chatbot', 'automation', 'machine learning', 'gemini', 'openai', 'gpt', 'chatgpt'],
                'responses' => [
                    "🤖 Nirbhay actively works on AI-powered solutions:\n- **AI Chatbot Integrations** (Gemini, OpenAI)\n- **Smart Automation Workflows**\n- **Intelligent Recommendation Systems**\n- **API-driven AI Pipelines**\n\nThis very chatbot is built by him! 😄",
                    "Yes! 🤖 Nirbhay integrates AI into real-world applications — from this chatbot you're using right now to enterprise-grade automation systems. He works with OpenAI, Gemini, and custom ML pipelines.",
                ],
                'quick_replies' => ['View Projects', 'View Skills', 'Contact Nirbhay'],
            ],
            'payment' => [
                'keywords' => ['payment', 'razorpay', 'paypal', 'cashfree', 'stripe', 'gateway integration', 'payment gateway'],
                'responses' => [
                    "💳 Nirbhay has deep expertise in payment integrations:\n- **Razorpay** (India-focused)\n- **Stripe** (Global)\n- **PayPal** (International)\n- **Cashfree** (India)\n- **Custom Wallet Systems**\n- **Subscription & Recurring Billing**",
                    "Payment systems are a specialty! 💳 From Razorpay to Stripe to custom wallet architectures — Nirbhay has built secure, PCI-compliant payment workflows for multiple platforms.",
                ],
                'quick_replies' => ['View Projects', 'View Services', 'Book Meeting'],
            ],
            'database' => [
                'keywords' => ['database', 'db', 'mysql optimization', 'query optimization', 'performance', 'slow queries', 'indexing', 'migration'],
                'responses' => [
                    "⚡ Database optimization is one of Nirbhay's superpowers:\n- **Complex Query Optimization** (reduced query times by up to 90%)\n- **Strategic Indexing** for millions of records\n- **Redis Caching** for high-traffic systems\n- **Database Sharding & Replication**\n- **PgSQL & MySQL expertise**",
                    "Slow queries? Nirbhay can fix that! ⚡ He specializes in MySQL/PgSQL optimization, indexing strategies, Redis caching, and scaling databases for high-traffic Laravel apps.",
                ],
                'quick_replies' => ['View Skills', 'View Projects', 'Contact Nirbhay'],
            ],
            'saas' => [
                'keywords' => ['saas', 'multi vendor', 'super app', 'food delivery app', 'ride booking', 'marketplace', 'multi-tenant'],
                'responses' => [
                    "🚀 Nirbhay has architected complex SaaS & Super App platforms:\n- **Food Delivery** systems\n- **Ride Booking** platforms\n- **Multi-vendor Marketplaces**\n- **Logistics & Parcel Delivery**\n- **Multi-tenant SaaS Architecture**\n\nAll built with **scalability** and **performance** at the core!",
                ],
                'quick_replies' => ['View All Projects', 'View Experience', 'Book Meeting'],
            ],
            'contact' => [
                'keywords' => ['contact', 'email', 'phone', 'call', 'skype', 'whatsapp', 'reach', 'mobile', 'address', 'number'],
                'responses' => [
                    "📱 You can reach Nirbhay directly:\n\n- **📧 Email**: nirbhaydhaked@gmail.com\n- **📞 Phone/WhatsApp**: +91 8209-99-0511\n- **💬 Skype**: live:718c6b5c940cd730\n- **📍 Location**: Jaipur, Rajasthan (India)\n\nOr simply use the **Contact Form** at the bottom of this page!",
                    "Here are all the ways to reach Nirbhay:\n\n📧 **nirbhaydhaked@gmail.com**\n📞 **+91 8209-99-0511** (WhatsApp available)\n💬 **Skype**: live:718c6b5c940cd730\n\nHe typically responds within a few hours! ⚡",
                ],
                'quick_replies' => ['Download Resume', 'Book Meeting', 'View Services'],
            ],
            'resume' => [
                'keywords' => ['resume', 'cv', 'biodata', 'curriculum vitae', 'profile file', 'download resume', 'pdf'],
                'responses' => [
                    "📄 Sure! Here's Nirbhay's professional resume:\n\n[📄 Download Resume (PDF)](/Nirbhay%20Singh%20SR.%20Laravel%20Developer.pdf)\n\nIt includes his complete work history, technical skills, and project highlights.",
                    "Here you go! 📄 Click below to download Nirbhay's detailed CV:\n\n[📄 Download Resume (PDF)](/Nirbhay%20Singh%20SR.%20Laravel%20Developer.pdf)",
                ],
                'quick_replies' => ['View Experience', 'View Skills', 'Contact Nirbhay'],
            ],
            'education' => [
                'keywords' => ['education', 'edu', 'degree', 'mca', 'bca', 'university', 'college', 'studied', 'qualification', 'educations', 'academic'],
                'responses' => [
                    "🎓 Nirbhay's Academic Background:\n\n- **MCA (Honours)** — Rajasthan Technical University, Kota\n- **BCA** — University of Rajasthan, Jaipur\n\nHe has a strong academic foundation in Computer Science, Data Structures, and System Architecture.",
                    "🎓 Nirbhay holds a **Master's in Computer Applications (MCA Honours)** from RTU Kota, and a **Bachelor's in Computer Applications (BCA)** from UOR Jaipur. Solid academic credentials combined with 12+ years of industry experience!",
                ],
                'quick_replies' => ['View Experience', 'View Skills', 'Download Resume'],
            ],
            'location' => [
                'keywords' => ['jaipur', 'india', 'location', 'city', 'rajasthan', 'based', 'hometown'],
                'responses' => [
                    "📍 Nirbhay is based in **Jaipur, Rajasthan (India)** — the vibrant Pink City! He's widely recognized as a top **Laravel Expert in Jaipur** and serves clients globally across India, UAE, USA, and Europe.",
                    "📍 Located in **Jaipur, India**. Nirbhay works with clients worldwide and is comfortable with any timezone. He is known as one of the **Top Laravel Developers in Jaipur**.",
                ],
                'quick_replies' => ['Contact Info', 'View Experience', 'Book Meeting'],
            ],
            'skills' => [
                'keywords' => ['skills', 'tech', 'technology', 'stack', 'backend', 'api', 'mysql', 'redis', 'jquery', 'expertise', 'postgre', 'pgsql', 'technical skills', 'what can you do'],
                'responses' => [
                    "🛠️ Nirbhay's Technical Arsenal:\n\n**🔹 Framework**: Laravel (v5 to v12), Lumen\n**🔹 Backend**: Core PHP, RESTful APIs, GraphQL\n**🔹 Database**: MySQL (Expert), PostgreSQL, Redis\n**🔹 Frontend**: jQuery, Ajax, HTML5, CSS3, Bootstrap\n**🔹 DevOps**: Linux, Git, CI/CD, Docker basics\n**🔹 APIs**: Payment Gateways, SMS, Maps, Social Login\n**🔹 Tools**: Jira, Slack, Bitbucket, GitHub",
                ],
                'quick_replies' => ['View Experience', 'View Projects', 'Download Resume'],
            ],
            'services' => [
                'keywords' => ['services', 'hire', 'build', 'develop', 'offer', 'what services', 'can you build'],
                'responses' => [
                    "🏆 Nirbhay offers premium development services:\n\n- **🔧 SaaS Application Development**\n- **🛒 E-Commerce & Marketplace Platforms**\n- **💼 Custom CRM & ERP Solutions**\n- **💳 Payment Gateway Integration**\n- **🔗 Third-party API Integrations**\n- **⚡ Database & Performance Optimization**\n- **🤖 AI Chatbot & Automation Systems**\n- **📱 Super App Architecture**\n\nReady to discuss your project? Let's connect!",
                ],
                'quick_replies' => ['Contact Nirbhay', 'Book Meeting', 'View Projects'],
            ],
            'experience' => [
                'keywords' => ['experience', 'exp', 'exp.', 'years', 'career', 'journey', 'work history', 'professional experience', 'where worked', 'companies'],
                'responses' => [
                    "Nirbhay's professional journey spans over **12+ years** 🚀 Here is his career timeline:\n\n" .
                    "<div class='chat-timeline'>" .
                    "<div class='timeline-item'><div class='timeline-dot'></div><div class='timeline-content'><strong>Senior Laravel Developer & Tech Lead</strong><br><small>MOBIIWORLD | Nov 2025 - Present</small></div></div>" .
                    "<div class='timeline-item'><div class='timeline-dot'></div><div class='timeline-content'><strong>Software Engineer (Laravel Module Lead)</strong><br><small>OPTIMA TAX RELIEF | Jan 2025 - Nov 2025</small></div></div>" .
                    "<div class='timeline-item'><div class='timeline-dot'></div><div class='timeline-content'><strong>Sr. Laravel Developer & Tech Lead</strong><br><small>KONSTANT INFOSOLUTIONS | Dec 2017 - Nov 2024</small></div></div>" .
                    "<div class='timeline-item'><div class='timeline-dot'></div><div class='timeline-content'><strong>Senior Software Engineer</strong><br><small>ARKA SOFTWARES | Jun 2016 - Dec 2017</small></div></div>" .
                    "<div class='timeline-item'><div class='timeline-dot'></div><div class='timeline-content'><strong>PHP Developer</strong><br><small>WDP TECHNOLOGIES | Sep 2015 - Jun 2016</small></div></div>" .
                    "<div class='timeline-item'><div class='timeline-dot'></div><div class='timeline-content'><strong>Web Programmer & Developer</strong><br><small>ECARE SOFTECH | Sep 2014 - Sep 2015</small></div></div>" .
                    "</div>\n\n" .
                    "![Professional Experience Timeline](/images/professional-experience.png)\n\n" .
                    "*(Click the image above to download the full timeline)*\n\n" .
                    "[📄 Download Full CV (PDF)](/Nirbhay%20Singh%20SR.%20Laravel%20Developer.pdf)",
                ],
                'quick_replies' => ['View Skills', 'View Projects', 'Contact Nirbhay'],
            ],
            'laravel' => [
                'keywords' => ['laravel', 'framework', 'php'],
                'responses' => [
                    "🔥 **Laravel** is Nirbhay's core expertise! He has been working with it since Laravel 4.x and is now proficient in **Laravel 12**. His specialization includes:\n- Modular Architecture\n- Multi-tenant SaaS\n- Advanced Eloquent & Query Builder\n- Custom Package Development\n- API-first Design Patterns",
                    "Laravel is where Nirbhay truly shines! 🔥 With 12+ years in PHP and deep mastery of the Laravel ecosystem (v5-v12), he builds enterprise-grade applications that scale.",
                ],
                'quick_replies' => ['View Projects', 'View Experience', 'Book Meeting'],
            ],
            'who' => [
                'keywords' => ['who are you', 'what are you', 'assistant', 'bot', 'are you real', 'are you ai'],
                'responses' => [
                    "🤖 I'm **Nirbhay's AI Assistant** — built to help you learn about his expertise, projects, and services. I'm not a generic bot — I know Nirbhay's entire career inside out!\n\nTry asking: *\"What's his experience?\"* or *\"Show me his skills\"*",
                    "Hi there! I'm a custom-built AI assistant for **Nirbhay Dhaked** 🤖 Think of me as his digital portfolio guide. Ask me anything about his work, skills, or how to reach him!",
                ],
                'quick_replies' => ['About Nirbhay', 'View Skills', 'View Experience'],
            ],
            'nirbhay' => [
                'keywords' => ['nirbhay', 'dhaked', 'the developer', 'author', 'laravel expert', 'about him', 'tell me about'],
                'responses' => [
                    "👨‍💻 **Nirbhay Dhaked** is a passionate **Senior Technology Lead & Laravel Expert** with **12+ years** of hands-on experience.\n\n🔹 **Current Role**: Tech Lead at MOBIIWORLD\n🔹 **Specialization**: Laravel, SaaS, Super Apps\n🔹 **Education**: MCA (Honours), BCA\n🔹 **Location**: Jaipur, India 🇮🇳\n🔹 **Clients**: India, UAE, USA\n\nHe believes in writing clean, scalable code and building products that make a real impact.",
                ],
                'quick_replies' => ['View Experience', 'View Skills', 'Download Resume'],
            ],
            'thank_you' => [
                'keywords' => [
                    'thank you', 'thank you so much', 'dhanyabad', 'dhanyabaad', 'shukriya',
                    'badiya', 'bahut acha', 'bahut achha', 'badhiya',
                    'thanks', 'thnx', 'thx', 'thank u', 'ty',
                    'thanks a lot', 'thankyou', 'ok thanks', 'great thanks', 'perfect',
                ],
                'responses' => [
                    "You're most welcome! 😊 If you need anything else, feel free to ask. Have a wonderful day!",
                    "Glad I could help! 🙌 Let me know if you'd like to **book a meeting** with Nirbhay.",
                    "My pleasure! 😊 I'm here anytime you need more information about Nirbhay's work.",
                    "Happy to help! 🚀 Feel free to explore more about Nirbhay's **experience** or **services**.",
                    "Anytime! Have a great day ahead 🌟 Don't hesitate to come back if you have more questions!",
                ],
                'quick_replies' => ['Book Meeting', 'Contact Nirbhay', 'Download Resume'],
            ],
            'bye' => [
                'keywords' => ['bye', 'goodbye', 'see you', 'tata', 'alvida', 'cya', 'good bye', 'take care'],
                'responses' => [
                    "Goodbye! 👋 Thanks for visiting Nirbhay's portfolio. Have a wonderful day!",
                    "See you later! 👋 Feel free to come back anytime. Nirbhay would love to hear from you!",
                    "Take care! 🌟 Don't forget — you can always reach Nirbhay at **nirbhaydhaked@gmail.com**",
                ],
                'quick_replies' => ['Contact Info', 'Download Resume'],
            ],
            'how_are_you' => [
                'keywords' => ['how are you', 'how r u', 'kaise ho', 'kya haal', 'whats up', 'wassup', 'sup'],
                'responses' => [
                    "I'm doing great, thank you for asking! 😊 I'm always ready to help. What would you like to know about Nirbhay?",
                    "All good here! 😄 How can I assist you today? Feel free to ask about Nirbhay's **skills**, **experience**, or **projects**.",
                ],
                'quick_replies' => ['About Nirbhay', 'View Skills', 'View Projects'],
            ],
            'projects' => [
                'keywords' => [
                    'projects', 'project list', 'what projects', 'what have you built',
                    'portfolio projects', 'laravel projects', 'work experience projects',
                    'real world projects', 'systems developed', 'applications built',
                    'experience in projects', 'what kind of apps'
                ],
                'responses' => [
                    "Here are some major projects Nirbhay has built 🚀\n\n" .
                    "🚀 **Super App Architecture**\n" .
                    "- Food Delivery System\n" .
                    "- Ride Booking Platform\n" .
                    "- Cab Booking System\n" .
                    "- Logistics & Parcel Delivery\n" .
                    "- Multi-Service Marketplace\n\n" .
                    "🛒 **E-Commerce & Marketplaces**\n" .
                    "- Multi-vendor Marketplace\n" .
                    "- Product Variant System\n" .
                    "- Wallet & Referral System\n" .
                    "- Dynamic Pricing & Coupons\n" .
                    "- Payment Integrations (Razorpay, PayPal, Stripe)\n\n" .
                    "💼 **Enterprise & CRM**\n" .
                    "- Tax Management System (USA)\n" .
                    "- Custom Lead Management CRM\n" .
                    "- RBAC & Workflow Systems\n\n" .
                    "📊 **SaaS Platforms**\n" .
                    "- Multi-tenant Architecture\n" .
                    "- API-first Backend Systems\n\n" .
                    "🤖 **AI & Automation**\n" .
                    "- AI Chatbot Integrations\n" .
                    "- Smart Recommendation Systems\n\n" .
                    "Over **12+ years** of real-world, production-grade development!",
                ],
                'quick_replies' => ['View Experience', 'View Skills', 'Book Meeting'],
            ],
            'ok' => [
                'keywords' => ['ok', 'okay', 'alright', 'hmm', 'achha', 'accha', 'theek hai', 'thik hai'],
                'responses' => [
                    "Great! 👍 Is there anything else you'd like to know about Nirbhay?",
                    "Sure! 👍 Feel free to ask me anything — I'm here to help!",
                    "Alright! Let me know if there's anything else I can help with. 😊",
                ],
                'quick_replies' => ['View Experience', 'View Skills', 'Contact Nirbhay'],
            ],
            'greeting' => [
                'keywords' => ['hello', 'hi', 'hey', 'greetings', 'namaste', 'hlo', 'hii', 'hola', 'yo'],
                'responses' => [
                    "{$timeGreeting}! I'm Nirbhay's AI assistant 🤖 How can I help you today?\n\nYou can ask me about his **experience**, **skills**, **projects**, or how to **contact** him!",
                    "{$timeGreeting}! Welcome to Nirbhay's portfolio 👋 I can help you with:\n- 💼 Professional Experience\n- 🛠️ Technical Skills\n- 🚀 Projects & Services\n- 📱 Contact Information\n\nWhat would you like to explore?",
                    "Hey there! {$timeGreeting} 👋 Ask me anything about Nirbhay — his **career**, **expertise**, or how to **hire** him!",
                ],
                'quick_replies' => ['About Nirbhay', 'View Experience', 'View Skills', 'Contact Info'],
            ],
        ];

        // Primary search: Exact word boundary match
        foreach ($kb as $intent => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (preg_match("/\b" . preg_quote($keyword, '/') . "\b/i", $message)) {
                    return [
                        'response' => $this->randomResponse($data['responses']),
                        'quick_replies' => $data['quick_replies'] ?? [],
                    ];
                }
            }
        }

        // Secondary search: Partial/fuzzy matches
        foreach ($kb as $intent => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    return [
                        'response' => $this->randomResponse($data['responses']),
                        'quick_replies' => $data['quick_replies'] ?? [],
                    ];
                }
            }
        }

        // Tertiary: Check for similar words (basic fuzzy matching)
        $fuzzyMap = [
            'experiance' => 'experience', 'experence' => 'experience', 'exprience' => 'experience',
            'skils' => 'skills', 'skilss' => 'skills', 'tecnology' => 'skills',
            'projeccts' => 'projects', 'projet' => 'projects', 'projcts' => 'projects',
            'contac' => 'contact', 'contct' => 'contact', 'contakt' => 'contact',
            'resme' => 'resume', 'resum' => 'resume', 'rezume' => 'resume',
            'education' => 'education', 'educaton' => 'education',
            'larvel' => 'laravel', 'laravl' => 'laravel', 'lravel' => 'laravel',
            'pament' => 'payment', 'paymet' => 'payment',
        ];

        foreach ($fuzzyMap as $typo => $correctIntent) {
            if (str_contains($message, $typo) && isset($kb[$correctIntent])) {
                return [
                    'response' => $this->randomResponse($kb[$correctIntent]['responses']),
                    'quick_replies' => $kb[$correctIntent]['quick_replies'] ?? [],
                ];
            }
        }

        // Context-aware fallback
        if (str_contains($message, '?')) {
            return [
                'response' => "That's an interesting question! 🤔 I may not have the exact answer right now, but Nirbhay would love to help you directly. Reach out at **nirbhaydhaked@gmail.com** or **+91 8209-99-0511**.",
                'quick_replies' => ['Contact Nirbhay', 'View Experience', 'View Skills'],
            ];
        }

        return [
            'response' => "I'm here to help! 💡 Try asking about:\n\n- 💼 **Experience** — Nirbhay's 12+ year career journey\n- 🛠️ **Skills** — His complete tech stack\n- 🚀 **Projects** — Real-world systems he's built\n- 📄 **Resume** — Download his CV\n- 📱 **Contact** — How to reach him\n\nOr simply type what you're looking for!",
            'quick_replies' => ['View Experience', 'View Skills', 'View Projects', 'Contact Info'],
        ];
    }
}
