<?php

namespace Database\Seeders;

use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceResource;
use App\Models\DigitalServiceCalendarEntry;
use Illuminate\Database\Seeder;

class DigitalServicesSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            ['title' => 'Google Classroom', 'description' => 'Access your class assignments, submit work, and communicate with teachers through the school\'s learning management system.', 'audience' => 'Students', 'icon' => 'BookOpen', 'icon_color' => 'sky', 'url' => 'https://classroom.google.com', 'sort_order' => 1],
            ['title' => 'Student Results Portal', 'description' => 'View term-end report cards, exam scores, and academic progress summaries for current and past sessions.', 'audience' => 'Students', 'icon' => 'BarChart2', 'icon_color' => 'rose', 'url' => 'https://results.schoolportal.ng', 'sort_order' => 2],
            ['title' => 'Parent Dashboard', 'description' => 'Monitor your child\'s attendance, academic performance, fee payment status, and receive school notifications.', 'audience' => 'Parents', 'icon' => 'Users', 'icon_color' => 'emerald', 'url' => 'https://parents.schoolportal.ng', 'sort_order' => 3],
            ['title' => 'Fee Payment Portal', 'description' => 'Pay school fees securely online using bank transfer, card, or USSD. Download receipts and view payment history.', 'audience' => 'Parents', 'icon' => 'CreditCard', 'icon_color' => 'amber', 'url' => 'https://fees.schoolportal.ng', 'sort_order' => 4],
            ['title' => 'Digital Library', 'description' => 'Browse and borrow from thousands of e-books, academic journals, and reference materials available 24/7.', 'audience' => 'Students', 'icon' => 'Library', 'icon_color' => 'violet', 'url' => 'https://library.schoolportal.ng', 'sort_order' => 5],
            ['title' => 'Staff Portal', 'description' => 'Internal platform for staff to manage class registers, submit grades, access HR documents, and view announcements.', 'audience' => 'Staff', 'icon' => 'Briefcase', 'icon_color' => 'slate', 'url' => 'https://staff.schoolportal.ng', 'sort_order' => 6],
            ['title' => 'School Email (Google Workspace)', 'description' => 'Access your official school email address and Google Drive for school communications and document collaboration.', 'audience' => 'Students', 'icon' => 'Mail', 'icon_color' => 'sky', 'url' => 'https://mail.google.com', 'sort_order' => 7],
            ['title' => 'CBT Examination Platform', 'description' => 'The school\'s Computer-Based Testing platform used for internal assessments, mock exams, and practice tests.', 'audience' => 'Students', 'icon' => 'ClipboardList', 'icon_color' => 'rose', 'url' => 'https://cbt.schoolportal.ng', 'sort_order' => 8],
            ['title' => 'School Notice Board', 'description' => 'Stay up to date with the latest school announcements, circulars, and official notices published by the administration.', 'audience' => 'All', 'icon' => 'Bell', 'icon_color' => 'amber', 'url' => 'https://notices.schoolportal.ng', 'sort_order' => 9],
        ];

        foreach ($resources as $data) {
            DigitalServiceResource::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, ['is_active' => true])
            );
        }

        $documents = [
            ['title' => 'New Student Enrolment Form', 'category' => 'Forms & Applications', 'file_type' => 'PDF', 'file_size' => '245 KB', 'audience' => 'Parents', 'published_at' => '2024-08-01', 'sort_order' => 1],
            ['title' => 'Student Leave of Absence Request Form', 'category' => 'Forms & Applications', 'file_type' => 'PDF', 'file_size' => '118 KB', 'audience' => 'Parents', 'published_at' => '2024-08-01', 'sort_order' => 2],
            ['title' => 'Medical / Health Information Form', 'category' => 'Forms & Applications', 'file_type' => 'PDF', 'file_size' => '189 KB', 'audience' => 'Parents', 'published_at' => '2024-08-01', 'sort_order' => 3],
            ['title' => 'Extracurricular Club Registration Form', 'category' => 'Forms & Applications', 'file_type' => 'DOCX', 'file_size' => '92 KB', 'audience' => 'Students', 'published_at' => '2024-09-02', 'sort_order' => 4],
            ['title' => 'Student Handbook 2024–2025', 'category' => 'Policies & Handbooks', 'file_type' => 'PDF', 'file_size' => '1.4 MB', 'audience' => 'Students', 'published_at' => '2024-08-15', 'sort_order' => 5],
            ['title' => 'Parent & Guardian Information Guide', 'category' => 'Policies & Handbooks', 'file_type' => 'PDF', 'file_size' => '876 KB', 'audience' => 'Parents', 'published_at' => '2024-08-15', 'sort_order' => 6],
            ['title' => 'Code of Conduct & Disciplinary Policy', 'category' => 'Policies & Handbooks', 'file_type' => 'PDF', 'file_size' => '512 KB', 'audience' => 'Parents', 'published_at' => '2024-07-20', 'sort_order' => 7],
            ['title' => 'ICT & Acceptable Use Policy', 'category' => 'Policies & Handbooks', 'file_type' => 'PDF', 'file_size' => '304 KB', 'audience' => 'Students', 'published_at' => '2024-07-20', 'sort_order' => 8],
            ['title' => 'JSS Class Timetable — 2024/2025 Session', 'category' => 'Timetables & Schedules', 'file_type' => 'PDF', 'file_size' => '198 KB', 'audience' => 'Students', 'published_at' => '2024-09-09', 'sort_order' => 9],
            ['title' => 'SSS Class Timetable — 2024/2025 Session', 'category' => 'Timetables & Schedules', 'file_type' => 'PDF', 'file_size' => '214 KB', 'audience' => 'Students', 'published_at' => '2024-09-09', 'sort_order' => 10],
            ['title' => 'First Term Examination Schedule 2024', 'category' => 'Timetables & Schedules', 'file_type' => 'PDF', 'file_size' => '156 KB', 'audience' => 'Students', 'published_at' => '2024-10-28', 'sort_order' => 11],
            ['title' => 'Academic Calendar — Term Dates 2024/2025', 'category' => 'Timetables & Schedules', 'file_type' => 'PDF', 'file_size' => '134 KB', 'audience' => 'Parents', 'published_at' => '2024-07-15', 'sort_order' => 12],
            ['title' => 'WAEC Syllabus — Core Subjects 2025', 'category' => 'Academic Resources', 'file_type' => 'PDF', 'file_size' => '2.1 MB', 'audience' => 'Students', 'published_at' => '2024-08-20', 'sort_order' => 13],
            ['title' => 'Mathematics Past Examination Papers (2019–2023)', 'category' => 'Academic Resources', 'file_type' => 'PDF', 'file_size' => '3.8 MB', 'audience' => 'Students', 'published_at' => '2024-08-20', 'sort_order' => 14],
            ['title' => 'English Language Past Papers & Model Answers', 'category' => 'Academic Resources', 'file_type' => 'PDF', 'file_size' => '2.6 MB', 'audience' => 'Students', 'published_at' => '2024-08-20', 'sort_order' => 15],
            ['title' => 'SSS Revision & Study Skills Guide', 'category' => 'Academic Resources', 'file_type' => 'PDF', 'file_size' => '445 KB', 'audience' => 'Students', 'published_at' => '2024-09-15', 'sort_order' => 16],
        ];

        foreach ($documents as $data) {
            DigitalServiceDocument::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, ['is_active' => true])
            );
        }

        $calendarEntries = [
            ['title' => 'Second Term Begins', 'date' => '2025-01-06', 'end_date' => null, 'type' => 'term', 'description' => 'Students resume for the second term of the 2024/2025 academic session.', 'sort_order' => 1],
            ['title' => 'Mock WAEC Examinations', 'date' => '2025-01-20', 'end_date' => '2025-01-31', 'type' => 'exam', 'description' => 'Internal mock examinations for SSS 3 students in preparation for WAEC.', 'sort_order' => 2],
            ['title' => 'Mid-Term Break', 'date' => '2025-02-21', 'end_date' => '2025-02-28', 'type' => 'holiday', 'description' => 'One-week mid-term break for all students.', 'sort_order' => 3],
            ['title' => 'Parents\' Visiting Day', 'date' => '2025-03-08', 'end_date' => null, 'type' => 'event', 'description' => 'Annual parents\' day — parents are invited to meet teachers and review progress reports.', 'sort_order' => 4],
            ['title' => 'Second Term Examinations', 'date' => '2025-03-17', 'end_date' => '2025-03-28', 'type' => 'exam', 'description' => 'End-of-term examinations for all classes.', 'sort_order' => 5],
            ['title' => 'Second Term Ends', 'date' => '2025-04-04', 'end_date' => null, 'type' => 'term', 'description' => 'Last day of the second term. Long vacation begins.', 'sort_order' => 6],
            ['title' => 'Third Term Begins', 'date' => '2025-04-28', 'end_date' => null, 'type' => 'term', 'description' => 'Students resume for the third and final term of the 2024/2025 session.', 'sort_order' => 7],
            ['title' => 'WAEC/NECO Examinations Begin', 'date' => '2025-05-05', 'end_date' => '2025-06-20', 'type' => 'exam', 'description' => 'West African Senior School Certificate Examination (WASSCE) for SSS 3 students.', 'sort_order' => 8],
            ['title' => 'Annual Sports Day', 'date' => '2025-05-16', 'end_date' => null, 'type' => 'event', 'description' => 'Inter-house sports competition held on the school field. All students participate.', 'sort_order' => 9],
            ['title' => 'Graduation & Prize Giving Ceremony', 'date' => '2025-06-27', 'end_date' => null, 'type' => 'event', 'description' => 'Annual graduation ceremony for the SSS 3 graduating class.', 'sort_order' => 10],
            ['title' => 'Third Term Ends — Long Vacation', 'date' => '2025-07-18', 'end_date' => null, 'type' => 'term', 'description' => 'End of the 2024/2025 academic session. Long vacation begins.', 'sort_order' => 11],
        ];

        foreach ($calendarEntries as $data) {
            DigitalServiceCalendarEntry::updateOrCreate(
                ['title' => $data['title'], 'date' => $data['date']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
