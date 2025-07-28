<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the marketing home page.
     */
    public function index(): View
    {
        return view('home', [
            'features' => [
                [
                    'icon' => 'rocket',
                    'title' => 'Launch in 15 Minutes',
                    'description' => 'Create a professional client portal with custom branding in just 15 minutes. No technical skills required.'
                ],
                [
                    'icon' => 'palette',
                    'title' => 'White-Label Branding',
                    'description' => 'Customize colors, logos, and domains to match your brand perfectly. Your clients will never know it\'s not custom-built.'
                ],
                [
                    'icon' => 'users',
                    'title' => 'Client Management',
                    'description' => 'Invite clients, manage permissions, track projects, and handle invoices all in one centralized dashboard.'
                ],
                [
                    'icon' => 'folder',
                    'title' => 'Secure File Sharing',
                    'description' => 'Share files securely with clients, control access permissions, and track downloads with version control.'
                ],
                [
                    'icon' => 'credit-card',
                    'title' => 'Integrated Payments',
                    'description' => 'Send invoices and collect payments directly through your portal with Stripe integration.'
                ],
                [
                    'icon' => 'zap',
                    'title' => 'Powerful Integrations',
                    'description' => 'Connect with Zapier, Notion, and other tools to automate your workflow and sync data seamlessly.'
                ]
            ],
            'testimonials' => [
                [
                    'name' => 'Sarah Chen',
                    'role' => 'Freelance Designer',
                    'content' => 'Portal Pro transformed how I work with clients. What used to take hours of back-and-forth emails now happens seamlessly in one place.',
                    'avatar' => 'SC'
                ],
                [
                    'name' => 'Mike Rodriguez',
                    'role' => 'Marketing Consultant',
                    'content' => 'My clients love the professional experience. It\'s helped me close more deals and retain clients longer.',
                    'avatar' => 'MR'
                ],
                [
                    'name' => 'Emma Thompson',
                    'role' => 'Development Agency Owner',
                    'content' => 'The white-label features are incredible. Our clients think we built this custom portal just for them.',
                    'avatar' => 'ET'
                ]
            ],
            'pricing' => [
                [
                    'name' => 'Starter',
                    'price' => 29,
                    'description' => 'Perfect for freelancers',
                    'features' => ['Up to 5 clients', '1 portal', 'Basic branding', 'File sharing', 'Email support'],
                    'popular' => false
                ],
                [
                    'name' => 'Professional',
                    'price' => 59,
                    'description' => 'Great for growing businesses',
                    'features' => ['Up to 25 clients', '3 portals', 'Full branding', 'Custom domain', 'Priority support', 'Integrations'],
                    'popular' => true
                ],
                [
                    'name' => 'Agency',
                    'price' => 99,
                    'description' => 'For agencies and teams',
                    'features' => ['Unlimited clients', 'Unlimited portals', 'White-label', 'Team collaboration', 'API access', 'Phone support'],
                    'popular' => false
                ]
            ]
        ]);
    }
}
