@extends('layouts.admin')

@section('title','Students')
@section('content')

<h2>Sections</h2>

<a href="{{ route('admin.sections.create') }}">Add Section</a>



<table border="1">
@foreach($sections as $s)
<tr>
<td>{{ $s->name }}</td>
<td>
<a href="{{ route('sections.edit',$s) }}">Edit</a>
<form action="{{ route('sections.destroy',$s) }}" method="POST" style="display:inline">
@csrf @method('DELETE')
<button>Delete</button>
</form>
</td>
</tr>
@endforeach
</table>

@endsection