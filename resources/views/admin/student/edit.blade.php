@extends('layouts.admin')

@section('title','Edit Student')

@section('content')

<div class="max-w-4xl mx-auto bg-white rounded-2xl border shadow-sm">

    {{-- Header --}}
    <div class="px-8 py-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Edit Student</h2>
        <p class="text-sm text-gray-500 mt-1">
            Update student personal and academic details
        </p>
    </div>

    {{-- Form --}}
    <form id="studentEditForm"
          method="POST"
          action="{{ route('admin.students.update', $student->id) }}"
          class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6"
          novalidate>

        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label class="label">Student Name</label>
            <input type="text"
                   name="name"
                   class="input"
                   value="{{ old('name', $student->name) }}">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="label">Email Address</label>
            <input type="email"
                   name="email"
                   class="input"
                   value="{{ old('email', $student->email) }}">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Gender --}}
        <div>
            <label class="label">Gender</label>
            <select name="gender" class="input">
                <option value="">Select gender</option>
                <option value="male"
                    {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>
                    Male
                </option>
                <option value="female"
                    {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>
                    Female
                </option>
                <option value="other"
                    {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>
                    Other
                </option>
            </select>
            @error('gender') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Roll Number --}}
        <div>
            <label class="label">Roll Number</label>
            <input name="rollnum"
                   class="input"
                   value="{{ old('rollnum', $student->rollnum) }}">
            @error('rollnum') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="label">Student Phone</label>
            <input name="phone"
                   class="input"
                   value="{{ old('phone', $student->phone) }}">
            @error('phone') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Father Phone --}}
        <div>
            <label class="label">Father Phone</label>
            <input name="father_phone"
                   class="input"
                   value="{{ old('father_phone', $student->father_phone) }}">
            @error('father_phone') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Department --}}
        <div>
    <label class="label">Department</label>

    <select name="department_id" id="department" class="input">
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}"
                {{ $student->department_id == $dept->id ? 'selected' : '' }}>
                {{ $dept->code }} - {{ $dept->name }}
            </option>
        @endforeach
    </select>

    @error('department_id') <p class="error">{{ $message }}</p> @enderror
</div>

        {{-- Section --}}
        <div>
    <label class="label">Section</label>

    <select name="section_id" id="section" class="input">
        @foreach($sections as $sec)
            <option value="{{ $sec->id }}"
                {{ $student->section_id == $sec->id ? 'selected' : '' }}>
                {{ $sec->name }}
            </option>
        @endforeach
    </select>

    @error('section_id') <p class="error">{{ $message }}</p> @enderror
</div>


        {{-- Academic Year --}}
        <!-- <div>
            <label class="label">Academic Year</label>
            <input name="academic_year"
                   class="input"
                   value="{{ old('academic_year', $student->academic_year) }}">
            @error('academic_year') <p class="error">{{ $message }}</p> @enderror
        </div> -->

        {{-- Admission Year--}}

         <div>
            <label class="label">Admission Year</label>
            <input name="admission_year"
                   class="input"
                   value="{{ old('admission_year', $student->admission_year) }}">
            @error('admission_year') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Passout Year --}}
        <div>
            <label class="label">Passout Year</label>
            <input name="passout_year"
                   class="input"
                   value="{{ old('passout_year', $student->passout_year) }}">
            @error('passout_year') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.students.index') }}"
               class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">
                Back 
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white
                       hover:bg-indigo-700 transition">
                Update Student
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
$(function () {

    $("#studentEditForm").validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            gender: { required: true },
            rollnum: { required: true },
            phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
            father_phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
            department: { required: true },
            section: { required: true },
            academic_year: { required: true },
            passout_year: { required: true }
        },

        submitHandler: function (form) {

            let formData = new FormData(form);

            $.ajax({
                url: form.action,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function () {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Student updated successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },

                error: function (xhr) {
                    $(".error").remove();

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            let input = $(`[name="${key}"]`);
                            input.after(`<p class="error text-red-500 text-xs mt-1">${value[0]}</p>`);
                        });
                    }
                }
            });

            return false;
        }
    });

});

$('#department').change(function () {

    let deptId = $(this).val();

    $.get('/admin/departments/' + deptId + '/sections', function (data) {

        $('#section').empty();
        $('#section').append('<option value="">Select Section</option>');

        data.forEach(sec => {
            $('#section').append(
                `<option value="${sec.id}">${sec.name}</option>`
            );
        });

    });
});

</script>
@endpush

@endsection
