<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoresStudentRequest;
use App\Http\Requests\UpdatedStudentRequest;
use App\Http\Requests;
use App\Imports\StudentsImport;
use App\Models\Student;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{

public function index(Request $request)
{
    $students = Student::with('department', 'section')

        // SEARCH
        ->when($request->search, function ($q) use ($request) {

            $q->where(function ($sub) use ($request) {

                $sub->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('rollnum', 'like', "%{$request->search}%");

            });

        })

        // DEPARTMENT
        ->when($request->department, function ($q) use ($request) {

            $q->where(
                'department_id',
                $request->department
            );

        })

        // SECTION
        ->when($request->section, function ($q) use ($request) {

            $q->where(
                'section_id',
                $request->section
            );

        })

        // SEMESTER
        ->when($request->semester, function ($q) use ($request) {

            $q->where(
                'semester',
                $request->semester
            );

        })

        // ->orderBy('name')
         ->orderBy('semester', 'asc')
        ->paginate(10)
        ->withQueryString();

    if ($request->ajax()) {

        return view(
            'admin.student.partials.table',
            compact('students')
        )->render();

    }

    return view('admin.student.index', [

        'students' => $students,

        'departments' =>
            Department::orderBy('name')->get(),

        'sections' =>
            Section::orderBy('name')->get(),

    ]);
}
    // EXPORT EXCEL
    public function exportExcel(Request $request)
    {
        $students = $this->filteredStudents($request)->get();
        return Excel::download(new StudentsExport($students), 'students.xlsx');
    }

    // EXPORT CSV
    public function exportCsv(Request $request)
    {
    $students = $this->filteredStudents($request)->get();

    return Excel::download(
        new StudentsExport($students),
        'students.csv'
    );
    }


    // EXPORT PDF
   public function exportPdf(Request $request)
{
    $students = $this->filteredStudents($request)->get();

    $pdf = Pdf::loadView('admin.student.pdf', compact('students'));

    return $pdf->download('students.pdf');
}



    // Show create form
    public function create()
    {

           $departments = Department::orderBy('name')->get();
        return view('admin.student.create',compact('departments'));
    }

    // Store student
    public function store(StoresStudentRequest $request)
    {
        // dd($request);
        Student::create($request->validated());
        // dd(Student);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student created successfully');
    }

    // Show single student
    public function show(Student $student)
    {
        return view('admin.student.show', compact('student'));
    }

    // Edit form
   public function edit(Student $student)
{
    $departments = Department::orderBy('name')->get();

    $sections = Section::where('department_id', $student->department_id)->get();

    return view('admin.student.edit', compact(
        'student',
        'departments',
        'sections'
    ));
}

    // Update student
    public function update(UpdatedStudentRequest $request, Student $student)
    {
    // dd("hi");
        $student->update($request->validated());
    // dd($student);
        if ($request->ajax()) {
        return response()->json([
            'status' => true,
            'redirect' => route('admin.students.index')
        ]);
    }
        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student updated successfully');
    }

    // Delete student (AJAX)
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'status' => true,
            'message' => 'Student deleted successfully'
        ]);
    }

    // Excel upload page
    public function excel()
    {
        return view('admin.student.excel');
    }

   
public function import(Request $request)
{
    // dd([
    //     'all' => $request->all(),
    //     'file' => $request->file('file'),
    //     'has_file' => $request->hasFile('file'),
    // ]);

    $request->validate([
        'file' => 'required|file|mimes:csv,txt,xlsx|max:10240',
    ]);

    $import = new StudentsImport();

    Excel::import($import, $request->file('file'));
//  dd([
//         'inserted' => $import->inserted,
//         'updated' => $import->updated,
//         'failures' => $import->failures(),
//     ]);
    return back()->with('summary', [
        'inserted' => $import->inserted,
        'updated' => $import->updated,
    ])->with(
        'failures',
        $import->failures()
    );
}

    // department fetch in 


     public function departmentfetch(Department $department)
    {
        return $department->sections()->select('id','name')->get();
    }


    private function filteredStudents(Request $request)
{
    $currentYear = now()->year;

    return Student::with('department','section')

        // SEARCH
        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('rollnum', 'like', "%{$request->search}%");
            });
        })

        // DEPARTMENT
        ->when($request->department, fn ($q) =>
            $q->where('department_id', $request->department)
        )

        // SECTION
        ->when($request->section, fn ($q) =>
            $q->where('section_id', $request->section)
        )

        // STUDY YEAR (same as table)
        ->when($request->year, function ($q) use ($request, $currentYear) {
            $q->whereRaw(
                "(? - admission_year + 1) = ?",
                [$currentYear, $request->year]
            );
        });
}

}





