<?php

namespace Database\Seeders;

use App\Models\AcademicLevel;
use App\Models\AcademicsOverview;
use Illuminate\Database\Seeder;

class AcademicsSeeder extends Seeder
{
    public function run(): void
    {
        AcademicsOverview::updateOrCreate(['id' => 1], [
            'text'       => 'At Hulhudhuffaaru School, academic learning is built on strong foundations, clear standards, and internationally recognised pathways. We follow the Maldives National Curriculum and strengthen our subject syllabi with Cambridge International and Pearson Edexcel programmes, giving students both local grounding and global academic options.',
            'curriculum' => 'Maldives National Curriculum · Cambridge · Pearson Edexcel',
        ]);

        $levels = [
            [
                'sort_order'  => 1,
                'abbreviation'=> 'FS',
                'label'       => 'Foundation Stage',
                'age_range'   => 'Ages 4 – 5',
                'year_groups' => 'LKG – UKG',
                'lead_teacher'=> 'Ms. Aishath Shifna',
                'subjects'    => ['Early literacy', 'Early numeracy', 'Islam', 'Dhivehi', 'English', 'Creative play'],
                'targets'     => ['School readiness', 'Confidence and routines', 'Language and social development'],
                'streams'     => null,
                'is_active'   => true,
            ],
            [
                'sort_order'  => 2,
                'abbreviation'=> 'KS1',
                'label'       => 'Key Stage 1',
                'age_range'   => 'Ages 6 – 8',
                'year_groups' => 'Grade 1 – Grade 3',
                'lead_teacher'=> 'Ms. Mariyam Sana',
                'subjects'    => ['Dhivehi', 'English', 'Mathematics', 'Islam', 'Environmental Studies', 'Creative Arts'],
                'targets'     => ['Reading fluency', 'Core numeracy', 'Positive learning habits'],
                'streams'     => null,
                'is_active'   => true,
            ],
            [
                'sort_order'  => 3,
                'abbreviation'=> 'KS2',
                'label'       => 'Key Stage 2',
                'age_range'   => 'Ages 9 – 11',
                'year_groups' => 'Grade 4 – Grade 6',
                'lead_teacher'=> 'Mr. Ahmed Niyaz',
                'subjects'    => ['Dhivehi', 'English', 'Mathematics', 'Science', 'Social Studies', 'ICT'],
                'targets'     => ['Independent learning', 'Problem solving', 'Strong primary foundations'],
                'streams'     => null,
                'is_active'   => true,
            ],
            [
                'sort_order'  => 4,
                'abbreviation'=> 'KS3',
                'label'       => 'Key Stage 3',
                'age_range'   => 'Ages 12 – 13',
                'year_groups' => 'Grade 7 – Grade 8',
                'lead_teacher'=> 'Ms. Fathimath Shazna',
                'subjects'    => ['English', 'Mathematics', 'Science', 'Dhivehi', 'Islam', 'Humanities', 'ICT'],
                'targets'     => ['Secondary transition', 'Subject confidence', 'Stream readiness'],
                'streams'     => null,
                'is_active'   => true,
            ],
            [
                'sort_order'  => 5,
                'abbreviation'=> 'KS4',
                'label'       => 'Key Stage 4',
                'age_range'   => 'Ages 14 – 15',
                'year_groups' => 'Grade 9 – Grade 10',
                'lead_teacher'=> 'Mr. Ali Shareef',
                'subjects'    => ['English', 'Mathematics', 'Islam', 'Dhivehi', 'Science subjects', 'Business subjects'],
                'targets'     => ['IGCSE readiness', 'Stream mastery', 'Exam discipline'],
                'streams'     => ['Science Stream', 'Business Stream'],
                'is_active'   => true,
            ],
            [
                'sort_order'  => 6,
                'abbreviation'=> 'KS5',
                'label'       => 'Key Stage 5',
                'age_range'   => 'Ages 16 – 17',
                'year_groups' => 'Grade 11 – Grade 12',
                'lead_teacher'=> 'Ms. Aminath Rifa',
                'subjects'    => ['Advanced Mathematics', 'Biology / Chemistry / Physics', 'Accounting', 'Business', 'Economics', 'English'],
                'targets'     => ['A Level readiness', 'University pathways', 'Scholarship preparation'],
                'streams'     => ['Science Stream', 'Business Stream'],
                'is_active'   => true,
            ],
        ];

        foreach ($levels as $level) {
            AcademicLevel::updateOrCreate(
                ['abbreviation' => $level['abbreviation']],
                $level
            );
        }
    }
}
