<?php

namespace Database\Seeders;

use App\Models\StaffMember;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            ['name' => 'Fathimath Shazna', 'designation' => 'Principal', 'education' => 'M.Ed. Educational Leadership', 'section' => 'senior_management', 'sub_section' => null],
            ['name' => 'Ahmed Rasheed', 'designation' => 'Deputy Principal', 'education' => 'M.Ed. School Management', 'section' => 'senior_management', 'sub_section' => null],
            ['name' => 'Aishath Niuma', 'designation' => 'Assistant Principal', 'education' => 'B.Ed. Primary Education', 'section' => 'senior_management', 'sub_section' => null],
            ['name' => 'Mohamed Nabeel', 'designation' => 'Assistant Principal', 'education' => 'B.Ed. Secondary Education', 'section' => 'senior_management', 'sub_section' => null],
            ['name' => 'Mariyam Haleema', 'designation' => 'School Administrator', 'education' => 'BBA Management', 'section' => 'senior_management', 'sub_section' => null],

            ['name' => 'Fathimath Raniya', 'designation' => 'Leading Teacher - Foundation Stage', 'education' => 'Diploma in Early Childhood Education', 'section' => 'academic', 'sub_section' => 'leading_teachers'],
            ['name' => 'Aishath Sama', 'designation' => 'Leading Teacher - Key Stage 1', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'leading_teachers'],
            ['name' => 'Hussain Zahir', 'designation' => 'Leading Teacher - Key Stage 2', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'leading_teachers'],
            ['name' => 'Nasha Ibrahim', 'designation' => 'Leading Teacher - Key Stage 3', 'education' => 'B.Ed. English', 'section' => 'academic', 'sub_section' => 'leading_teachers'],
            ['name' => 'Ali Shifan', 'designation' => 'Leading Teacher - Key Stage 4', 'education' => 'B.Sc. Science Education', 'section' => 'academic', 'sub_section' => 'leading_teachers'],
            ['name' => 'Muna Mohamed', 'designation' => 'Leading Teacher - Key Stage 5', 'education' => 'M.Sc. Business Education', 'section' => 'academic', 'sub_section' => 'leading_teachers'],

            ['name' => 'Zainab Latheef', 'designation' => 'Class Teacher - LKG', 'education' => 'Diploma in Early Childhood Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aminath Rizna', 'designation' => 'Class Teacher - UKG', 'education' => 'Diploma in Early Childhood Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Hawwa Ilyas', 'designation' => 'Class Teacher - Grade 1', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Fathimath Yumna', 'designation' => 'Class Teacher - Grade 2', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aishath Maira', 'designation' => 'Class Teacher - Grade 3', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ibrahim Firaq', 'designation' => 'Class Teacher - Grade 4', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Mariyam Shaheema', 'designation' => 'Class Teacher - Grade 5', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ahmed Fazeel', 'designation' => 'Class Teacher - Grade 6', 'education' => 'B.Ed. Primary Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aishath Safa', 'designation' => 'English Teacher', 'education' => 'B.A. English Language', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Mohamed Samaah', 'designation' => 'English Teacher', 'education' => 'B.A. English Literature', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Niuma Shakir', 'designation' => 'Dhivehi Teacher', 'education' => 'B.A. Dhivehi Language', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Fathimath Zuleikha', 'designation' => 'Dhivehi Teacher', 'education' => 'B.A. Dhivehi Language', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Hussain Anwar', 'designation' => 'Mathematics Teacher', 'education' => 'B.Sc. Mathematics', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aishath Noora', 'designation' => 'Mathematics Teacher', 'education' => 'B.Sc. Mathematics', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ali Sameer', 'designation' => 'Physics Teacher', 'education' => 'B.Sc. Physics', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Mariyam Fazla', 'designation' => 'Chemistry Teacher', 'education' => 'B.Sc. Chemistry', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ahmed Shiyam', 'designation' => 'Biology Teacher', 'education' => 'B.Sc. Biology', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aminath Aaliya', 'designation' => 'Business Studies Teacher', 'education' => 'BBA Business Studies', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ibrahim Naail', 'designation' => 'Accounting Teacher', 'education' => 'B.Com. Accounting', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Hawwa Nasreen', 'designation' => 'Economics Teacher', 'education' => 'B.A. Economics', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Mohamed Yameen', 'designation' => 'Computer Science Teacher', 'education' => 'B.Sc. Computer Science', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aishath Zaha', 'designation' => 'Islam Teacher', 'education' => 'B.A. Islamic Studies', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ahmed Junaid', 'designation' => 'Quran Teacher', 'education' => 'Diploma in Quran Studies', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Fathimath Shaffa', 'designation' => 'Social Studies Teacher', 'education' => 'B.A. Social Studies', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Hussain Waheed', 'designation' => 'History Teacher', 'education' => 'B.A. History', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Mariyam Liyana', 'designation' => 'Geography Teacher', 'education' => 'B.A. Geography', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ali Ziyad', 'designation' => 'Physical Education Teacher', 'education' => 'Diploma in Physical Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Aishath Lina', 'designation' => 'Art Teacher', 'education' => 'Diploma in Fine Arts', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Mohamed Raif', 'designation' => 'Design and Technology Teacher', 'education' => 'B.Tech. Education', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Nuzha Ahmed', 'designation' => 'Health Science Teacher', 'education' => 'B.Sc. Health Science', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Ahmed Fikry', 'designation' => 'Additional Mathematics Teacher', 'education' => 'B.Sc. Mathematics', 'section' => 'academic', 'sub_section' => 'teachers'],
            ['name' => 'Fathimath Reema', 'designation' => 'ESL Support Teacher', 'education' => 'B.A. English Language Teaching', 'section' => 'academic', 'sub_section' => 'academic_support'],
            ['name' => 'Ibrahim Zayaan', 'designation' => 'Learning Support Teacher', 'education' => 'Diploma in Inclusive Education', 'section' => 'academic', 'sub_section' => 'academic_support'],
            ['name' => 'Aishath Thahira', 'designation' => 'Laboratory Technician', 'education' => 'Diploma in Laboratory Technology', 'section' => 'academic', 'sub_section' => 'laboratory'],
            ['name' => 'Mohamed Nihad', 'designation' => 'Laboratory Assistant', 'education' => 'Certificate in Laboratory Practice', 'section' => 'academic', 'sub_section' => 'laboratory'],
            ['name' => 'Mariyam Fathuna', 'designation' => 'Librarian', 'education' => 'Diploma in Library Studies', 'section' => 'academic', 'sub_section' => 'library'],
            ['name' => 'Aishath Nadha', 'designation' => 'Assistant Librarian', 'education' => 'Certificate in Library Studies', 'section' => 'academic', 'sub_section' => 'library'],

            ['name' => 'Ahmed Hameed', 'designation' => 'HR Officer', 'education' => 'BBA Human Resource Management', 'section' => 'administrative', 'sub_section' => 'hr'],
            ['name' => 'Aishath Shaaira', 'designation' => 'Administrative Officer', 'education' => 'Diploma in Office Administration', 'section' => 'administrative', 'sub_section' => 'hr'],
            ['name' => 'Mohamed Habeeb', 'designation' => 'Finance Officer', 'education' => 'B.Com. Finance', 'section' => 'administrative', 'sub_section' => 'budget'],
            ['name' => 'Fathimath Maeesha', 'designation' => 'Accounts Assistant', 'education' => 'Diploma in Accounting', 'section' => 'administrative', 'sub_section' => 'budget'],
            ['name' => 'Ibrahim Aslam', 'designation' => 'IT Officer', 'education' => 'B.Sc. Information Technology', 'section' => 'administrative', 'sub_section' => 'it'],
            ['name' => 'Ahmed Saif', 'designation' => 'Network Assistant', 'education' => 'Diploma in Networking', 'section' => 'administrative', 'sub_section' => 'it'],
            ['name' => 'Mariyam Nisha', 'designation' => 'Printing Officer', 'education' => 'Certificate in Printing Operations', 'section' => 'administrative', 'sub_section' => 'printer'],
            ['name' => 'Ali Faiz', 'designation' => 'Printing Assistant', 'education' => 'Certificate in Office Operations', 'section' => 'administrative', 'sub_section' => 'printer'],
            ['name' => 'Aminath Nafa', 'designation' => 'Receptionist', 'education' => 'Diploma in Office Administration', 'section' => 'administrative', 'sub_section' => 'hr'],
            ['name' => 'Hussain Shareef', 'designation' => 'Facilities Coordinator', 'education' => 'Diploma in Facilities Management', 'section' => 'administrative', 'sub_section' => 'hr'],
            ['name' => 'Mariyam Zeena', 'designation' => 'Records Officer', 'education' => 'Diploma in Records Management', 'section' => 'administrative', 'sub_section' => 'hr'],
            ['name' => 'Ahmed Mishal', 'designation' => 'Procurement Assistant', 'education' => 'Diploma in Business Administration', 'section' => 'administrative', 'sub_section' => 'budget'],
        ];

        foreach ($staff as $index => $member) {
            StaffMember::updateOrCreate(
                ['name' => $member['name'], 'designation' => $member['designation']],
                [
                    'education' => $member['education'],
                    'photo_path' => null,
                    'section' => $member['section'],
                    'sub_section' => $member['sub_section'],
                    'work_experiences' => $this->experiencesFor($member),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }

    private function experiencesFor(array $member): array
    {
        return [
            [
                'title' => $member['designation'],
                'institution' => config('app.name'),
                'period' => 'Current',
            ],
        ];
    }
}
