<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Department;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SectionImport;
class SectionController extends Controller
{
   public function index()
    {
    $sections = Section::with('department')
    ->join('departments', 'sections.department_id', '=', 'departments.id')
    ->orderBy('departments.name')
    ->orderBy('sections.name')
    ->select('sections.*')
    ->get();
    //  dd($query->toSql(), $query->getBindings());
    return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
    $departments = Department::orderBy('name')->get();
    return view('admin.sections.create', compact('departments'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'name' => 'required|max:5'
    ]);

    Section::create([
        'department_id' => $request->department_id,
        'name' => strtoupper($request->name)
    ]);

    return redirect()->route('admin.sections.index')->with('success','Section added');
    }

    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|max:20'
        ]);

        $section->update($request->all());

        return redirect()->route('admin.sections.index')->with('success','Updated');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return back()->with('success','Deleted');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx'
        ]);

        Excel::import(new SectionImport, $request->file('file'));

        return back()->with('success','Imported successfully');
    }
}

