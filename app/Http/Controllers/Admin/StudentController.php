<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoresStudentRequest;
use App\Http\Requests\UpdatedStudentRequest;
use App\Imports\StudentsImport;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    // List students


    public function index(Request $request)
    {
        $students = Student::query()

            // SEARCH
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%")
                        ->orWhere('rollnum', 'like', "%{$request->search}%");
                });
            })

            // FILTER: DEPARTMENT
            ->when($request->department, function ($q) use ($request) {
                $q->where('department', $request->department);
            })

            // FILTER: SECTION
            ->when($request->section, function ($q) use ($request) {
                $q->where('section', $request->section);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.student.index', compact('students'));
    }

    // EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    // EXPORT CSV
    public function exportCsv()
    {
        return Excel::download(new StudentsExport, 'students.csv');
    }

    // EXPORT PDF
    public function exportPdf()
    {
        $students = Student::all();
        $pdf = Pdf::loadView('admin.student.pdf', compact('students'));
        return $pdf->download('students.pdf');
    }


    // Show create form
    public function create()
    {
        return view('admin.student.create');
    }

    // Store student
    public function store(StoresStudentRequest $request)
    {
        Student::create($request->validated());

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
        return view('admin.student.edit', compact('student'));
    }

    // Update student
    public function update(UpdatedStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

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

    // Import CSV / Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx'
        ]);

        $import = new StudentsImport();
        Excel::import($import, $request->file('file'));

        return back()
            ->with('summary', [
                'inserted' => $import->inserted,
            ])
            ->with('failures', $import->failures());
    }
}
