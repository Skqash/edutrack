<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CollegeSubjectSeeder extends Seeder
{
    /**
     * College-level subjects organized by:
     * - Course (BSIT, BEED, BSHM, etc.)
     * - Year (1-4)
     * - Semester (1-2)
     * With college-level subject coding (PCIT 01, ED 01, HM 01, etc.)
     */
    public function run(): void
    {
        // BSIT Subjects (PCIT = Programming and Computing Information Technology)
        $bsitSubjects = [
            // Year 1, Semester 1
            ['code' => 'PCIT 01', 'name' => 'Introduction to Programming', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'PCIT 02', 'name' => 'Computer Fundamentals', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'PCIT 03', 'name' => 'Mathematics for Computing I', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'PCIT 04', 'name' => 'Introduction to Web Design', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'PCIT 05', 'name' => 'English Communication I', 'year' => 1, 'semester' => 1, 'credits' => 3],

            // Year 1, Semester 2
            ['code' => 'PCIT 06', 'name' => 'Object-Oriented Programming', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 07', 'name' => 'Data Structures', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 08', 'name' => 'Web Development Basics', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 09', 'name' => 'Database Design I', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 10', 'name' => 'English Communication II', 'year' => 1, 'semester' => 2, 'credits' => 3],

            // Year 2, Semester 1
            ['code' => 'PCIT 11', 'name' => 'Advanced Programming', 'year' => 2, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 12', 'name' => 'Algorithms and Complexity', 'year' => 2, 'semester' => 1, 'credits' => 3],
            ['code' => 'PCIT 13', 'name' => 'Database Management Systems', 'year' => 2, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 14', 'name' => 'Web Application Development', 'year' => 2, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 15', 'name' => 'Mathematics for Computing II', 'year' => 2, 'semester' => 1, 'credits' => 3],

            // Year 2, Semester 2
            ['code' => 'PCIT 16', 'name' => 'Software Engineering', 'year' => 2, 'semester' => 2, 'credits' => 4],
            ['code' => 'PCIT 17', 'name' => 'System Administration', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 18', 'name' => 'Network Fundamentals', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 19', 'name' => 'Mobile Application Development', 'year' => 2, 'semester' => 2, 'credits' => 4],
            ['code' => 'PCIT 20', 'name' => 'Information Security Basics', 'year' => 2, 'semester' => 2, 'credits' => 3],

            // Year 3, Semester 1
            ['code' => 'PCIT 21', 'name' => 'Enterprise Application Development', 'year' => 3, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 22', 'name' => 'Advanced Database Systems', 'year' => 3, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 23', 'name' => 'Cloud Computing', 'year' => 3, 'semester' => 1, 'credits' => 3],
            ['code' => 'PCIT 24', 'name' => 'Artificial Intelligence Fundamentals', 'year' => 3, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 25', 'name' => 'Cybersecurity I', 'year' => 3, 'semester' => 1, 'credits' => 3],

            // Year 3, Semester 2
            ['code' => 'PCIT 26', 'name' => 'Machine Learning Applications', 'year' => 3, 'semester' => 2, 'credits' => 4],
            ['code' => 'PCIT 27', 'name' => 'Advanced Networking', 'year' => 3, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 28', 'name' => 'Cybersecurity II', 'year' => 3, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 29', 'name' => 'DevOps and Automation', 'year' => 3, 'semester' => 2, 'credits' => 4],
            ['code' => 'PCIT 30', 'name' => 'Capstone Project I', 'year' => 3, 'semester' => 2, 'credits' => 3],

            // Year 4, Semester 1
            ['code' => 'PCIT 31', 'name' => 'Big Data Analytics', 'year' => 4, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 32', 'name' => 'Advanced Machine Learning', 'year' => 4, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 33', 'name' => 'Internet of Things (IoT)', 'year' => 4, 'semester' => 1, 'credits' => 4],
            ['code' => 'PCIT 34', 'name' => 'Professional Ethics in IT', 'year' => 4, 'semester' => 1, 'credits' => 2],
            ['code' => 'PCIT 35', 'name' => 'IT Project Management', 'year' => 4, 'semester' => 1, 'credits' => 3],

            // Year 4, Semester 2
            ['code' => 'PCIT 36', 'name' => 'Capstone Project II', 'year' => 4, 'semester' => 2, 'credits' => 6],
            ['code' => 'PCIT 37', 'name' => 'Advanced Topics in AI', 'year' => 4, 'semester' => 2, 'credits' => 3],
            ['code' => 'PCIT 38', 'name' => 'Enterprise Systems Integration', 'year' => 4, 'semester' => 2, 'credits' => 4],
            ['code' => 'PCIT 39', 'name' => 'Internship/Practicum', 'year' => 4, 'semester' => 2, 'credits' => 4],
            ['code' => 'PCIT 40', 'name' => 'Emerging Technologies', 'year' => 4, 'semester' => 2, 'credits' => 3],
        ];

        // BEED Subjects (ED = Education)
        $beedSubjects = [
            // Year 1, Semester 1
            ['code' => 'ED 01', 'name' => 'Introduction to Education', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 02', 'name' => 'Child Development and Psychology', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 03', 'name' => 'English Language Arts I', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 04', 'name' => 'Mathematics for Elementary I', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 05', 'name' => 'General Science Concepts', 'year' => 1, 'semester' => 1, 'credits' => 3],

            // Year 1, Semester 2
            ['code' => 'ED 06', 'name' => 'Teaching Methods I', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 07', 'name' => 'Classroom Management', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 08', 'name' => 'English Language Arts II', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 09', 'name' => 'Mathematics for Elementary II', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 10', 'name' => 'Social Studies for Elementary', 'year' => 1, 'semester' => 2, 'credits' => 3],

            // Year 2, Semester 1
            ['code' => 'ED 11', 'name' => 'Curriculum Design and Development', 'year' => 2, 'semester' => 1, 'credits' => 4],
            ['code' => 'ED 12', 'name' => 'Assessment and Evaluation', 'year' => 2, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 13', 'name' => 'Advanced Teaching Methods', 'year' => 2, 'semester' => 1, 'credits' => 4],
            ['code' => 'ED 14', 'name' => 'Inclusive Education', 'year' => 2, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 15', 'name' => 'Educational Technology', 'year' => 2, 'semester' => 1, 'credits' => 3],

            // Year 2, Semester 2
            ['code' => 'ED 16', 'name' => 'Special Education Basics', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 17', 'name' => 'Parent Engagement and Communication', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 18', 'name' => 'Student Discipline and Behavior Management', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 19', 'name' => 'Teaching Language Learners', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 20', 'name' => 'Practicum I', 'year' => 2, 'semester' => 2, 'credits' => 4],

            // Year 3, Semester 1
            ['code' => 'ED 21', 'name' => 'Advanced Child Psychology', 'year' => 3, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 22', 'name' => 'Research Methods in Education', 'year' => 3, 'semester' => 1, 'credits' => 4],
            ['code' => 'ED 23', 'name' => 'Differentiated Instruction', 'year' => 3, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 24', 'name' => 'Digital Literacy and Educational Media', 'year' => 3, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 25', 'name' => 'Leadership and School Culture', 'year' => 3, 'semester' => 1, 'credits' => 3],

            // Year 3, Semester 2
            ['code' => 'ED 26', 'name' => 'Practicum II', 'year' => 3, 'semester' => 2, 'credits' => 6],
            ['code' => 'ED 27', 'name' => 'Action Research and Reflection', 'year' => 3, 'semester' => 2, 'credits' => 3],
            ['code' => 'ED 28', 'name' => 'Professional Development Planning', 'year' => 3, 'semester' => 2, 'credits' => 2],

            // Year 4, Semester 1
            ['code' => 'ED 29', 'name' => 'Advanced Curriculum Issues', 'year' => 4, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 30', 'name' => 'Educational Equity and Diversity', 'year' => 4, 'semester' => 1, 'credits' => 3],
            ['code' => 'ED 31', 'name' => 'Ethics in Education', 'year' => 4, 'semester' => 1, 'credits' => 2],
            ['code' => 'ED 32', 'name' => 'Capstone Project', 'year' => 4, 'semester' => 1, 'credits' => 3],

            // Year 4, Semester 2
            ['code' => 'ED 33', 'name' => 'Student Teaching/Internship', 'year' => 4, 'semester' => 2, 'credits' => 12],
        ];

        // BSHM Subjects (HM = Hospitality Management)
        $bshmSubjects = [
            // Year 1, Semester 1
            ['code' => 'HM 01', 'name' => 'Introduction to Hospitality Industry', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 02', 'name' => 'Hotel and Resort Operations', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 03', 'name' => 'Food Service Fundamentals', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 04', 'name' => 'Hospitality Accounting I', 'year' => 1, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 05', 'name' => 'Customer Service Excellence', 'year' => 1, 'semester' => 1, 'credits' => 3],

            // Year 1, Semester 2
            ['code' => 'HM 06', 'name' => 'Culinary Fundamentals', 'year' => 1, 'semester' => 2, 'credits' => 4],
            ['code' => 'HM 07', 'name' => 'Hospitality Management Basics', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 08', 'name' => 'Hospitality Accounting II', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 09', 'name' => 'Event Planning Basics', 'year' => 1, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 10', 'name' => 'Business Communication', 'year' => 1, 'semester' => 2, 'credits' => 3],

            // Year 2, Semester 1
            ['code' => 'HM 11', 'name' => 'Advanced Culinary Arts', 'year' => 2, 'semester' => 1, 'credits' => 4],
            ['code' => 'HM 12', 'name' => 'Front Office Operations', 'year' => 2, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 13', 'name' => 'Housekeeping Management', 'year' => 2, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 14', 'name' => 'Revenue Management', 'year' => 2, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 15', 'name' => 'Marketing for Hospitality', 'year' => 2, 'semester' => 1, 'credits' => 3],

            // Year 2, Semester 2
            ['code' => 'HM 16', 'name' => 'Event Management and Planning', 'year' => 2, 'semester' => 2, 'credits' => 4],
            ['code' => 'HM 17', 'name' => 'Food and Beverage Management', 'year' => 2, 'semester' => 2, 'credits' => 4],
            ['code' => 'HM 18', 'name' => 'Human Resources in Hospitality', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 19', 'name' => 'Hospitality Law and Ethics', 'year' => 2, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 20', 'name' => 'Practicum I', 'year' => 2, 'semester' => 2, 'credits' => 3],

            // Year 3, Semester 1
            ['code' => 'HM 21', 'name' => 'International Hotel Management', 'year' => 3, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 22', 'name' => 'Wine and Beverage Excellence', 'year' => 3, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 23', 'name' => 'Strategic Management in Hospitality', 'year' => 3, 'semester' => 1, 'credits' => 4],
            ['code' => 'HM 24', 'name' => 'Hospitality Finance Management', 'year' => 3, 'semester' => 1, 'credits' => 4],
            ['code' => 'HM 25', 'name' => 'Sustainable Hospitality Practices', 'year' => 3, 'semester' => 1, 'credits' => 3],

            // Year 3, Semester 2
            ['code' => 'HM 26', 'name' => 'Luxury Hospitality Management', 'year' => 3, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 27', 'name' => 'Crisis Management in Hospitality', 'year' => 3, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 28', 'name' => 'Practicum II', 'year' => 3, 'semester' => 2, 'credits' => 6],
            ['code' => 'HM 29', 'name' => 'Hospitality Quality Management', 'year' => 3, 'semester' => 2, 'credits' => 3],

            // Year 4, Semester 1
            ['code' => 'HM 30', 'name' => 'Advanced Event Management', 'year' => 4, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 31', 'name' => 'Hospitality Technology and Systems', 'year' => 4, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 32', 'name' => 'Entrepreneurship in Hospitality', 'year' => 4, 'semester' => 1, 'credits' => 3],
            ['code' => 'HM 33', 'name' => 'Capstone Project I', 'year' => 4, 'semester' => 1, 'credits' => 3],

            // Year 4, Semester 2
            ['code' => 'HM 34', 'name' => 'Capstone Project II', 'year' => 4, 'semester' => 2, 'credits' => 3],
            ['code' => 'HM 35', 'name' => 'Internship/Practicum', 'year' => 4, 'semester' => 2, 'credits' => 6],
            ['code' => 'HM 36', 'name' => 'Professional Development in Hospitality', 'year' => 4, 'semester' => 2, 'credits' => 2],
        ];

        // Get courses
        $bsit = Course::where('program_code', 'BSIT')->first();
        $beed = Course::where('program_code', 'BEED')->first();
        $bshm = Course::where('program_code', 'BSHM')->first();

        // Create BSIT subjects
        if ($bsit) {
            foreach ($bsitSubjects as $subjectData) {
                Subject::firstOrCreate(
                    ['subject_code' => $subjectData['code']],
                    [
                        'subject_name' => $subjectData['name'],
                        'course_id' => $bsit->id,
                        'credit_hours' => $subjectData['credits'],
                        'category' => 'Core',
                        'year' => $subjectData['year'],
                        'semester' => $subjectData['semester'],
                        'school_year' => '2026-2027',
                    ]
                );
            }
        }

        // Create BEED subjects
        if ($beed) {
            foreach ($beedSubjects as $subjectData) {
                Subject::firstOrCreate(
                    ['subject_code' => $subjectData['code']],
                    [
                        'subject_name' => $subjectData['name'],
                        'course_id' => $beed->id,
                        'credit_hours' => $subjectData['credits'],
                        'category' => 'Core',
                        'year' => $subjectData['year'],
                        'semester' => $subjectData['semester'],
                        'school_year' => '2026-2027',
                    ]
                );
            }
        }

        // Create BSHM subjects
        if ($bshm) {
            foreach ($bshmSubjects as $subjectData) {
                Subject::firstOrCreate(
                    ['subject_code' => $subjectData['code']],
                    [
                        'subject_name' => $subjectData['name'],
                        'course_id' => $bshm->id,
                        'credit_hours' => $subjectData['credits'],
                        'category' => 'Core',
                        'year' => $subjectData['year'],
                        'semester' => $subjectData['semester'],
                        'school_year' => '2026-2027',
                    ]
                );
            }
        }
    }
}
