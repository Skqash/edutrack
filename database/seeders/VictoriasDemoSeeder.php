<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VictoriasDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏫 Seeding Victorias Campus demo data...');

        $campus   = 'CPSU Victorias Campus';
        $school   = \App\Models\School::where('short_name', 'CPSU-Victorias')->first();
        $schoolId = $school?->id;

        // ── Teachers ──────────────────────────────────────────────────────────
        $garcia = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $lopez  = User::where('email', 'carmen.lopez@cpsu.edu.ph')->first();
        $torres = User::where('email', 'miguel.torres@cpsu.edu.ph')->first();

        if (!$garcia || !$lopez || !$torres) {
            $this->command->error('Victorias teachers not found. Run CPSUAccurateSeeder first.');
            return;
        }

        // ── Courses ───────────────────────────────────────────────────────────
        $bsitVic = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic = Course::where('program_code', 'BEED-VIC')->first();
        $bshmVic = Course::where('program_code', 'BSHM-VIC')->first();

        // ── Subjects ──────────────────────────────────────────────────────────
        $it101   = Subject::where('subject_code', 'IT101-VIC')->first();
        $prog101 = Subject::where('subject_code', 'PROG101-VIC')->first();
        $webdev  = Subject::where('subject_code', 'WEBDEV-VIC')->first();
        $dbms    = Subject::where('subject_code', 'DBMS-VIC')->first();
        $educ101 = Subject::where('subject_code', 'EDUC101-VIC')->first();
        $educ201 = Subject::where('subject_code', 'EDUC201-VIC')->first();
        $hm101   = Subject::where('subject_code', 'HM101-VIC')->first();
        $hm201   = Subject::where('subject_code', 'HM201-VIC')->first();

        // ── Wipe stale Victorias data ─────────────────────────────────────────
        $this->command->info('🧹 Cleaning stale Victorias data...');
        $vicClassIds = ClassModel::where('campus', $campus)->pluck('id');
        DB::table('attendance')->whereIn('class_id', $vicClassIds)->delete();
        DB::table('grades')->whereIn('class_id', $vicClassIds)->delete();
        // Reset student class assignments for Victorias students
        Student::where('campus', $campus)->update(['class_id' => null]);
        // Delete old Victorias classes (will recreate cleanly)
        ClassModel::where('campus', $campus)->delete();

        // ── Load students by course ───────────────────────────────────────────
        $allVicStudents = Student::where('campus', $campus)->orderBy('id')->get();
        $this->command->info("  Total Victorias students: {$allVicStudents->count()}");

        // Group by course
        $bsitStudents = $allVicStudents->where('course_id', $bsitVic?->id)->values();
        $beedStudents = $allVicStudents->where('course_id', $beedVic?->id)->values();
        $bshmStudents = $allVicStudents->where('course_id', $bshmVic?->id)->values();

        $this->command->info("  BSIT: {$bsitStudents->count()} | BEED: {$beedStudents->count()} | BSHM: {$bshmStudents->count()}");

        // ── Class definitions ─────────────────────────────────────────────────
        // Each student is assigned to exactly ONE class (their primary class).
        // For demo purposes: all BSIT students → BSIT 1-A (Garcia's main class)
        // Additional subject classes share the same students but don't re-assign class_id.
        $classDefinitions = [
            // ── Roberto Garcia (IT) ──────────────────────────────────────────
            [
                'class_name'     => 'BSIT 1-A Intro to Computing',
                'teacher'        => $garcia,
                'subject'        => $it101,
                'course'         => $bsitVic,
                'year'           => 1,
                'section'        => 'A',
                'semester'       => 'First',
                'primary_for'    => $bsitStudents,   // these students get class_id set here
                'grade_students' => $bsitStudents,
            ],
            [
                'class_name'     => 'BSIT 1-B Programming Fundamentals',
                'teacher'        => $garcia,
                'subject'        => $prog101,
                'course'         => $bsitVic,
                'year'           => 1,
                'section'        => 'B',
                'semester'       => 'Second',
                'primary_for'    => collect(),        // no class_id reassignment
                'grade_students' => $bsitStudents,
            ],
            [
                'class_name'     => 'BSIT 2-A Web Development',
                'teacher'        => $garcia,
                'subject'        => $webdev,
                'course'         => $bsitVic,
                'year'           => 2,
                'section'        => 'A',
                'semester'       => 'First',
                'primary_for'    => collect(),
                'grade_students' => $bsitStudents,
            ],
            [
                'class_name'     => 'BSIT 2-B Database Management',
                'teacher'        => $garcia,
                'subject'        => $dbms,
                'course'         => $bsitVic,
                'year'           => 2,
                'section'        => 'B',
                'semester'       => 'Second',
                'primary_for'    => collect(),
                'grade_students' => $bsitStudents,
            ],
            // ── Carmen Lopez (Education) ─────────────────────────────────────
            [
                'class_name'     => 'BEED 1-A Foundations of Education',
                'teacher'        => $lopez,
                'subject'        => $educ101,
                'course'         => $beedVic,
                'year'           => 1,
                'section'        => 'A',
                'semester'       => 'First',
                'primary_for'    => $beedStudents,
                'grade_students' => $beedStudents,
            ],
            [
                'class_name'     => 'BEED 2-A Child and Adolescent Dev',
                'teacher'        => $lopez,
                'subject'        => $educ201,
                'course'         => $beedVic,
                'year'           => 2,
                'section'        => 'A',
                'semester'       => 'First',
                'primary_for'    => collect(),
                'grade_students' => $beedStudents,
            ],
            // ── Miguel Torres (Hospitality) ──────────────────────────────────
            [
                'class_name'     => 'BSHM 1-A Intro to Hospitality',
                'teacher'        => $torres,
                'subject'        => $hm101,
                'course'         => $bshmVic,
                'year'           => 1,
                'section'        => 'A',
                'semester'       => 'First',
                'primary_for'    => $bshmStudents,
                'grade_students' => $bshmStudents,
            ],
            [
                'class_name'     => 'BSHM 2-A Food and Beverage Mgmt',
                'teacher'        => $torres,
                'subject'        => $hm201,
                'course'         => $bshmVic,
                'year'           => 2,
                'section'        => 'A',
                'semester'       => 'First',
                'primary_for'    => collect(),
                'grade_students' => $bshmStudents,
            ],
        ];

        $createdClasses = [];

        foreach ($classDefinitions as $cd) {
            $gradeStudents = $cd['grade_students'];
            $primaryStudents = $cd['primary_for'];

            $class = ClassModel::create([
                'class_name'    => $cd['class_name'],
                'teacher_id'    => $cd['teacher']->id,
                'subject_id'    => $cd['subject']?->id,
                'course_id'     => $cd['course']?->id,
                'year'          => $cd['year'],
                'class_level'   => $cd['year'],
                'section'       => $cd['section'],
                'semester'      => $cd['semester'],
                'academic_year' => '2025-2026',
                'school_year'   => '2025-2026',
                'total_students'=> $gradeStudents->count(),
                'status'        => 'Active',
                'current_term'  => 'midterm',
                'campus'        => $campus,
                'school_id'     => $schoolId,
                'units'         => $cd['subject']?->credit_hours ?? 3,
            ]);

            // Only set class_id on students for their PRIMARY class
            if ($primaryStudents->isNotEmpty()) {
                Student::whereIn('id', $primaryStudents->pluck('id'))
                    ->update(['class_id' => $class->id]);
            }

            $createdClasses[] = [
                'class'   => $class,
                'teacher' => $cd['teacher'],
                'students'=> $gradeStudents,
            ];

            $this->command->info("  ✅ {$class->class_name} | primary_students: {$primaryStudents->count()} | grade_students: {$gradeStudents->count()}");
        }

        // ── Grades ────────────────────────────────────────────────────────────
        $this->command->info('📊 Creating grades...');

        foreach ($createdClasses as $entry) {
            $class   = $entry['class'];
            $teacher = $entry['teacher'];
            $sem     = $class->semester === 'Second' ? '2' : '1';

            foreach ($entry['students'] as $student) {
                $midK = rand(70, 95); $midS = rand(72, 96); $midA = rand(75, 98);
                $midFinal = round(($midK * 0.40) + ($midS * 0.50) + ($midA * 0.10), 2);

                $finK = rand(70, 95); $finS = rand(72, 96); $finA = rand(75, 98);
                $finFinal = round(($finK * 0.40) + ($finS * 0.50) + ($finA * 0.10), 2);

                $overall = round(($midFinal * 0.40) + ($finFinal * 0.60), 2);

                // Use class_id + student_id + subject_id as unique key to avoid constraint issues
                Grade::updateOrCreate(
                    [
                        'student_id'    => $student->id,
                        'subject_id'    => $class->subject_id,
                        'semester'      => $sem,
                        'academic_year' => '2025-2026',
                    ],
                    [
                        'class_id'                => $class->id,
                        'teacher_id'              => $teacher->id,
                        'term'                    => 'final',
                        'mid_knowledge_average'   => $midK,
                        'mid_skills_average'      => $midS,
                        'mid_attitude_average'    => $midA,
                        'mid_final_grade'         => $midFinal,
                        'midterm_grade'           => $midFinal,
                        'final_knowledge_average' => $finK,
                        'final_skills_average'    => $finS,
                        'final_attitude_average'  => $finA,
                        'final_final_grade'       => $finFinal,
                        'final_grade_value'       => $finFinal,
                        'knowledge_average'       => round(($midK + $finK) / 2, 2),
                        'skills_average'          => round(($midS + $finS) / 2, 2),
                        'attitude_average'        => round(($midA + $finA) / 2, 2),
                        'overall_grade'           => $overall,
                        'grade'                   => $overall,
                        'campus'                  => $campus,
                        'school_id'               => $schoolId,
                        'remarks'                 => $overall >= 75 ? 'Passed' : 'Failed',
                    ]
                );
            }
        }

        // ── Attendance ────────────────────────────────────────────────────────
        $this->command->info('📅 Creating attendance records...');

        $statuses = ['Present', 'Present', 'Present', 'Present', 'Late', 'Absent'];

        foreach ($createdClasses as $entry) {
            $class   = $entry['class'];
            $teacher = $entry['teacher'];

            for ($day = 1; $day <= 20; $day++) {
                $date = Carbon::now()->subDays($day)->toDateString();
                $term = $day <= 10 ? 'Midterm' : 'Final';

                foreach ($entry['students'] as $student) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'class_id'   => $class->id,
                        'teacher_id' => $teacher->id,
                        'date'       => $date,
                        'status'     => $statuses[array_rand($statuses)],
                        'term'       => $term,
                        'campus'     => $campus,
                        'school_id'  => $schoolId,
                    ]);
                }
            }
        }

        // ── Update total_students on each class ───────────────────────────────
        foreach ($createdClasses as $entry) {
            $class = $entry['class'];
            $class->total_students = $entry['students']->count();
            $class->save();
        }

        // ── Course Access Requests ────────────────────────────────────────────
        $this->command->info('🎓 Creating course access requests...');

        $courseTeacherMap = [
            [$garcia, $bsitVic],
            [$lopez,  $beedVic],
            [$torres, $bshmVic],
        ];

        foreach ($courseTeacherMap as [$teacher, $course]) {
            if (!$teacher || !$course) continue;
            \App\Models\CourseAccessRequest::updateOrCreate(
                ['teacher_id' => $teacher->id, 'course_id' => $course->id],
                [
                    'campus'      => $campus,
                    'school_id'   => $schoolId,
                    'status'      => 'approved',
                    'reason'      => 'Assigned by admin during campus setup.',
                    'approved_by' => null,
                    'approved_at' => now(),
                ]
            );
        }

        // ── Summary ───────────────────────────────────────────────────────────
        $this->command->info('✅ Victorias demo seeder completed!');
        $this->command->info('   Classes: ' . count($createdClasses));
        $this->command->info('   BSIT students: ' . $bsitStudents->count());
        $this->command->info('   BEED students: ' . $beedStudents->count());
        $this->command->info('   BSHM students: ' . $bshmStudents->count());

        // Verify
        $this->command->info("\n📋 Verification:");
        foreach ($createdClasses as $entry) {
            $c = $entry['class'];
            $stuInClass = Student::where('class_id', $c->id)->count();
            $attInClass = DB::table('attendance')->where('class_id', $c->id)->count();
            $gradesInClass = DB::table('grades')->where('class_id', $c->id)->count();
            $this->command->info("  {$c->class_name}: primary_students={$stuInClass} | att={$attInClass} | grades={$gradesInClass}");
        }
    }
}
