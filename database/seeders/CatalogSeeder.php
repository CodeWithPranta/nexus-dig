<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Digital Marketing', 'slug' => 'digital-marketing', 'description' => 'SEO, ads, and growth.'],
            ['name' => 'Web Development', 'slug' => 'web-development', 'description' => 'Modern web engineering.'],
            ['name' => 'Data & Analytics', 'slug' => 'data-analytics', 'description' => 'Turn data into decisions.'],
            ['name' => 'Design & Branding', 'slug' => 'design-branding', 'description' => 'Visual design and brand systems.'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true, 'sort_order' => $index],
            );
        }

        $teacher = User::where('email', 'teacher@nexusdig.com')->first();

        $courses = [
            [
                'category' => 'digital-marketing',
                'title' => 'SEO Mastery: Rank on Page One',
                'slug' => 'seo-mastery-rank-on-page-one',
                'short_description' => 'A practical SEO system from keyword research to technical audits.',
                'price' => 17900,
                'discounted_price' => 11900,
                'difficulty' => 'intermediate',
                'duration_hours' => 12,
                'is_featured' => true,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'category' => 'web-development',
                'title' => 'Laravel for Production Teams',
                'slug' => 'laravel-for-production-teams',
                'short_description' => 'Build, test, and ship maintainable Laravel applications.',
                'price' => 23900,
                'discounted_price' => null,
                'difficulty' => 'advanced',
                'duration_hours' => 20,
                'is_featured' => true,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'category' => 'data-analytics',
                'title' => 'Analytics with SQL and Dashboards',
                'slug' => 'analytics-with-sql-and-dashboards',
                'short_description' => 'From raw data to executive-ready dashboards.',
                'price' => 14900,
                'discounted_price' => 9900,
                'difficulty' => 'beginner',
                'duration_hours' => 8,
                'is_featured' => false,
                'status' => Course::STATUS_PENDING,
            ],
        ];

        foreach ($courses as $data) {
            $category = Category::where('slug', $data['category'])->first();

            $course = Course::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category?->id,
                    'title' => $data['title'],
                    'short_description' => $data['short_description'],
                    'description' => '<p>'.$data['short_description'].' This cohort-style course blends short lessons, live Q&A, and hands-on projects.</p>',
                    'language' => 'English',
                    'difficulty' => $data['difficulty'],
                    'duration_hours' => $data['duration_hours'],
                    'learning_objectives' => [
                        'Build a repeatable workflow',
                        'Apply real-world frameworks',
                        'Ship a portfolio project',
                    ],
                    'prerequisites' => [
                        'Basic computer literacy',
                        'Willingness to practise',
                    ],
                    'target_audience' => [
                        'Marketers',
                        'Founders',
                        'Career switchers',
                    ],
                    'price' => $data['price'],
                    'discounted_price' => $data['discounted_price'],
                    'currency' => 'BDT',
                    'status' => $data['status'],
                    'is_featured' => $data['is_featured'],
                    'published_at' => $data['status'] === Course::STATUS_PUBLISHED ? now() : null,
                ],
            );

            if ($teacher) {
                $course->instructors()->syncWithoutDetaching([
                    $teacher->id => ['role' => 'teacher'],
                ]);
            }

            $section = CourseSection::updateOrCreate(
                ['course_id' => $course->id, 'title' => 'Getting started'],
                [
                    'description' => 'Orientation and core concepts.',
                    'sort_order' => 0,
                    'status' => 'published',
                ],
            );

            $lessons = [
                ['title' => 'Welcome and course roadmap', 'type' => 'video', 'duration_minutes' => 8, 'is_preview' => true],
                ['title' => 'Core concepts explained', 'type' => 'video', 'duration_minutes' => 22, 'is_preview' => false],
                ['title' => 'Hands-on practice project', 'type' => 'text', 'duration_minutes' => 35, 'is_preview' => false],
            ];

            foreach ($lessons as $index => $lesson) {
                Lesson::updateOrCreate(
                    [
                        'course_section_id' => $section->id,
                        'slug' => Str::slug($lesson['title']),
                    ],
                    [
                        'title' => $lesson['title'],
                        'type' => $lesson['type'],
                        'description' => $lesson['title'].' walkthrough.',
                        'content' => '<p>Lesson content for '.$lesson['title'].'.</p>',
                        'duration_minutes' => $lesson['duration_minutes'],
                        'is_preview' => $lesson['is_preview'],
                        'sort_order' => $index,
                        'status' => 'published',
                        'published_at' => now(),
                    ],
                );
            }
        }
    }
}
