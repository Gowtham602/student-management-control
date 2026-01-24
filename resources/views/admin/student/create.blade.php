@extends('layouts.admin')

@section('title','Add Student')

@section('content')

<div class="max-w-4xl mx-auto bg-white rounded-2xl border shadow-sm">

    {{-- Header --}}
    <div class="px-8 py-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Add New Student</h2>
        <p class="text-sm text-gray-500 mt-1">
            Enter student personal and academic details
        </p>
    </div>

    {{-- Form --}}
    <form id="studentForm" method="POST" novalidate
          action="{{ route('admin.students.store') }}"
          class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf

        {{-- Name --}}
        <div>
            <label class="label">Student Name</label>
            <input type="text" name="name" class="input" value="{{ old('name') }}">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="label">Email Address</label>
            <input type="email" name="email" class="input" value="{{ old('email') }}">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Gender --}}
        <div>
            <label class="label">Gender</label>
            <select name="gender" class="input">
                <option value="">Select gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                    Male
                </option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                    Female
                </option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                    Other
                </option>
            </select>

            @error('gender') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Roll Number --}}
        <div>
            <label class="label">Roll Number</label>
            <input name="rollnum" class="input" value="{{ old('rollnum') }}">

            @error('rollnum') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="label">Student Phone</label>
            <input name="phone" class="input" value="{{ old('phone') }}">

            @error('phone') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Father Phone --}}
        <div>
            <label class="label">Father Phone</label>
          <input name="father_phone" class="input" value="{{ old('father_phone') }}">

            @error('father_phone') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Department --}}
        <div>
            <label class="label">Department</label>
            <input name="department" class="input" value="{{ old('department') }}">

            @error('department') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- Section --}}
        <div>
            <label class="label">Section</label>
           <input name="section" class="input" value="{{ old('section') }}">

            @error('section') <p class="error">{{ $message }}</p> @enderror
        </div>

        <!-- year -->
         <div>
            <label class="label">Academic Year</label>
           <input name="academic_year" class="input" value="{{ old('academic_year') }}">

            @error('academic_year') <p class="error">{{ $message }}</p> @enderror
        </div>

        <!-- passout  -->
        <div>
            <label class="label">Passout Year</label>
           <input name="passout_year" class="input" value="{{ old('passout_year') }}">

            @error('passout_year') <p class="error">{{ $message }}</p> @enderror
        </div>

 
        {{-- Actions --}}
        <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.students.index') }}"
               class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white
                       hover:bg-indigo-700 transition">
                Save Student
            </button>
        </div>

    </form>
</div>
@push('scripts')
<script>
$(function () {

    $("#studentForm").validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            gender: { required: true },
            rollnum: { required: true },
            phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
            father_phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
            department: { required: true },
            section: { required: true },
            year:{ required: true},
            passout_year:{ required: true}

        },
        submitHandler: function (form) {

            let formData = new FormData(form);

            $.ajax({
                url: form.action,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Student added successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    form.reset();
                },
                error: function (xhr) {

                    let errors = xhr.responseJSON.errors;

                    $(".error").remove();

                    $.each(errors, function (key, value) {
                        let input = $(`[name="${key}"]`);
                        input.after(`<p class="error text-red-500 text-xs mt-1">${value[0]}</p>`);
                    });
                }
            });

            return false;
        }
    });

});
</script>
@endpush




@endsection
