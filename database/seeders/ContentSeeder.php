<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Sarah Ahmed', 'role' => 'Founder, Bloom Studio', 'content' => 'NexusDig transformed our online presence. Traffic doubled in three months.', 'rating' => 5],
            ['name' => 'Imran Kabir', 'role' => 'Marketing Lead, FinEdge', 'content' => 'Clear strategy, fast execution, and reporting we can actually use.', 'rating' => 5],
            ['name' => 'Nadia Islam', 'role' => 'Student', 'content' => 'The SEO course paid for itself within a month.', 'rating' => 5],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial + ['is_approved' => true],
            );
        }

        $faqs = [
            [
                'question' => 'Do I get lifetime access to courses?',
                'answer' => 'Yes, most courses include lifetime access and free updates.',
                'category' => 'courses',
                'sort_order' => 0,
            ],
            [
                'question' => 'How do live classes work?',
                'answer' => 'Live classes are hosted on Zoom or Google Meet and recordings are added to your dashboard.',
                'category' => 'courses',
                'sort_order' => 1,
            ],
            [
                'question' => 'Can you work with our in-house team?',
                'answer' => 'Absolutely. We embed with your team and transfer knowledge as we go.',
                'category' => 'services',
                'sort_order' => 2,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq + ['is_active' => true],
            );
        }

        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About NexusDig',
                'content' => '<p>NexusDig is a digital growth studio and learning platform helping people and brands win online.</p>',
                'status' => 'published',
            ],
        );

        $admin = User::where('email', 'admin@nexusdig.com')->first();

        if ($admin) {
            BlogPost::updateOrCreate(
                ['slug' => 'five-seo-wins-you-can-ship-this-week'],
                [
                    'author_id' => $admin->id,
                    'title' => 'Five SEO wins you can ship this week',
                    'excerpt' => 'Practical, low-effort SEO improvements that compound over time.',
                    'content' => '<p>Start with technical hygiene, then layer content and internal links.</p>',
                    'status' => 'published',
                    'published_at' => now()->subDays(5),
                ],
            );
        }

        SiteSetting::put('site_name', 'NexusDig');
        SiteSetting::put('contact_email', 'hello@nexusdig.com', 'contact');
        SiteSetting::put('contact_phone', '+880 1700 000000', 'contact');
        SiteSetting::put('whatsapp_number', '+880 1700 000000', 'contact');
        SiteSetting::put('address', 'Dhaka, Bangladesh', 'contact');
        SiteSetting::put('facebook_url', 'https://facebook.com/nexusdig', 'social');
        SiteSetting::put('linkedin_url', 'https://linkedin.com/company/nexusdig', 'social');
        SiteSetting::put('default_currency', 'BDT', 'commerce');
    }
}
