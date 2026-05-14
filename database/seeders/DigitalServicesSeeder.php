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

        // Replace all calendar entries with Ministry of Education Academic Calendar 2026 (TENTATIVE 10.08.2025)
        DigitalServiceCalendarEntry::truncate();

        $calendarEntries = [
            // January
            ['title' => 'New Year 2026',                          'date' => '2026-01-01', 'end_date' => null,         'type' => 'holiday', 'description' => 'Public holiday.',                                                                        'sort_order' => 1],
            ['title' => 'Teachers\' Reporting Day',               'date' => '2026-01-25', 'end_date' => null,         'type' => 'event',   'description' => 'Teachers report for the start of the 2026 academic year.',                                 'sort_order' => 2],
            ['title' => 'Beginning of Academic Year 2026',        'date' => '2026-01-27', 'end_date' => null,         'type' => 'term',    'description' => 'First day of Term 1, Academic Year 2026.',                                                'sort_order' => 3],
            // February
            ['title' => 'First of Ramadan',                       'date' => '2026-02-18', 'end_date' => null,         'type' => 'holiday', 'description' => 'First day of the holy month of Ramadan.',                                                 'sort_order' => 4],
            // March
            ['title' => 'Professional Development Days',          'date' => '2026-03-01', 'end_date' => '2026-03-08', 'type' => 'event',   'description' => 'School-wide professional development days for teaching staff.',                           'sort_order' => 5],
            ['title' => 'Last 10 Days of Ramadan',                'date' => '2026-03-09', 'end_date' => '2026-03-19', 'type' => 'holiday', 'description' => 'School closed for the last 10 days of Ramadan.',                                          'sort_order' => 6],
            ['title' => 'Eid-al-Fitr',                            'date' => '2026-03-20', 'end_date' => null,         'type' => 'holiday', 'description' => 'Eid-al-Fitr public holiday.',                                                             'sort_order' => 7],
            ['title' => 'Eid-al-Fitr Holiday',                    'date' => '2026-03-21', 'end_date' => '2026-03-22', 'type' => 'holiday', 'description' => 'School closed on the occasion of Eid-al-Fitr.',                                           'sort_order' => 8],
            // May
            ['title' => 'Labour Day',                             'date' => '2026-05-01', 'end_date' => null,         'type' => 'holiday', 'description' => 'International Labour Day — public holiday.',                                              'sort_order' => 9],
            ['title' => 'Children\'s Day',                        'date' => '2026-05-10', 'end_date' => null,         'type' => 'event',   'description' => 'Children\'s Day — half-day of teaching.',                                                'sort_order' => 10],
            ['title' => 'School Transfer Period 1',                'date' => '2026-05-17', 'end_date' => '2026-06-15', 'type' => 'event',   'description' => 'First school transfer period of the academic year.',                                      'sort_order' => 11],
            ['title' => 'First Term Mid-Break',                   'date' => '2026-05-24', 'end_date' => '2026-05-30', 'type' => 'holiday', 'description' => 'One-week mid-term break for Term 1.',                                                      'sort_order' => 12],
            ['title' => 'Hajj Day',                               'date' => '2026-05-26', 'end_date' => null,         'type' => 'holiday', 'description' => 'Hajj Day — public holiday.',                                                              'sort_order' => 13],
            ['title' => 'Eid-al-Adha',                            'date' => '2026-05-27', 'end_date' => null,         'type' => 'holiday', 'description' => 'Eid-al-Adha public holiday.',                                                             'sort_order' => 14],
            ['title' => 'Eid-al-Adha Holiday',                    'date' => '2026-05-28', 'end_date' => '2026-05-30', 'type' => 'holiday', 'description' => 'School closed on the occasion of Eid-al-Adha.',                                           'sort_order' => 15],
            // June
            ['title' => 'Beginning of AL Batch 2026',             'date' => '2026-06-07', 'end_date' => null,         'type' => 'event',   'description' => 'Advanced Level (AL) Batch 2026 begins their studies.',                                    'sort_order' => 16],
            ['title' => 'Islamic New Year 1448',                  'date' => '2026-06-16', 'end_date' => null,         'type' => 'holiday', 'description' => 'Islamic New Year 1448 — public holiday.',                                                 'sort_order' => 17],
            // July
            ['title' => 'First Term Examinations',                'date' => '2026-06-30', 'end_date' => '2026-07-09', 'type' => 'exam',    'description' => 'End-of-term examinations for all classes — Term 1, Academic Year 2026.',                  'sort_order' => 18],
            ['title' => 'End of First Term 2026',                 'date' => '2026-07-16', 'end_date' => null,         'type' => 'term',    'description' => 'Last day of Term 1, Academic Year 2026.',                                                 'sort_order' => 19],
            ['title' => 'First Term Holidays',                    'date' => '2026-07-17', 'end_date' => '2026-08-01', 'type' => 'holiday', 'description' => 'Term 1 vacation — 16 days.',                                                              'sort_order' => 20],
            ['title' => 'Independence Day',                       'date' => '2026-07-26', 'end_date' => null,         'type' => 'holiday', 'description' => 'Independence Day of the Republic of Maldives — public holiday.',                          'sort_order' => 21],
            ['title' => 'Independence Day Holiday',               'date' => '2026-07-27', 'end_date' => null,         'type' => 'holiday', 'description' => 'Public holiday on the occasion of Independence Day.',                                      'sort_order' => 22],
            // August
            ['title' => 'Beginning of Second Term 2026',          'date' => '2026-08-02', 'end_date' => null,         'type' => 'term',    'description' => 'First day of Term 2, Academic Year 2026.',                                                'sort_order' => 23],
            ['title' => 'National Day',                           'date' => '2026-08-14', 'end_date' => null,         'type' => 'holiday', 'description' => 'National Day — public holiday.',                                                          'sort_order' => 24],
            ['title' => 'Camps and Activities (KS1–KS4)',         'date' => '2026-08-22', 'end_date' => '2026-08-25', 'type' => 'event',   'description' => 'School camps and extracurricular activities for Key Stage 1 to Key Stage 4 students.',   'sort_order' => 25],
            ['title' => 'Prophet Muhammad\'s (ﷺ) Birthday',      'date' => '2026-08-25', 'end_date' => null,         'type' => 'holiday', 'description' => 'Prophet Muhammad\'s (ﷺ) Birthday — public holiday.',                                     'sort_order' => 26],
            // September
            ['title' => 'Second Term Mid-Break',                  'date' => '2026-09-13', 'end_date' => '2026-09-19', 'type' => 'holiday', 'description' => 'One-week mid-term break for Term 2.',                                                     'sort_order' => 27],
            ['title' => 'The Day Maldives Embraced Islam',        'date' => '2026-09-13', 'end_date' => null,         'type' => 'holiday', 'description' => 'Public holiday celebrating the day Maldives embraced Islam.',                              'sort_order' => 28],
            // October
            ['title' => 'Teachers\' Day',                         'date' => '2026-10-05', 'end_date' => null,         'type' => 'event',   'description' => 'Teachers\' Day — half-day of teaching.',                                                 'sort_order' => 29],
            ['title' => 'School Transfer Period 2',               'date' => '2026-10-18', 'end_date' => '2026-11-17', 'type' => 'event',   'description' => 'Second school transfer period of the academic year.',                                     'sort_order' => 30],
            // November
            ['title' => 'Victory Day',                            'date' => '2026-11-03', 'end_date' => null,         'type' => 'holiday', 'description' => 'Victory Day — public holiday.',                                                           'sort_order' => 31],
            ['title' => 'Republic Day',                           'date' => '2026-11-11', 'end_date' => null,         'type' => 'holiday', 'description' => 'Republic Day — public holiday.',                                                          'sort_order' => 32],
            ['title' => 'Professional Development Day',           'date' => '2026-11-12', 'end_date' => null,         'type' => 'event',   'description' => 'Professional development day for teaching staff.',                                         'sort_order' => 33],
            // December
            ['title' => 'Second Term Examinations',               'date' => '2026-12-01', 'end_date' => '2026-12-10', 'type' => 'exam',    'description' => 'End-of-term examinations for all classes — Term 2, Academic Year 2026.',                  'sort_order' => 34],
            ['title' => 'End of Second Term 2026',                'date' => '2026-12-17', 'end_date' => null,         'type' => 'term',    'description' => 'Last day of Term 2, Academic Year 2026.',                                                 'sort_order' => 35],
            ['title' => 'Second Term Holidays',                   'date' => '2026-12-18', 'end_date' => '2027-01-12', 'type' => 'holiday', 'description' => 'Term 2 vacation — 26 days.',                                                              'sort_order' => 36],
        ];

        foreach ($calendarEntries as $data) {
            DigitalServiceCalendarEntry::create(array_merge($data, ['is_active' => true]));
        }
    }
}
