<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{{--{!! HTML::style('css/questionstemplate.css') !!}--}}
<table>
	<tr>
		<td style="width: 10px;">&nbsp;</td>
		<td colspan="3"><h1>Master Users Upload Template</h1></td>
	</tr>
    <tr class="info header">
        <td style=""></td>
        <td style="width: 30px;"><b>Identity Number</b></td>
        <td style="width: 30px;"><b>First Name</b></td>
        <td style="width: 30px;"><b>Last Name</b></td>
        <td style="width: 20px;"><b>Class</b></td>
        <td style="width: 20px;"><b>Class Arm</b></td>
    </tr>
	<tbody>
    @foreach($classes as $class)
		@foreach($class->class_arm as $classArm)
        <tr>
			<td class="text" style="width: 10px;">&nbsp;</td>
			<td class="text">&nbsp;</td>
			<td class="text">&nbsp;  </td>
			<td class="text">&nbsp; </td>
			<td class="text">{{$class->name}}</td>
			<td class="text">{{$classArm->arm_name}}</td>
		</tr>
		@endforeach
    @endforeach
    </tbody>
</table>
</html>