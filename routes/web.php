<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    DashboardController,
    StudentController,
    DepartmentController,
    SectionController,
    AttendanceController
};

// Route::get('/', fn () => view('welcome'));


Route::get('/', function () {
    return redirect()->route('login');
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */
    Route::get('/students/import', [StudentController::class, 'excel'])
        ->name('students.import.form');

    Route::post('/students/import', [StudentController::class, 'import'])
        ->name('students.import');

    Route::get('/students/export/csv', [StudentController::class, 'exportCsv'])
        ->name('students.export.csv');

    Route::get('/students/export/excel', [StudentController::class, 'exportExcel'])
        ->name('students.export.excel');

    Route::get('/students/export/pdf', [StudentController::class, 'exportPdf'])
        ->name('students.export.pdf');

    Route::resource('students', StudentController::class);

    /*
    |--------------------------------------------------------------------------
    | Departments & Sections
    |--------------------------------------------------------------------------
    */
    Route::resource('departments', DepartmentController::class);
    Route::resource('sections', SectionController::class);

    Route::get('/departments/{department}/sections',
        [StudentController::class, 'departmentfetch']);

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/attendance/bulk-save', [AttendanceController::class, 'bulkSave'])
        ->name('attendance.bulkSave');

    Route::get('/attendance/day', [AttendanceController::class, 'dayList'])
        ->name('attendance.day');

    Route::get('/attendance/summary', [AttendanceController::class, 'summary'])
        ->name('attendance.summary');

    Route::post('/attendance/update',  [AttendanceController::class, 'update'])
    ->name('attendance.update');


    Route::get('/departments/{department}/sections', [AttendanceController::class, 'sections'])
        ->name('departments.sections');

    Route::get('/attendance/ajax-students',[AttendanceController::class, 'ajaxStudents'])
        ->name('attendance.ajaxStudents');
});

/*
|--------------------------------------------------------------------------
| Profile
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

    /*
    |--------------------------------------------------------------------------
    | CSV Columns
    |--------------------------------------------------------------------------
    */

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
        'semester',
    ];

    /*
    |--------------------------------------------------------------------------
    | Sample Data
    |--------------------------------------------------------------------------
    */

    $rows = [
        [
            '25001',
            'ACHSAH JESSICA D',
            '',
            'female',
            '9944440632',
            '',
            '9994940632',
            'B.Sc Nursing',
            'A',
            'II semester',
        ],

        [
            '25002',
            'AKASH M',
            '',
            'male',
            '9585877181',
            '',
            '9585877181',
            'B.Sc Nursing',
            'A',
            'II semester',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | CSV Download
    |--------------------------------------------------------------------------
    */

    $callback = function () use ($columns, $rows) {

        $file = fopen('php://output', 'w');

        // Header
        fputcsv($file, $columns);

        // Rows
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream(
        $callback,
        200,
        $headers
    );

})->name('admin.students.template.csv');
