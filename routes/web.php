<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ======================
        // Students
        // ======================

        // IMPORT (STATIC ROUTES FIRST)
        Route::get('/students/import', [StudentController::class, 'excel'])
            ->name('students.import.form');

        // EXPORT csv excel pdf 
        Route::get('/students/export/csv', [StudentController::class, 'exportCsv'])
            ->name('students.export.csv');

        Route::get('/students/export/excel', [StudentController::class, 'exportExcel'])
            ->name('students.export.excel');

        Route::get('/students/export/pdf', [StudentController::class, 'exportPdf'])
            ->name('students.export.pdf');


            // bulk upload 
        Route::post('/students/import', [StudentController::class, 'import'])
            ->name('students.import');

        // CRUD
        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        Route::get('/students/create', [StudentController::class, 'create'])
            ->name('students.create');

        Route::post('/students', [StudentController::class, 'store'])
            ->name('students.store');

        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
            ->name('students.edit');

        Route::put('/students/{student}', [StudentController::class, 'update'])
            ->name('students.update');

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

        // SHOW (ALWAYS LAST)
        Route::get('/students/{student}', [StudentController::class, 'show'])
            ->name('students.show');
    });


Route::get('/students/template', function () {
    $headers = [
        'name,email,gender,rollnum,phone,blood_group,father_phone,department,section,academic_year,passout_year'
    ];

    return response()->streamDownload(function () use ($headers) {
        echo implode("\n", $headers);
    }, 'students_template.csv');
})->name('students.template');



/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
