<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Pro - Professional Client Portals in 15 Minutes</title>
    <meta name="description" content="Create stunning, white-label client portals for your freelance or agency business. Launch in 15 minutes with custom branding, file sharing, and payment integration.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-blue-600">Portal Pro</h1>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#features" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">Features</a>
                        <a href="#testimonials" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">Testimonials</a>
                        <a href="#pricing" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">Pricing</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Get Started</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-20 pb-16 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Professional Client Portals
                    <span class="text-blue-600">in 15 Minutes</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Create stunning, white-label client portals that wow your clients and streamline your business. 
                    Perfect for freelancers, consultants, and agencies who want to deliver a premium experience.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition duration-200">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition duration-200">
                            Start Free Trial
                        </a>
                        <a href="#demo" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition duration-200">
                            Watch Demo
                        </a>
                    @endauth
                </div>
                <p class="text-sm text-gray-500 mt-4">No credit card required • 14-day free trial</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Everything you need to impress your clients
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Portal Pro gives you all the tools to create professional client experiences that set you apart from the competition.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $feature)
                    <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition duration-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            @if($feature['icon'] === 'rocket')
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            @elseif($feature['icon'] === 'palette')
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                </svg>
                            @elseif($feature['icon'] === 'users')
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            @elseif($feature['icon'] === 'folder')
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                            @elseif($feature['icon'] === 'credit-card')
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Loved by freelancers and agencies
                </h2>
                <p class="text-xl text-gray-600">
                    See what our customers are saying about Portal Pro
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $testimonial['avatar'] }}
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">{{ $testimonial['name'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">"{{ $testimonial['content'] }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Simple, transparent pricing
                </h2>
                <p class="text-xl text-gray-600">
                    Choose the plan that fits your business size
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($pricing as $plan)
                    <div class="bg-white border {{ $plan['popular'] ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-200' }} rounded-lg p-8 relative">
                        @if($plan['popular'])
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                            </div>
                        @endif
                        
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                            <p class="text-gray-600 mb-4">{{ $plan['description'] }}</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-gray-900">${{ $plan['price'] }}</span>
                                <span class="text-gray-600">/month</span>
                            </div>
                            
                            <ul class="text-left space-y-3 mb-8">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                            
                            <a href="{{ route('register') }}" class="w-full {{ $plan['popular'] ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} py-3 px-6 rounded-lg font-semibold transition duration-200 block text-center">
                                Start Free Trial
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <p class="text-gray-600">All plans include a 14-day free trial. No credit card required.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-blue-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Ready to impress your clients?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of freelancers and agencies who trust Portal Pro to deliver exceptional client experiences.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 transition duration-200">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 transition duration-200">
                    Start Your Free Trial
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-blue-400 mb-4">Portal Pro</h3>
                    <p class="text-gray-400">
                        Professional client portals for freelancers and agencies.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
                        <li><a href="#" class="hover:text-white">Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">Status</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-white">Privacy</a></li>
                        <li><a href="#" class="hover:text-white">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Portal Pro. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth scrolling script -->
    <script>
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
