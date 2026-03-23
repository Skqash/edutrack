<?php

/**
 * Database ERD Analysis Script
 * Analyzes the database structure and generates ERD documentation
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseERDAnalyzer
{
    private $tables = [];
    private $relationships = [];
    
    public function analyze()
    {
        echo "=== DATABASE ERD ANALYSIS ===\n\n";
        
        // Get all tables
        $this->getTables();
        
        // Analyze each table
        foreach ($this->tables as $table) {
            $this->analyzeTable($table);
        }
        
        // Print results
        $this->printResults();
        
        // Generate Mermaid ERD
        $this->generateMermaidERD();
    }
    
    private function getTables()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_{$dbName}"};
            $this->tables[] = $tableName;
        }
        
        echo "Found " . count($this->tables) . " tables\n\n";
    }
    
    private function analyzeTable($tableName)
    {
        $columns = DB::select("DESCRIBE {$tableName}");
        
        $tableInfo = [
            'name' => $tableName,
            'columns' => [],
            'primary_key' => null,
            'foreign_keys' => [],
            'indexes' => [],
        ];
        
        foreach ($columns as $column) {
            $columnInfo = [
                'name' => $column->Field,
                'type' => $column->Type,
                'null' => $column->Null === 'YES',
                'key' => $column->Key,
                'default' => $column->Default,
                'extra' => $column->Extra,
            ];
            
            $tableInfo['columns'][] = $columnInfo;
            
            // Identify primary key
            if ($column->Key === 'PRI') {
                $tableInfo['primary_key'] = $column->Field;
            }
            
            // Identify foreign keys (by naming convention)
            if (preg_match('/_id$/', $column->Field) && $column->Field !== 'id') {
                $referencedTable = str_replace('_id', '', $column->Field);
                
                // Handle special cases
                if ($referencedTable === 'user') $referencedTable = 'users';
                if ($referencedTable === 'class') $referencedTable = 'classes';
                if ($referencedTable === 'course') $referencedTable = 'courses';
                if ($referencedTable === 'subject') $referencedTable = 'subjects';
                if ($referencedTable === 'student') $referencedTable = 'students';
                if ($referencedTable === 'teacher') $referencedTable = 'users';
                if ($referencedTable === 'program') $referencedTable = 'courses';
                if ($referencedTable === 'component') $referencedTable = 'assessment_components';
                if ($referencedTable === 'school') $referencedTable = 'schools';
                
                $tableInfo['foreign_keys'][] = [
                    'column' => $column->Field,
                    'references' => $referencedTable,
                    'nullable' => $column->Null === 'YES',
                ];
                
                $this->relationships[] = [
                    'from' => $tableName,
                    'to' => $referencedTable,
                    'type' => $column->Null === 'YES' ? 'optional' : 'required',
                    'column' => $column->Field,
                ];
            }
        }
        
        $this->tables[$tableName] = $tableInfo;
    }
    
    private function printResults()
    {
        echo "=== TABLE STRUCTURE ===\n\n";
        
        foreach ($this->tables as $tableName => $tableInfo) {
            if (is_string($tableName)) {
                echo "Table: {$tableName}\n";
                echo "Primary Key: " . ($tableInfo['primary_key'] ?? 'None') . "\n";
                echo "Columns: " . count($tableInfo['columns']) . "\n";
                
                if (!empty($tableInfo['foreign_keys'])) {
                    echo "Foreign Keys:\n";
                    foreach ($tableInfo['foreign_keys'] as $fk) {
                        $nullable = $fk['nullable'] ? '(nullable)' : '(required)';
                        echo "  - {$fk['column']} -> {$fk['references']} {$nullable}\n";
                    }
                }
                
                echo "\n";
            }
        }
        
        echo "\n=== RELATIONSHIPS ===\n\n";
        echo "Total Relationships: " . count($this->relationships) . "\n\n";
    }
    
    private function generateMermaidERD()
    {
        echo "\n=== MERMAID ERD DIAGRAM ===\n\n";
        
        $mermaid = "erDiagram\n\n";
        
        // Core entities
        $coreEntities = [
            'users' => 'User Management',
            'schools' => 'School/Campus Management',
            'courses' => 'Academic Programs',
            'subjects' => 'Subject Catalog',
            'classes' => 'Class Sections',
            'students' => 'Student Records',
            'assessment_components' => 'Grade Components',
            'component_entries' => 'Grade Entries',
            'ksa_settings' => 'KSA Settings',
            'attendance' => 'Attendance Records',
            'grades' => 'Final Grades',
            'course_access_requests' => 'Access Requests',
            'notifications' => 'Notifications',
        ];
        
        // Define entities with key attributes
        foreach ($coreEntities as $table => $description) {
            if (isset($this->tables[$table]) && is_array($this->tables[$table])) {
                $tableInfo = $this->tables[$table];
                $mermaid .= "    {$table} {\n";
                
                // Add key columns (limit to important ones)
                $importantColumns = array_slice($tableInfo['columns'], 0, 8);
                foreach ($importantColumns as $column) {
                    $type = $this->simplifyType($column['type']);
                    $pk = $column['key'] === 'PRI' ? ' PK' : '';
                    $fk = preg_match('/_id$/', $column['name']) && $column['name'] !== 'id' ? ' FK' : '';
                    $mermaid .= "        {$type} {$column['name']}{$pk}{$fk}\n";
                }
                
                $mermaid .= "    }\n\n";
            }
        }
        
        // Define relationships
        $processedRelationships = [];
        foreach ($this->relationships as $rel) {
            $key = $rel['from'] . '-' . $rel['to'];
            
            // Skip if already processed or if tables don't exist in core entities
            if (isset($processedRelationships[$key])) continue;
            if (!isset($coreEntities[$rel['from']]) || !isset($coreEntities[$rel['to']])) continue;
            
            $cardinality = $rel['type'] === 'optional' ? '||--o{' : '||--||';
            $mermaid .= "    {$rel['from']} {$cardinality} {$rel['to']} : \"{$rel['column']}\"\n";
            
            $processedRelationships[$key] = true;
        }
        
        echo $mermaid;
        
        // Save to file
        file_put_contents('DATABASE_ERD.mmd', $mermaid);
        echo "\n\nMermaid diagram saved to DATABASE_ERD.mmd\n";
    }
    
    private function simplifyType($type)
    {
        if (strpos($type, 'int') !== false) return 'int';
        if (strpos($type, 'varchar') !== false) return 'string';
        if (strpos($type, 'text') !== false) return 'text';
        if (strpos($type, 'decimal') !== false) return 'decimal';
        if (strpos($type, 'date') !== false) return 'date';
        if (strpos($type, 'timestamp') !== false) return 'timestamp';
        if (strpos($type, 'enum') !== false) return 'enum';
        return 'string';
    }
}

$analyzer = new DatabaseERDAnalyzer();
$analyzer->analyze();
