@extends('app')

@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Examination: {!! $examination->title !!}
					<small class="pull-right"><span id="countdown"></span></small>
				</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="container-fluid">{{-- oncontextmenu="return false"--}}
			@if(Session::has('message'))
				<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times; </button>
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

			<div class="row">
				<div class="alert alert-info">
					<p class="lead">Instructions</p>
					<ul>
						<li>Please, Do not close, refresh or navigate away from this page or you will not have a score.</li>
						<li>Clicking on finish will automatically submit this examination</li>
						<li>Contact the examiners present if you are experiencing any issues</li>
					</ul>
				</div>
				<div class="alert collapse alert-dismissible alert-warning" id="time-warning">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					You now have less than 5 minutes to finish this examination.
				</div>
				<div class="row col-md-10 col-md-offset-1" style="min-height: 400px;">
					{!! Form::open(['route' => array('client.examinations.store'),'class' => '']) !!}
					<?php

					$counter = 0;
					$lastElement = count($questionCollection) - 1;
					$questionPerPage = $examination->no_questions_per_page;
					$pageControl = 1;
					$index = 1;
					$html = "<div id='questions'>";
					for ($counter = 0; $counter < count($questionCollection); $counter++) {
						if ($counter == 0) {
							$html .= "<div class = 'questiondiv page" . $pageControl . "' style='display:'>";
							$pageControl++;
						}
						if (((($counter) % $questionPerPage) == 0) & $counter > 0) {
							$html .= "</div><div class = 'questiondiv page" . $pageControl . "' style='display:none;'>";
							$pageControl++;
						}
						$question = $questionCollection[$counter];
						$html .= "<div class='panel'><div class='panel-body'><div style='float:left;width: 100px;margin-right: 40px;'><span style='font-size:50px;'>" . $index . "</span></div><div style='float:left'><ul style='list-style-type: none;padding-left:0px;'>
						<p class='lead'>" . $question->title . "</p>";
						$html .= "<div class='col-md-12' style='border: #000 1px solid'>";
						foreach($question->qnImages as $qnImages)
						{
							$html .= "<div class='col-md-6'>";
							$html .= "<img src='/questions/".$qnImages->image_url."' class='col-md-12'>";
							$html .= "</div>";
						}
						
						$html .= "</div>";
						$correct_options = [];
						foreach ($question->options as $option) {
							if ($option->correct_answer) {
								$correct_options[] = 1;
							}
						}
						$type = (sizeof($correct_options) == 1) ? 'radio name=answers' . $index : 'checkbox name=answers' . $index . '[]';

						foreach ($question->options as $option) {
							$html .= "<div class='radio'><label><input type=$type  value=" . $option->id . "> " . $option->title . "</label><br>
							</div>";
							$html .= "<div class='col-md-12'>";
							foreach($option->optionImages as $optionImages)
							{
								$html .= "<div class='col-md-6'>";
								$html .= "<img src='/options/".$optionImages->image_url."' class='col-md-12'>";
								$html .= "</div>";
							}
							
							$html .= "</div>";
						}
						$html .= "</div></div></div>";

						
						$index++;
					}
					if ($counter == ($lastElement)) {
						$html .= "</div></div>";
					}
					$html .= "</div>";
					echo $html;
					?>

				</div>
				<div class="col-lg-12">
					<div class="form-group">

						<div class="col-lg-6 col-lg-offset-4">
							<p class="myfooter">

							</p>
						</div>
						<div class="col-lg-2">
							{!!   Form::hidden('examination_id',$examination->id)    !!}
							<div class="pull-right">
								{!! Form::submit("Finish",array("class" => "btn btn-success", 'id' => 'submitExam')) !!}
								{{--"onClick" => "return confirm('Sure you want to submit this exam ?')",--}}
							</div>
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
@endsection

@section('scripts')
	<script>
		dtotal = <?php echo ceil(count($questionCollection)/$questionPerPage); ?>;
		$(document).ready(function () {
			$('div.pull-left p.myfooter ul.pagination.bootpag li.prev.disabled').css('display', 'none');
			$('p.myfooter').bootpag({
				total: dtotal,
				page: 1,
				maxVisible: 8,
				leaps: false,
				wrapClass: 'pagination',
				activeClass: 'active',
				disabledClass: 'disabled',
				next: "Next",
				prev: "Previous"
			}).on("page", function (event, num) {
				//alert(num);
				var previousPage = $('div#questions > div.questiondiv').filter(function () {
					return this.style.display == '';
				});
				previousPage.css('display', 'none');
				//alert('div#questions > div.questiondiv.page' + num);
				$('div#questions > div.questiondiv.page' + num).css('display', '');
//				if(num == dtotal){
//					$('div.pull-left ul.pagination.bootpag li.disabled').css('display','none');
//				}
//				else{
//					$('div.pull-left ul.pagination.bootpag li').css('display','');
//				}
			});
		});

	</script>


	<script type="text/javascript">

		//Countdown timer
		var startTime = Date.now();
		var endtime = startTime + {{($examination->duration * 60 * 1000)}};
		var examTime = moment(endtime).format('YYYY/MM/DD HH:mm:ss');
		var warned = false; //set to true if the time warning has been shown
		$("#countdown").countdown(examTime)
				.on('update.countdown', function (event) {
					var hours = parseInt(event.strftime('%-H')) * 60 * 60 * 1000;
					var minutes = parseInt(event.strftime('%-M')) * 60 * 1000;
					var seconds = parseInt(event.strftime('%-M')) * 1000;
					var timeLeft = hours + minutes + seconds;
					if (timeLeft <= 5 * 60 * 1000) {
						if (!warned) {
							$('#time-warning').slideDown('1000');
							warned = true;
						}

						$(this).addClass('text-danger');
					}
					else {
						$(this).addClass('text-info');
					}
					$(this).text(
							event.strftime('%-H hour%!H, %-M minute%!M, %S sec%!S')
					);
				})
				.on('finish.countdown', function (event) {
					$('#submitExam').trigger('click');
				});


	</script>
	{{--<script type="text/javascript">--}}
	{{--window.onbeforeunload = function() {--}}
	{{--return "Do not refresh this page. It will end your exam with score of zero.";--}}
	{{--}--}}
	{{--</script>--}}
@stop
