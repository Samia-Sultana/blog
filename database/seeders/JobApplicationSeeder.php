<?php

namespace Database\Seeders;

use App\Enums\StatusLabelEnum;
use App\Models\Application;
use App\Models\StatusLabel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobTitles = [
            'Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'DevOps Engineer',
            'QA Engineer',
            'Mobile App Developer',
            'UI/UX Designer',
            'Product Manager',
            'Project Manager',
            'System Analyst',
            'Database Administrator',
            'Cloud Engineer',
            'Machine Learning Engineer',
            'Data Scientist',
            'Security Engineer',
            'Business Analyst',
            'Technical Support Engineer',
            'Site Reliability Engineer',
            'Network Engineer',
        ];

        $textColors = [
            '#1f2937', // Gray-800
            '#111827', // Gray-900
            '#0f172a', // Slate-900
        ];

        $bgColors = [
            '#e0f2fe', // Sky-100
            '#dbeafe', // Blue-100
            '#e5e7eb', // Gray-200
            '#f3f4f6', // Gray-100
            '#f0f9ff', // Light Blue
        ];

        foreach ($jobTitles as $title) {
            StatusLabel::create([
                'name' => $title,
                'type' => StatusLabelEnum::DEPARTMENT->value,
                'text_color' => $textColors[array_rand($textColors)],
                'background_color' => $bgColors[array_rand($bgColors)],
            ]);
        }

        $positionLabelIds = StatusLabel::where('type', StatusLabelEnum::DEPARTMENT->value)->pluck('id')->toArray();

        $jobStatuses = [
            'Pending',
            'Shortlisted',
            'Interview Scheduled',
            'Interviewed',
            'Offered',
            'Accepted',
            'Rejected',
            'Hired',
        ];
        foreach ($jobStatuses as $index => $status) {
            StatusLabel::create([
                'name' => $status,
                'type' => StatusLabelEnum::STATUS->value,
                'text_color' => $textColors[array_rand($textColors)],
                'background_color' => $bgColors[array_rand($bgColors)],
            ]);
        }

        $statusLabelIds = StatusLabel::where('type', StatusLabelEnum::STATUS->value)->pluck('id')->toArray();

        for ($i=0; $i < 100; $i++) {
            Application::create([
                'name' => "name{$i}",
                'email' => "email{$i}@gmail.com",
                'contact' => '01751149979',
                'location' => 'Bangladesh',
                'position_id' => $positionLabelIds[array_rand($positionLabelIds)],
                'expected_salary' => '1,000',
                'experience' => '1 Year',
                'cv_file' => 'storage/cv_files/VISER X Company Profile.pdf',
                'label_status_id' => $statusLabelIds[array_rand($statusLabelIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
