<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentCommunication;
use App\Models\ParentCommunicationRecipient;
use App\Models\ParentMessageTemplate;
use App\Models\Student;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\Section;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Log;


class ParentCommunicationController extends Controller
{
    // public function index()
    // {
    //     $templates = ParentMessageTemplate::where(
    //         'status',
    //         true
    //     )->get();

    //     return view(
    //         'admin.parent-communications.index',
    //         compact('templates')
    //     );
    // }



  public function index()
    {
        $departments = Department::orderBy('name')->get();

        $sections = Section::orderBy('name')->get();

        $academicYears = AcademicYear::orderByDesc('id')->get();

        $templates = ParentMessageTemplate::where('status', true)
            ->orderBy('name')
            ->get();
            

        return view(
            'admin.parent-communications.index',
            compact(
                'departments',
                'sections',
                'academicYears',
                'templates'
            )
        );
    }
//     public function students(Request $request)
// {
//     $request->validate([
//         'department_id' => 'required|exists:departments,id',
//         'section_id' => 'required|exists:sections,id',
       
//     ]);

//     $students = Student::query()
//         ->where(
//             'department_id',
//             $request->department_id
//         )
//         ->where(
//             'section_id',
//             $request->section_id
//         )
       
//         // ->where('status', 1)
//         ->get();

//     return response()->json([
//         'success' => true,
//         'students' => $students,
//     ]);
// }


public function students(Request $request)
{
    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'section_id'    => 'required|exists:sections,id',
        'year'          => 'required|in:1,2,3,4',
    ]);

    $semesterMap = [
        1 => [1, 2], // I Year
        2 => [3, 4], // II Year
        3 => [5, 6], // III Year
        4 => [7, 8], // IV Year
    ];

    $semesters = $semesterMap[$request->year];

    $students = Student::query()
        ->where('department_id', $request->department_id)
        ->where('section_id', $request->section_id)
        ->whereIn('semester', $semesters)
        ->orderBy('rollnum')
        ->get([
            'id',
            'name',
            'rollnum',
            'father_phone',
            'semester',
        ]);

    return response()->json([
        'success'  => true,
        'students' => $students,
    ]);
}
public function store(Request $request)
{
    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'section_id' => 'required|exists:sections,id',
     
        'template_id' => 'required|exists:parent_message_templates,id',
        'message' => 'required|string',
        'student_ids' => 'required|array|min:1',
        'student_ids.*' => 'exists:students,id',
    ]);

    DB::beginTransaction();

    try {

        $communication = ParentCommunication::create([
            'created_by' => auth()->id(),

            'department_id' => $request->department_id,

            'section_id' => $request->section_id,

      
            'template_id' => $request->template_id,

            'message' => $request->message,

            'status' => 'PENDING',

            'submitted_at' => now(),

            'total_students' =>
                count($request->student_ids),
        ]);

        $students = Student::whereIn(
            'id',
            $request->student_ids
        )->get();

        foreach ($students as $student) {

            /*
             * Replace these fields with your actual
             * student/parent columns.
             */

            ParentCommunicationRecipient::create([

                'parent_communication_id' =>
                    $communication->id,

                'student_id' => $student->id,

                'student_name' =>
                    $student->name,

                'parent_name' =>
                    $student->father_name ?? null,

                'phone' =>
                    $student->father_phone ??
                    $student->parent_phone,

                'message' =>
                    $request->message,

                'status' => 'PENDING',
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' =>
                'Communication submitted for approval.',
            'id' => $communication->id,
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
public function confirm($id)
{
    $communication =
        ParentCommunication::with('recipients')
            ->findOrFail($id);

    if ($communication->status !== 'PENDING') {

        return response()->json([
            'success' => false,
            'message' =>
                'Communication is already processed.'
        ], 422);
    }

    $communication->update([
        'status' => 'CONFIRMED',
        'confirmed_by' => auth()->id(),
        'confirmed_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' =>
            'Communication confirmed successfully.'
    ]);
}
// public function send($id)
// {
//     $communication =
//         ParentCommunication::with('recipients')
//             ->findOrFail($id);

//     if ($communication->status !== 'CONFIRMED') {

//         return response()->json([
//             'success' => false,
//             'message' =>
//                 'Communication is not confirmed.'
//         ], 422);
//     }

//     $communication->update([
//         'status' => 'SENDING'
//     ]);

//     $sent = 0;
//     $failed = 0;

//     foreach ($communication->recipients as $recipient) {

//         if (!$recipient->phone) {

//             $recipient->update([
//                 'status' => 'FAILED',
//                 'sms_response' =>
//                     'Parent phone number missing.',
//             ]);

//             $failed++;

//             continue;
//         }

//         $result = SmsService::send(
//             $recipient->phone,
//             $recipient->message,
//             $communication->template?->template_id
//         );

//         if ($result['success']) {

//             $recipient->update([
//                 'status' => 'SENT',
//                 'sms_response' =>
//                     $result['response'],
//                 'sent_at' => now(),
//             ]);

//             $sent++;

//         } else {

//             $recipient->update([
//                 'status' => 'FAILED',
//                 'sms_response' =>
//                     $result['response'],
//             ]);

//             $failed++;
//         }
//     }

//     $communication->update([
//         'total_sent' => $sent,
//         'total_failed' => $failed,

//         'status' => $failed === 0
//             ? 'SENT'
//             : ($sent > 0
//                 ? 'PARTIAL'
//                 : 'FAILED'),
//     ]);

//     return response()->json([
//         'success' => true,
//         'sent' => $sent,
//         'failed' => $failed,
//     ]);
// }



public function send($id)
{
    Log::info('========== PARENT SMS SEND START ==========', [
        'communication_id' => $id,
    ]);

    $communication = ParentCommunication::with([
        'recipients',
        'template'
    ])->findOrFail($id);

    Log::info('COMMUNICATION FOUND', [
        'id' => $communication->id,
        'status' => $communication->status,
        'template_id' => $communication->template?->template_id,
        'recipient_count' => $communication->recipients->count(),
    ]);

    if ($communication->status !== 'CONFIRMED') {

        Log::warning('COMMUNICATION NOT CONFIRMED', [
            'id' => $id,
            'status' => $communication->status,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Communication is not confirmed.'
        ], 422);
    }

    $communication->update([
        'status' => 'SENDING'
    ]);

    $sent = 0;
    $failed = 0;

    foreach ($communication->recipients as $recipient) {

        Log::info('PROCESSING PARENT RECIPIENT', [
            'recipient_id' => $recipient->id,
            'phone' => $recipient->phone,
            'message' => $recipient->message,
            'status' => $recipient->status,
        ]);

        if (!$recipient->phone) {

            $recipient->update([
                'status' => 'FAILED',
                'sms_response' => 'Parent phone number missing.',
            ]);

            $failed++;

            continue;
        }

        $result = SmsService::send(
            $recipient->phone,
            $recipient->message,
            $communication->template?->template_id
        );

        Log::info('PARENT SMS RESULT', [
            'recipient_id' => $recipient->id,
            'phone' => $recipient->phone,
            'result' => $result,
        ]);

        if ($result['success']) {

            $recipient->update([
                'status' => 'SENT',
                'sms_response' => $result['response'],
                'sent_at' => now(),
            ]);

            $sent++;

        } else {

            $recipient->update([
                'status' => 'FAILED',
                'sms_response' => $result['response'],
            ]);

            $failed++;
        }
    }

    $communication->update([
        'total_sent' => $sent,
        'total_failed' => $failed,

        'status' => $failed === 0
            ? 'SENT'
            : ($sent > 0
                ? 'PARTIAL'
                : 'FAILED'),
    ]);

    Log::info('========== PARENT SMS SEND END ==========', [
        'communication_id' => $id,
        'sent' => $sent,
        'failed' => $failed,
    ]);

    return response()->json([
        'success' => true,
        'sent' => $sent,
        'failed' => $failed,
    ]);
}

// http://127.0.0.1:8000/admin/parent-communications/test-sms
public function testSms()
{
    $result = SmsService::send(
        '9344783117',
        "Dear Parents,
Parents meeting will be held for III Year on a 20th August 2026 in the College Campus, mandatory for all the parents to attend the meeting.
Principal
TSACBON -IDEAL SMS",
        '1407171567505451288'
    );

    dd($result);
}


// public function pending()
// {
//     $communications = ParentCommunication::with([
//         'department',
//         'section',
//         'template',
//         'recipients',
//     ])
//     ->where('status', 'PENDING')
//     ->latest()
//     ->get();

//     return view(
//         'admin.parent-communications.pending',
//         compact('communications')
//     );
// }

public function pending()
{
    $communications = ParentCommunication::with([
        'department',
        'section',
        'template',
        'recipients',
    ])
    ->whereIn('status', ['PENDING', 'CONFIRMED'])
    ->latest()
    ->get();

    return view(
        'admin.parent-communications.pending',
        compact('communications')
    );
}

}