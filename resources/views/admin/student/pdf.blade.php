<h3>Students List</h3>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Roll No</th>
        <th>Name</th>
        <th>Department</th>
        <th>Section</th>
        <th>Phone</th>
    </tr>

    @foreach($students as $student)
    <tr>
        <td>{{ $student->rollnum }}</td>
        <td>{{ $student->name }}</td>
        <td>{{ $student->department }}</td>
        <td>{{ $student->section }}</td>
        <td>{{ $student->phone }}</td>
    </tr>
    @endforeach
</table>
