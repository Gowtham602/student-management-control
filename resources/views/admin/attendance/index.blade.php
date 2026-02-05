@extends('layouts.admin')

@section('title','Student Day Attendance')

@section('content')
<style>
.attendance{
    width: 45px;
    padding: 4px;
    border-radius: 8px;
    border: 1px solid #ddd;
    text-align: center;
    font-weight: 600;
    cursor: pointer;
}

/* Colors */
.attendance.P { background:#d1fae5; color:#065f46; }
.attendance.A { background:#fee2e2; color:#991b1b; }
.attendance.H { background:#fef3c7; color:#92400e; }

.attendance:hover{
    transform: scale(1.05);
    transition:.15s;
}
</style>


<div class="bg-white rounded-xl shadow p-4 overflow-x-auto">

<table class="min-w-full border text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-3 py-2">S.No</th>
            <th class="border px-3 py-2">Roll No</th>
            <th class="border px-3 py-2">Student Name</th>

            @for($d=1; $d<=$daysInMonth; $d++)
                <th class="border px-2 py-2 text-center">
                    {{ $d }}/{{ $month }}
                </th>
            @endfor
        </tr>
    </thead>

    <tbody>
        @foreach($students as $i => $student)
        <tr>
            <td class="border px-2 py-2">{{ $i+1 }}</td>
            <td class="border px-2 py-2">{{ $student->roll_no }}</td>
            <td class="border px-2 py-2">{{ $student->name }}</td>

            @for($d=1; $d<=$daysInMonth; $d++)
                @php
                    $date = \Carbon\Carbon::create($year,$month,$d)->toDateString();
                    $key = $student->id.'_'.$date;
                    $status = $attendances[$key]->status ?? '';
                @endphp

              <td class="border text-center">
    <select
        class="attendance {{ $status }}"
        data-student="{{ $student->id }}"
        data-date="{{ $date }}"
    >
        <option value=""></option>
        <option value="P" @selected($status=='P')>P</option>
        <option value="A" @selected($status=='A')>A</option>
        <option value="H" @selected($status=='H')>H</option>
    </select>
</td>

            @endfor
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@push('scripts')
<script>
$('.attendance').on('change', function () {

    let el = $(this);
    let status = el.val();

    el.removeClass('P A H').addClass(status);

    $.ajax({
        url: "{{ route('admin.attendance.save') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            student_id: el.data('student'),
            date: el.data('date'),
            status: status
        }
    });

});
</script>

@endpush

@endsection
