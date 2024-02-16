@extends('app')

@section('content')

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    {!! "Step Two Select Candidates For ".$examination->title." Examination"!!}
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="container-fluid">

            <div class="row">
                @if(Session::has('message'))
                    <div class="alert alert-dismissible alert-success">
                        <button type="button" class="close" data-dismiss="alert">X</button>
                        {{ Session::get('message') }}
                    </div>
                @endif
                @if($errors->any())
                    <ul class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li> {{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="col-sm-12">
                    {!! Form::open(['url' => '/admin/exams/' . $examination->id . '/class/step3','class' => 'form']) !!}

                    <div class="form-group">
                        <p class="form-control-static">Select the Candidates Department and Level</p>
                    </div>


                    <div class="form-group" style="width: 200px;">
                        {!! Form::label('department', "Candidates Department" . ':') !!}
                        {!! Form::select('department',$departments,Input::old('department'),array('class' => 'form-control')) !!}
                    </div>
                    <div class="form-group" style="width: 200px;">
                        {!! Form::label('level', "Candidates Level" . ':') !!}
                        {!! Form::select('level',$levels,Input::old('level'),array('class' => 'form-control')) !!}
                    </div>

                    {!! Form::hidden('examination_id',$examination->id)!!}

                    <div class="form-group">
                    </div>
                    <div class="form-group">
                        <div class="pull-left">
                            {!! Form::submit("Next",array('class' => 'btn btn-primary','onClick' => 'this.form.submit(); this.disabled=true; this.value=\'Processing\'')) !!}
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-danger" href="javascript:history.back('-1')">{!! "Go Back" !!}</a>
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>
@endsection
