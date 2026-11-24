<?php

namespace Database\Seeders;

use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceInquiry;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'SEO & Content', 'slug' => 'seo-content'],
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Paid Advertising', 'slug' => 'paid-advertising'],
            ['name' => 'Mobile Apps', 'slug' => 'mobile-apps'],
            ['name' => 'Custom Software', 'slug' => 'custom-software'],
            ['name' => 'AI & Automation', 'slug' => 'ai-automation'],
            ['name' => 'Telegram Bots', 'slug' => 'telegram-bots'],
        ];

        foreach ($categories as $index => $category) {
            ServiceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['sort_order' => $index],
            );
        }

        $services = [
            [
                'category' => 'web-development',
                'name' => 'Web Development',
                'slug' => 'web-development',
                'starting_price' => 14900,
                'is_featured' => true,
            ],
            [
                'category' => 'mobile-apps',
                'name' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'starting_price' => 24900,
                'is_featured' => false,
            ],
            [
                'category' => 'custom-software',
                'name' => 'Software Development',
                'slug' => 'custom-software',
                'starting_price' => 39900,
                'is_featured' => false,
            ],
            [
                'category' => 'seo-content',
                'name' => 'SEO & Digital Growth',
                'slug' => 'seo-content',
                'starting_price' => 9900,
                'is_featured' => false,
            ],
            [
                'category' => 'ai-automation',
                'name' => 'AI Services',
                'slug' => 'ai-automation',
                'starting_price' => 19900,
                'is_featured' => true,
            ],
            [
                'category' => 'telegram-bots',
                'name' => 'Bots & Automation',
                'slug' => 'telegram-bots',
                'starting_price' => 12900,
                'is_featured' => false,
            ],
            [
                'category' => 'seo-content',
                'name' => 'Technical SEO Audit',
                'slug' => 'technical-seo-audit',
                'starting_price' => 59900,
                'is_featured' => true,
            ],
            [
                'category' => 'web-development',
                'name' => 'Laravel Web Application',
                'slug' => 'laravel-web-application',
                'starting_price' => 299000,
                'is_featured' => true,
            ],
            [
                'category' => 'paid-advertising',
                'name' => 'Google Ads Management',
                'slug' => 'google-ads-management',
                'starting_price' => 47900,
                'is_featured' => false,
            ],
        ];

        foreach ($services as $index => $data) {
            $category = ServiceCategory::where('slug', $data['category'])->first();

            Service::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'service_category_id' => $category?->id,
                    'name' => $data['name'],
                    'short_description' => 'A results-focused '.strtolower($data['name']).' delivered by specialists.',
                    'description' => '<p>We combine strategy, execution, and reporting to deliver measurable outcomes.</p>',
                    'features' => [
                        'Dedicated specialist',
                        'Weekly reporting',
                        'Performance guarantee',
                    ],
                    'deliverables' => [
                        'Strategy document',
                        'Implementation',
                        'Monthly report',
                    ],
                    'technologies' => ['Laravel', 'MySQL', 'Tailwind CSS'],
                    'starting_price' => $data['starting_price'],
                    'price_note' => 'Starting from',
                    'delivery_range' => '2-4 weeks',
                    'faqs' => [
                        [
                            'question' => 'How soon can we start?',
                            'answer' => 'Within one week of kickoff.',
                        ],
                    ],
                    'is_featured' => $data['is_featured'],
                    'status' => 'active',
                    'sort_order' => $index,
                ],
            );
        }

        PortfolioProject::updateOrCreate(
            ['slug' => 'fintech-growth-engine'],
            [
                'service_category_id' => ServiceCategory::where('slug', 'seo-content')->value('id'),
                'title' => 'Fintech Growth Engine',
                'description' => '<p>Tripled organic traffic for a fintech SaaS in six months with a full-funnel SEO and content programme.</p>',
                'tech_stack' => ['Laravel', 'MySQL', 'Google Analytics'],
                'project_url' => 'https://example.com',
                'status' => 'published',
                'is_featured' => true,
                'completed_at' => now()->subMonths(2),
            ],
        );

        ServiceInquiry::updateOrCreate(
            [
                'email' => 'lead@example.com',
                'project_description' => 'We need help with a Laravel rebuild.',
            ],
            [
                'name' => 'Maya Chowdhury',
                'phone' => '+880 1700 000000',
                'service_id' => Service::where('slug', 'laravel-web-application')->value('id'),
                'budget_range' => '$2,500 - $5,000',
                'timeline' => '1-2 months',
                'status' => 'new',
                'source' => 'website',
            ],
        );
    }
}
