<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class RealisticStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds with realistic Filipino student names
     */
    public function run()
    {
        // Clear existing students
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Student::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Creating realistic students...\n";

        // Get schools and courses
        $schools = School::all();
        $courses = Course::all();

        if ($schools->isEmpty() || $courses->isEmpty()) {
            echo "⚠ No schools or courses found. Please run school and course seeders first.\n";
            return;
        }

        // Filipino first names
        $maleFirstNames = [
            'Juan', 'Jose', 'Miguel', 'Gabriel', 'Rafael', 'Carlos', 'Luis', 'Antonio', 'Manuel', 'Pedro',
            'Diego', 'Fernando', 'Ricardo', 'Roberto', 'Eduardo', 'Andres', 'Francisco', 'Javier', 'Daniel', 'David',
            'Mark', 'John', 'James', 'Michael', 'Joshua', 'Christian', 'Angelo', 'Kenneth', 'Ryan', 'Kevin',
            'Jerome', 'Joseph', 'Emmanuel', 'Renz', 'Carlo', 'Paolo', 'Marco', 'Adrian', 'Alvin', 'Bryan'
        ];

        $femaleFirstNames = [
            'Maria', 'Ana', 'Sofia', 'Isabella', 'Gabriela', 'Valentina', 'Camila', 'Victoria', 'Daniela', 'Andrea',
            'Carmen', 'Rosa', 'Elena', 'Patricia', 'Laura', 'Sandra', 'Monica', 'Diana', 'Angela', 'Teresa',
            'Angel', 'Princess', 'Angelica', 'Christine', 'Michelle', 'Nicole', 'Stephanie', 'Kristine', 'Mary', 'Grace',
            'Joy', 'Faith', 'Hope', 'Charity', 'Divine', 'Precious', 'Lovely', 'Honey', 'Sunshine', 'Crystal'
        ];

        // Filipino last names
        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres', 'Gonzales', 'Lopez',
            'Ramos', 'Flores', 'Rivera', 'Gomez', 'Fernandez', 'Perez', 'Rodriguez', 'Sanchez', 'Ramirez', 'Castillo',
            'Dela Cruz', 'Villanueva', 'Aquino', 'Tolentino', 'Santiago', 'Pascual', 'Mercado', 'Aguilar', 'Navarro', 'Morales',
            'Diaz', 'Valdez', 'Castro', 'Marquez', 'Alvarez', 'Jimenez', 'Salazar', 'Herrera', 'Medina', 'Ortiz',
            'Gutierrez', 'Chavez', 'Vargas', 'Romero', 'Soto', 'Contreras', 'Guzman', 'Rojas', 'Pena', 'Rios'
        ];

        // Middle names (often mother's maiden name)
        $middleNames = [
            'De Leon', 'Del Rosario', 'San Jose', 'Santa Maria', 'Dela Rosa', 'San Pedro', 'De Guzman', 'Del Carmen',
            'Santos', 'Cruz', 'Reyes', 'Garcia', 'Lopez', 'Ramos', 'Torres', 'Gonzales', 'Mendoza', 'Rivera',
            'Flores', 'Gomez', 'Fernandez', 'Perez', 'Rodriguez', 'Sanchez', 'Ramirez', 'Castillo'
        ];

        $sections = ['A', 'B', 'C', 'D', 'E'];
        $studentCount = 0;

        foreach ($schools as $school) {
            // Get courses for this school
            $schoolCourses = $courses->where('school_id', $school->id);

            if ($schoolCourses->isEmpty()) {
                continue;
            }

            foreach ($schoolCourses as $course) {
                // Create students for each year level
                for ($year = 1; $year <= 4; $year++) {
                    foreach ($sections as $section) {
                        // Random number of students per section (20-35)
                        $studentsPerSection = rand(20, 35);

                        for ($i = 1; $i <= $studentsPerSection; $i++) {
                            $isMale = rand(0, 1);
                            $firstName = $isMale ? 
                                $maleFirstNames[array_rand($maleFirstNames)] : 
                                $femaleFirstNames[array_rand($femaleFirstNames)];
                            
                            $middleName = $middleNames[array_rand($middleNames)];
                            $lastName = $lastNames[array_rand($lastNames)];

                            // Generate student ID: YEAR-CAMPUS-COURSE-NUMBER
                            $campusCode = strtoupper(substr($school->short_name, 0, 3));
                            $courseCode = $course->program_code ?? 'GEN';
                            $studentNumber = str_pad($studentCount + 1, 4, '0', STR_PAD_LEFT);
                            $studentId = date('Y') . '-' . $campusCode . '-' . $courseCode . '-' . $studentNumber;

                            // Generate email
                            $emailName = strtolower(str_replace(' ', '', $firstName . '.' . $lastName));
                            $email = $emailName . $studentNumber . '@student.cpsu.edu.ph';

                            // Generate phone number (Philippine format)
                            $phone = '09' . rand(100000000, 999999999);

                            // Generate address
                            $barangays = ['Poblacion', 'San Jose', 'Santa Cruz', 'Bagumbayan', 'Magsaysay', 'Rizal', 'Bonifacio'];
                            $address = 'Brgy. ' . $barangays[array_rand($barangays)] . ', ' . $school->short_name . ', Negros Occidental';

                            Student::create([
                                'student_id' => $studentId,
                                'first_name' => $firstName,
                                'middle_name' => $middleName,
                                'last_name' => $lastName,
                                'suffix' => rand(0, 20) == 0 ? ['Jr.', 'Sr.', 'II', 'III'][rand(0, 3)] : null,
                                'email' => $email,
                                'phone' => $phone,
                                'address' => $address,
                                'course_id' => $course->id,
                                'class_id' => null, // Will be assigned when class is created
                                'year' => $year,
                                'section' => $section,
                                'campus' => $school->short_name, // Use short_name for campus field
                                'school_id' => $school->id,
                                'status' => 'Active',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            $studentCount++;

                            if ($studentCount % 100 == 0) {
                                echo "Created {$studentCount} students...\n";
                            }
                        }
                    }
                }
            }
        }

        echo "✓ Created {$studentCount} realistic students with Filipino names\n";
        echo "\nStudent Distribution:\n";
        
        foreach ($schools as $school) {
            $count = Student::where('school_id', $school->id)->count();
            echo "  {$school->short_name}: {$count} students\n";
        }

        echo "\nSample Students:\n";
        $samples = Student::with('course')->limit(5)->get();
        foreach ($samples as $student) {
            $courseName = $student->course ? $student->course->program_name : 'No Course';
            echo "  - {$student->student_id}: {$student->first_name} {$student->middle_name} {$student->last_name}\n";
            echo "    {$courseName} - Year {$student->year}{$student->section}\n";
            echo "    {$student->email}\n\n";
        }
    }
}
