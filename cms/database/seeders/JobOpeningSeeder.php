<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobOpening;

class JobOpeningSeeder extends Seeder
{
    public function run()
    {
        $jobOpenings = [
            [
                'job_title' => 'Software Developer',
                'shift' => 'Day',
                'salary' => 45000.00,
                'job_timing' => '9:00 AM - 6:00 PM',
                'estimated_time_to_hire' => 15,
                'job_description' => 'We are looking for a skilled Software Developer to join our team. The ideal candidate should have experience in web development, database management, and problem-solving skills.',
                'status' => 'active'
            ],
            [
                'job_title' => 'Customer Support',
                'shift' => 'Night',
                'salary' => 25000.00,
                'job_timing' => '10:00 PM - 7:00 AM',
                'estimated_time_to_hire' => 7,
                'job_description' => 'Looking for a dedicated Customer Support representative to handle customer inquiries and provide excellent service during night hours.',
                'status' => 'active'
            ],
            [
                'job_title' => 'Data Analyst',
                'shift' => 'Day',
                'salary' => 40000.00,
                'job_timing' => '10:00 AM - 7:00 PM',
                'estimated_time_to_hire' => 20,
                'job_description' => 'Seeking a Data Analyst to analyze business data, create reports, and provide insights to help drive business decisions.',
                'status' => 'active'
            ]
        ];

        foreach ($jobOpenings as $job) {
            JobOpening::create($job);
        }
    }
}