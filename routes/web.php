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



Route::get('/admin/students/template/csv', function () {

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename=students_template.csv',
    ];

    $columns = [
        'rollnum',
        'name',
        'email',
        'gender',
        'phone',
        'blood_group',
        'father_phone',
        'department',
        'section',
        'admission_year',
        'passout_year',
    ];

    $rows = [
        [101,'Arun Kumar','arun101@gmail.com','male','9876543210','B+','9876543211','CSE','A',2023,'2025-2026',2026],
        [102,'Divya R','divya102@gmail.com','female','9876543212','O+','9876543213','CSE','B',2023,'2025-2026',2026],
        [103,'Suresh M','suresh103@gmail.com','male','9876543214','A+','9876543215','ECE','A',2022,'2024-2025',2025],
        [104,'Priya S','priya104@gmail.com','female','9876543216','B-','9876543217','ECE','B',2022,'2024-2025',2025],
        [105,'Karthik V','karthik105@gmail.com','male','9876543218','O-','9876543219','MECH','A',2021,'2023-2024',2024],
        [106,'Nisha P','nisha106@gmail.com','female','9876543220','A-','9876543221','MECH','B',2021,'2023-2024',2024],
        [107,'Rahul T','rahul107@gmail.com','male','9876543222','B+','9876543223','CIVIL','A',2020,'2022-2023',2023],
        [108,'Meena L','meena108@gmail.com','female','9876543224','O+','9876543225','CIVIL','B',2020,'2022-2023',2023],
        [109,'Vikram S','vikram109@gmail.com','male','9876543226','AB+','9876543227','CSE','A',2024,'2026-2027',2027],
        [110,'Anitha K','anitha110@gmail.com','female','9876543228','A+','9876543229','CSE','B',2024,'2026-2027',2027],
    ];

    $callback = function () use ($columns, $rows) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
})->name('admin.students.template.csv');
