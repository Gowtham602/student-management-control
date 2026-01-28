<?php

namespace App\Http\Controllers\Admin;
use App\Imports\StudentsImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoresStudentRequest;
use App\Http\Requests\UpdatedStudentRequest;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request; 





class StudentController extends Controller
{
    // List students
    public function index()
    {
        $students = Student::latest()->get();
        return view('admin.student.index', compact('students'));
    }

    // Show create form
    public function createStudent()
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

    public function edit(Student $student)
    {
    return view('admin.student.edit', compact('student'));
    }

    public function update(UpdatedStudentRequest $request, Student $student)
    {
    $student->update($request->validated());

    return redirect()
        ->route('admin.students.index')
        ->with('success', 'Student updated successfully');  
    }

    public function destroy(Student $student)
    {
    $student->delete();

    return response()->json([
        'status' => true,
        'message' => 'Student deleted successfully'
    ]);
    }


    //show and info particular students 

    public function show(Student $student)
    {
    return view('admin.student.show', compact('student'));
    }



    //excel upload 
    public function excel(){
        return view('admin.student.excel');
    }

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
