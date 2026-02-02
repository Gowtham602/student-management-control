@extends('layouts.admin')

@section('title','Students')
@section('content')

<h2>Sections</h2>

<a href="{{ route('admin.sections.create') }}">Add Section</a>



<table border="1">
@foreach($sections as $s)
<tr>
<td>{{ $s->department->name }}</td>
<td>{{ $s->name }}</td>
</tr>
@endforeach

</table>

@endsection