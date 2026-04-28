<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@learnify.com'],
            [
                'first_name' => 'System',
                'last_name'  => 'Administrator',
                'password'   => Hash::make('Admin@1234'),
                'role'       => 'admin',
                'is_active'  => true,
            ]
        );

        // ── Instructors ───────────────────────────────────────────────────────
        $instructors = [];
        foreach ([
            ['Havish', 'Kumar',   'havish@learnify.com'],
            ['Julien', 'Bernard', 'julien@learnify.com'],
        ] as [$first, $last, $email]) {
            $instructors[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $first,
                    'last_name'  => $last,
                    'password'   => Hash::make('Instructor@1234'),
                    'role'       => 'instructor',
                    'is_active'  => true,
                ]
            );
        }

        // ── Students ──────────────────────────────────────────────────────────
        $students = [];
        foreach ([
            ['Alice',  'Martin',   'alice@example.com'],
            ['Bob',    'Johnson',  'bob@example.com'],
            ['Carol',  'Williams', 'carol@example.com'],
        ] as [$first, $last, $email]) {
            $students[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $first,
                    'last_name'  => $last,
                    'password'   => Hash::make('Student@1234'),
                    'role'       => 'student',
                    'is_active'  => true,
                ]
            );
        }

        // ── Courses ───────────────────────────────────────────────────────────
        $courseData = [
            [
                'title'        => 'Web Development Fundamentals',
                'description'  => 'Master essential web skills and build real-world projects. Perfect for beginners starting their coding journey. Covers HTML, CSS, JavaScript, and responsive design principles.',
                'price'        => 50.00,
                'category'     => 'Web Development',
                'duration'     => '12 hours',
                'rating'       => 4.8,
                'reviews_count'=> 100,
                'is_published' => true,
            ],
            [
                'title'        => 'Graphic Design Essentials',
                'description'  => 'Learn industry-standard design tools and create stunning visuals. Build your portfolio with real projects using Figma, Adobe XD, and design fundamentals.',
                'price'        => 50.00,
                'category'     => 'Design',
                'duration'     => '8 hours',
                'rating'       => 4.7,
                'reviews_count'=> 70,
                'is_published' => true,
            ],
            [
                'title'        => 'Data Science with Python',
                'description'  => 'Master Python for data analysis, visualisation, and machine learning. Work with real datasets and build predictive models using pandas, NumPy, and scikit-learn.',
                'price'        => 120.00,
                'original_price' => 150.00,
                'category'     => 'Data Science',
                'duration'     => '20 hours',
                'rating'       => 4.9,
                'reviews_count'=> 10,
                'is_published' => true,
            ],
            [
                'title'        => 'Digital Marketing Mastery',
                'description'  => 'Learn data-driven marketing strategies, analytics, and campaign optimisation for business growth. Covers SEO, social media, and Google Ads.',
                'price'        => 75.00,
                'category'     => 'Marketing',
                'duration'     => '10 hours',
                'rating'       => 4.6,
                'reviews_count'=> 90,
                'is_published' => true,
            ],
            [
                'title'        => 'Cybersecurity Fundamentals',
                'description'  => 'Understand the core principles of cybersecurity. Learn about network security, ethical hacking basics, and how to protect digital assets from common threats.',
                'price'        => 95.00,
                'category'     => 'Cybersecurity',
                'duration'     => '15 hours',
                'rating'       => 4.7,
                'reviews_count'=> 55,
                'is_published' => true,
            ],
        ];

        $courses = [];
        foreach ($courseData as $i => $data) {
            $instructor = $instructors[$i % count($instructors)];
            $courses[]  = Course::firstOrCreate(
                ['title' => $data['title']],
                array_merge($data, ['instructor_id' => $instructor->id])
            );
        }

        // ── Sample enrollments ────────────────────────────────────────────────
        foreach ($students as $student) {
            foreach (array_slice($courses, 0, 2) as $course) {
                Enrollment::firstOrCreate(
                    ['student_id' => $student->id, 'course_id' => $course->id],
                    [
                        'completion_status' => 'in_progress',
                        'progress'          => rand(10, 80),
                        'enrolled_at'       => now()->subDays(rand(1, 30)),
                    ]
                );
            }
        }
    }
}
