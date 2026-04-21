<?php

use Illuminate\Support\Facades\Route;

// Routes for Grading System Multi-Mode Support
// Add these routes to routes/web.php in the teacher middleware group

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    
    // Grading Mode Configuration Routes
    Route::prefix('grades/{classId}/mode')->group(function () {
        Route::get('/', 'GradingModeController@show')->name('teacher.grades.mode.show');
        Route::post('/', 'GradingModeController@update')->name('teacher.grades.mode.update');
        Route::get('/summary', 'GradingModeController@getSummary')->name('teacher.grades.mode.summary');
        
        // Component Mode Configuration (for hybrid mode)
        Route::get('/components', 'GradingModeController@getComponentModes')->name('teacher.grades.mode.components');
        Route::post('/components/{componentId}', 'GradingModeController@updateComponentMode')->name('teacher.grades.mode.component-update');
    });

    // E-Signature Management Routes
    Route::prefix('grades/{classId}/signatures')->group(function () {
        Route::get('/', 'AttendanceSignatureController@index')->name('teacher.attendance-signatures.index');
        Route::get('/create', 'AttendanceSignatureController@create')->name('teacher.attendance-signatures.create');
        Route::post('/', 'AttendanceSignatureController@store')->name('teacher.attendance-signatures.store');
        Route::get('/{signatureId}', 'AttendanceSignatureController@show')->name('teacher.attendance-signatures.show');
        Route::post('/{signatureId}/approve', 'AttendanceSignatureController@approve')->name('teacher.attendance-signatures.approve');
        Route::post('/{signatureId}/reject', 'AttendanceSignatureController@reject')->name('teacher.attendance-signatures.reject');
        Route::delete('/{signatureId}', 'AttendanceSignatureController@destroy')->name('teacher.attendance-signatures.destroy');
        Route::get('/statistics/summary', 'AttendanceSignatureController@getStatistics')->name('teacher.attendance-signatures.statistics');
    });

    // Grading Sheet Generation Routes
    Route::prefix('grades/{classId}/sheet')->group(function () {
        Route::get('/', 'GradingSheetController@preview')->name('teacher.grading-sheet.preview');
        Route::get('/download/{format?}', 'GradingSheetController@download')->name('teacher.grading-sheet.download');
        Route::get('/print', 'GradingSheetController@print')->name('teacher.grading-sheet.print');
        Route::get('/templates', 'GradingSheetController@templates')->name('teacher.grading-sheet.templates');
    });

});

// Admin routes for grading sheet template management
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('grading-templates', 'GradingSheetTemplateController');
    Route::post('grading-templates/{id}/set-default', 'GradingSheetTemplateController@setDefault')->name('grading-templates.set-default');
});
