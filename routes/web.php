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

Route::get('/', fn () => view('welcome'));

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

    // $rows = [
    //     [101,'Arun Kumar','arun101@gmail.com','male','9876543210','B+','9876543211','CSE','A',2023,2026],
    //     [102,'Divya R','divya102@gmail.com','female','9876543212','O+','9876543213','CSE','B',2023,2026],
    //     [103,'Suresh M','suresh103@gmail.com','male','9876543214','A+','9876543215','ECE','A',2022,2025],
    //     [104,'Priya S','priya104@gmail.com','female','9876543216','B-','9876543217','ECE','B',2022,2025],
    //     [105,'Karthik V','karthik105@gmail.com','male','9876543218','O-','9876543219','MECH','A',2021,2024],
    //     [106,'Nisha P','nisha106@gmail.com','female','9876543220','A-','9876543221','MECH','B',2021,2024],
    //     [107,'Rahul T','rahul107@gmail.com','male','9876543222','B+','9876543223','CIVIL','A',2020,2023],
    //     [108,'Meena L','meena108@gmail.com','female','9876543224','O+','9876543225','CIVIL','B',2020,2023],
    //     [109,'Vikram S','vikram109@gmail.com','male','9876543226','AB+','9876543227','CSE','A',2024,2027],
    //     [110,'Anitha K','anitha110@gmail.com','female','9876543228','A+','9876543229','CSE','B',2024,2027],
    // ];
//     $rows = [
// [101,'Arun Kumar','arun101@gmail.com','male','9876543210','B+','9876543211','CSE','A',2023,2026],
// [102,'Divya R','divya102@gmail.com','female','9876543212','O+','9876543213','CSE','B',2023,2026],
// [103,'Suresh M','suresh103@gmail.com','male','9876543214','A+','9876543215','ECE','A',2022,2025],
// [104,'Priya S','priya104@gmail.com','female','9876543216','B-','9876543217','ECE','B',2022,2025],
// [105,'Karthik V','karthik105@gmail.com','male','9876543218','O-','9876543219','MECH','A',2021,2024],
// [106,'Nisha P','nisha106@gmail.com','female','9876543220','A-','9876543221','MECH','B',2021,2024],
// [107,'Rahul T','rahul107@gmail.com','male','9876543222','B+','9876543223','CIVIL','A',2020,2023],
// [108,'Meena L','meena108@gmail.com','female','9876543224','O+','9876543225','CIVIL','B',2020,2023],
// [109,'Vikram S','vikram109@gmail.com','male','9876543226','AB+','9876543227','CSE','A',2024,2027],
// [110,'Anitha K','anitha110@gmail.com','female','9876543228','A+','9876543229','CSE','B',2024,2027],

// [111,'Ramesh P','ramesh111@gmail.com','male','9876543230','B+','9876543231','ECE','A',2022,2025],
// [112,'Sneha M','sneha112@gmail.com','female','9876543232','O+','9876543233','ECE','B',2022,2025],
// [113,'Ganesh K','ganesh113@gmail.com','male','9876543234','A+','9876543235','MECH','A',2021,2024],
// [114,'Kavya S','kavya114@gmail.com','female','9876543236','B-','9876543237','MECH','B',2021,2024],
// [115,'Hari R','hari115@gmail.com','male','9876543238','O-','9876543239','CIVIL','A',2020,2023],
// [116,'Deepa L','deepa116@gmail.com','female','9876543240','A-','9876543241','CIVIL','B',2020,2023],
// [117,'Prakash V','prakash117@gmail.com','male','9876543242','AB+','9876543243','CSE','A',2024,2027],
// [118,'Revathi K','revathi118@gmail.com','female','9876543244','B+','9876543245','CSE','B',2024,2027],
// [119,'Senthil M','senthil119@gmail.com','male','9876543246','O+','9876543247','ECE','A',2023,2026],
// [120,'Aarthi P','aarthi120@gmail.com','female','9876543248','A+','9876543249','ECE','B',2023,2026],

// [121,'Naveen S','naveen121@gmail.com','male','9876543250','B+','9876543251','MECH','A',2021,2024],
// [122,'Pooja R','pooja122@gmail.com','female','9876543252','O+','9876543253','MECH','B',2021,2024],
// [123,'Sanjay K','sanjay123@gmail.com','male','9876543254','A-','9876543255','CIVIL','A',2020,2023],
// [124,'Lavanya M','lavanya124@gmail.com','female','9876543256','B-','9876543257','CIVIL','B',2020,2023],
// [125,'Arvind T','arvind125@gmail.com','male','9876543258','AB+','9876543259','CSE','A',2024,2027],
// [126,'Bhavya S','bhavya126@gmail.com','female','9876543260','A+','9876543261','CSE','B',2024,2027],
// [127,'Kiran P','kiran127@gmail.com','male','9876543262','O+','9876543263','ECE','A',2023,2026],
// [128,'Swetha L','swetha128@gmail.com','female','9876543264','B+','9876543265','ECE','B',2023,2026],
// [129,'Manoj V','manoj129@gmail.com','male','9876543266','A-','9876543267','MECH','A',2021,2024],
// [130,'Divya N','divyan130@gmail.com','female','9876543268','O-','9876543269','MECH','B',2021,2024],

// [131,'Surya K','surya131@gmail.com','male','9876543270','B+','9876543271','CIVIL','A',2020,2023],
// [132,'Keerthi S','keerthi132@gmail.com','female','9876543272','A+','9876543273','CIVIL','B',2020,2023],
// [133,'Ajay R','ajay133@gmail.com','male','9876543274','AB+','9876543275','CSE','A',2024,2027],
// [134,'Shalini P','shalini134@gmail.com','female','9876543276','B-','9876543277','CSE','B',2024,2027],
// [135,'Vignesh M','vignesh135@gmail.com','male','9876543278','O+','9876543279','ECE','A',2023,2026],
// [136,'Anu L','anu136@gmail.com','female','9876543280','A-','9876543281','ECE','B',2023,2026],
// [137,'Lokesh T','lokesh137@gmail.com','male','9876543282','B+','9876543283','MECH','A',2021,2024],
// [138,'Preethi S','preethi138@gmail.com','female','9876543284','O+','9876543285','MECH','B',2021,2024],
// [139,'Mohan K','mohan139@gmail.com','male','9876543286','AB+','9876543287','CIVIL','A',2020,2023],
// [140,'Sangeetha R','sangeetha140@gmail.com','female','9876543288','A+','9876543289','CIVIL','B',2020,2023],
// ];
$rows = [];

$departments = ['CSE', 'ECE', 'MECH', 'CIVIL'];
$sections = ['A', 'B'];

$roll = 201;

for ($year = 1; $year <= 4; $year++) {

    for ($i = 1; $i <= 25; $i++) {

        $admissionYear = now()->year - ($year - 1);
        $passoutYear   = $admissionYear + 3;

        $rows[] = [
            $roll,
            "Student $roll",
            "student$roll@gmail.com",
            $i % 2 == 0 ? 'male' : 'female',
            '9' . rand(100000000, 999999999),
            ['A+','B+','O+','AB+'][array_rand(['A+','B+','O+','AB+'])],
            '9' . rand(100000000, 999999999),
            $departments[array_rand($departments)],
            $sections[array_rand($sections)],
            $admissionYear,
            $passoutYear,
        ];

        $roll++;
    }
}


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
