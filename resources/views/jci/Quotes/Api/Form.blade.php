@css('css/datetimepicker/bootstrap-datetimepicker.css', 'bootstrap-datetimepicker')
@css('css/rfq-forms.css', 'rfq-forms')

{{-- Let Document know the js files we're trying to add --}}
@js('plugins/daterangepicker/moment.js', 'moment')
@js('js/datetimepicker/bootstrap-datetimepicker.js', 'bootstrap-datetimepicker', 'moment')

{{-- Let Document know the js blocks we're trying to add --}}
@jsblock("Quotes.Form.partials.button-disabler", "button-disabler")
@jsblock("Quotes.Form.partials.button-handlers", "button-handlers")
@jsblock("Quotes.Form.partials.linked-questions-handler", "linked-questions")
@jsblock("Quotes.Form.partials.covers-input-handlers", "covers-handler")

@php $rname = $api ? 'api.' : ''; @endphp

@foreach($groups as $group)
	{{-- register tab --}}
	@push('form-tabs')
		<li class="form-tab-step {{ $loop->last ? 'last' : '' }}" id="step-{{$group->Name}}"><a href="#tab-{{$group->Name}}">{{ $group->DisplayText }}</a></li>
	@endpush
	@push('form-content') 
		<div class="rfq-group hidden {{ $loop->last ? 'last' : '' }} row col-md-12" id="tab-{{ $group->Name }}" >
			{{Form::open(['route' => [$rname .'quotes.validate', $PolicyTypeID, $FormTypeID, $group->FormQuestionGroupID, 'OrganisationID' => $OrganisationID, '_token' => csrf_token()], 'class' => 'grid-100 centered'])}}

				<div class="grid-100 disp-inline-table">
					<h4 class="form-tab-header grid-40 disp-inline-block form-section-title v-space-2x">{{ $group->DisplayText }}</h4>
					<div class="form-group grid-60 disp-inline-block button-group text-right">
						@if($loop->first)
							<a class='btn btn-default' href="{{ route('quotes.request', [$OrganisationID]) }}" disabled>Previous</a>
						@else
							<button class='btn btn-default btn-back'>Previous <i class='cp-spinner cp-eclipse '></i></button>
						@endif
						<button class='btn btn-default btn-submit'>{{ $loop->last ? 'Submit' : 'Next' }}<i class='cp-spinner cp-eclipse'></i></button>
					</div>
				</div>

				<div class="form-fields grid-100 v-space-2x">
					@if($group->Name === 'ContactDetails')
						@include("Quotes.Api.ContactDetails", ['current' => $group])
					@else
						@include("Quotes.Api.DynamicQuestions", ['current' => $group])	
					@endif
				</div>
				<div class="form-footer v-space-2x grid-100 disp-block">
					<div class="form-group grid-100 button-group text-right">
						@if($loop->first)
							<a class='btn btn-default' href="{{ route('quotes.request', [$OrganisationID]) }}" disabled>Previous</a>
						@else
							<button class='btn btn-default btn-back'>Previous <i class='cp-spinner cp-eclipse '></i></button>
						@endif
						<button class='btn btn-default btn-submit'>{{ $loop->last ? 'Submit' : 'Next' }}<i class='cp-spinner cp-eclipse'></i></button>
					</div>
				</div> 
			{{Form::close()}}
		</div>
	@endpush
	@php $document->deleteBlock("repeat-buttons") @endphp
@endforeach
@php 
	$document->deleteAsset('css', 'app');
	$document->deleteAsset('js', 'app');
@endphp

@assets('css')
@assets('cssblock')
<div class="rfq-form">
	<div class="tabs-wrapper">
		<ul class="form-tabs">@stack('form-tabs')</ul>
	</div>
	<div class="form-tab-contents">
		@stack('form-content')
		<div id="form-submit">
			{{ Form::open(['route' => [$rname .'quotes.submit', $PolicyTypeID, $FormTypeID, $group->FormQuestionGroupID, 'OrganisationID' => $OrganisationID, '_token' => csrf_token()]]) }}	
			{{ Form::close() }}
		</div>
	</div>
</div>

<script>
	var rfq_user = {!! json_encode(Auth::user()) !!} || {};
	var rfq_client = {!! Auth::check() && Auth::user()->is_client ? json_encode(Auth::user()) : '{}' !!};	
</script>

@assets('js')
@assets('jsblock')

<script>
	$(document).ready(function() {


		$('.datetimepicker').each(function() {
			var me = $(this);
				format = me.data('date-format') || 'D/M/YYYY';

			me.datetimepicker({
				format: format,
				viewMode: 'years'
			});
		});
	});
</script>

<script>
	$(document).ready(function(){
		var bindMailAddressUse = function() {
			var me = $(this);
			var Address = me.parents('.Address:first');
			var type = me.data('address-use') || 'mail_addr';

			if (!me.is(':checked') || !me.val()) {
				Address.removeClass('use-addr');
				Address.find('.address-wrapper').removeClass('hidden');
				Address.find('input, select').attr('disabled', false);
				return false;
			}
			Address.addClass('use-addr');	
			Address.find('.address-wrapper').addClass('hidden');
			var mail_address 	= rfq_client.mail_address || {};
			var home_address 	= rfq_client.home_address || {};

			var AddressID 		= $('#ContactDetails-'+ type +'-AddressID').val();
			//var UnitNumber 		= $('#ContactDetails-'+ type +'-UnitNumber').val();
			//var StreetNumber 	= $('#ContactDetails-'+ type +'-StreetNumber').val();
			//var StreetName 		= $('#ContactDetails-'+ type +'-StreetName').val();
			var AddressLine1 	= $('#ContactDetails-'+ type +'-AddressLine1').val();
			var AddressLine2 	= $('#ContactDetails-'+ type +'-AddressLine2').val();
			var City 			= $('#ContactDetails-'+ type +'-City').val();
			var State 			= $('#ContactDetails-'+ type +'-State').val();
			var Postcode 		= $('#ContactDetails-'+ type +'-Postcode').val();
			var Country 		= $('#ContactDetails-'+ type +'-Country').val();
			
			Address.find('input.AddressID').val(AddressID);	
			//Address.find('.UnitNumber input').attr('disabled', true).val(UnitNumber);
			//Address.find('.StreetNumber input').attr('disabled', true).val(StreetNumber);
			//Address.find('.StreetName input').attr('disabled', true).val(StreetName);
			Address.find('.AddressLine1 input').attr('disabled', true).val(AddressLine1);
			Address.find('.AddressLine2 input').attr('disabled', true).val(AddressLine2);
			Address.find('.City input').attr('disabled', true).val(City);
			Address.find('.State select').attr('disabled', true).val(State);
			Address.find('.Postcode input').attr('disabled', true).val(Postcode);
			Address.find('.Country input').attr('disabled', true).val(Country);

			return false
		}
		var bindSearchSelect = function(item) {
			var clickHandler = function() {
				var me = $(this).addClass('active');
				var data = me.data('Client');

				me.siblings('.active').removeClass('active');

				rfq_client = data; 

				$('#ContactDetails-RFQ-ClientUserID').val(data.UserID);
				$('#ContactDetails-RFQ-RequesterName').val(data.fullname);
				$('#ContactDetails-RFQ-EmailAddress').val(data.EmailAddress);
				$('#ContactDetails-RFQ-PhoneNumber').val(data.MobilePhoneNumber);

				$('#ContactDetails-mail_addr-AddressID').val(data.mail_address.AddressID);
				//$('#ContactDetails-mail_addr-UnitNumber').val(data.mail_address.UnitNumber);
				//$('#ContactDetails-mail_addr-StreetNumber').val(data.mail_address.StreetNumber);
				//$('#ContactDetails-mail_addr-StreetName').val(data.mail_address.StreetName);
				$('#ContactDetails-mail_addr-AddressLine1').val(data.mail_address.AddressLine1);
				$('#ContactDetails-mail_addr-AddressLine2').val(data.mail_address.AddressLine2);
				$('#ContactDetails-mail_addr-City').val(data.mail_address.City);
				$('#ContactDetails-mail_addr-State').val(data.mail_address.State);
				$('#ContactDetails-mail_addr-Postcode').val(data.mail_address.Postcode);
				$('#ContactDetails-mail_addr-Country').val(data.mail_address.Country);

				if (!$('#home-address').length)
					return false;

				$('#ContactDetails-mail_addr-AddressID').val(data.home_address.AddressID);
				//$('#ContactDetails-home_addr-UnitNumber').val(data.home_addr.UnitNumber);
				//$('#ContactDetails-home_addr-StreetNumber').val(data.home_addr.StreetNumber);
				//$('#ContactDetails-home_addr-StreetName').val(data.home_addr.StreetName);
				$('#ContactDetails-home_addr-AddressLine1').val(data.home_addr.AddressLine1);
				$('#ContactDetails-home_addr-AddressLine2').val(data.home_addr.AddressLine2);
				$('#ContactDetails-home_addr-City').val(data.home_addr.City);
				$('#ContactDetails-home_addr-State').val(data.home_addr.State);
				$('#ContactDetails-home_addr-Postcode').val(data.home_addr.Postcode);
				$('#ContactDetails-home_addr-Country').val(data.home_addr.Country);


				return false;
			}
			item.unbind('click', clickHandler)
				.bind('click', clickHandler);
		}
		var makeSelection = function(items) {
			var getFullName = function(item) {
				return [item.contact.FirstName, item.contact.LastName].join(' ');
			};
			var getBirthPlace = function(item) {
				return [item.contact.BirthCity, item.contact.BirthCountry].join(' ');
			};
			var list = $('#search-results ul.results').html("");

			for(var i in items) {
				var user = items[i].contact;

				user.fullname = getFullName(items[i]);

				var li = "<li class='list-group-item'><a href='#' ><span class='user-fullname'><b>"+ user.fullname +"</b></span>";
				li += '<span class="user-birthplace">Form: <i>'+ getBirthPlace(items[i]) +'</i></span></a>';
				li += '</li>';

				li = $(li).data('Client', user);
				list.append(li);
				bindSearchSelect(li);
			}
		}
		$('#contact-search-cancel').click(function(){
			$('#search-form, #search-results').toggleClass('hidden');
			return false;
		});
		$('#contact-search').click(function() {
			var btn = $(this);
			var data = {
				FirstName: $('#Search-FirstName').val(),
				LastName: $('#Search-LastName').val(),
				EmailAddress: $('#Search-EmailAddress').val(),
				MobilePhoneNumber: $('#Search-MobilePhoneNumber').val(),
			};

			btn.addClass('spin').attr('disabled', true);

			$.ajax({
				url: '{{ route('search', ['FindContactByPersonalDetails', '_token' => csrf_token()]) }}',
				type: 'get',
				dataType: 'json',
				data: data,
				success: function(result) {
					if (result && result.length) {
						makeSelection(result);
						$('#search-form, #search-results').toggleClass('hidden');
					} else {
						alert('No Contact Matched!');
					}
					
					btn.removeClass('spin').attr('disabled', false);
				},
				error: function(xhr) {
					btn.removeClass('spin').attr('disabled', false);
				}
			});
			return false;
		});
		var checkAddressChange = function(type) {
			var hasChange = 0;
			$('#'+ type.replace('_', '-')).find('input, select').each(function() {
				var me = $(this);
				
				if (me.attr('type') !== 'hidden' && me.val() !== me.data('val')) hasChange++;
			});
			return !!hasChange;
		}
		$('#mail-address input, #home-address input, #home-address select, #mail-address select')
		.each(function() {
			var me = $(this);
			me.data('val', me.val())
		})
		.change(function() {
			var me = $(this);
			var addr = me.parents('.address-fields:first');
			var type = addr.data('address') || 'mail_address';

			var field = function() {
				var match = me.attr('name').match(/\[([a-z0-9]+)\]$/i);
				return match ? match[1] : null;
			}
			var f = field();
			var m = rfq_client[type] = rfq_client[type] || {};
			if (f) {
				$('.use-addr .'+f).find('input').val(me.val());
				m[f] = me.val();
			}
			var aid = addr.find('input.AddressID');
			if (checkAddressChange(type)) {
				aid.val("");
				$('.use-addr').find('input.AddressID').val("");	
			}
			else {
				aid.val(aid.data('val'));
				$('.use-addr').find('input.AddressID').val(aid.data('val'))
			}
		});
		$('.u-m-a').change(bindMailAddressUse);
	});
</script>

<script>
	(function($){
		var jsteps = $.fn.jsteps = function(options) {

			if (typeof jsteps[options] === 'function') {
				return jsteps[options].apply(this, Array.prototype.slice.call(arguments, 1));
			}

			var options = $.extend(true, {
				tabs: '',
				panels: '',
				startIndex: 0,
				bindToTabs: true,
				onBeforeChange: function(prev, next) {},
				onChanged: function(step) {}
			}, options);

			return this.each(function () {
				var container = $(this);

				var state = {
					currentIndex : options.startIndex,
					currentStep : null,
					stepCount: 1
				}
				var panels = container.find(options.panels).addClass('hidden');
				var tabs = container.find(options.tabs).each(function(i,e) {
					$(this).data('jindex', i);
				}).removeClass('active');

				state.totalSteps = tabs.length;

				var activeTab = tabs.get(options.startIndex);
				var activePanel = panels.get(options.startIndex);

				$(activeTab).addClass('active');
				$(activePanel).removeClass('hidden');

				tabs.click(function() {
					container.jsteps('__tabClick', [this]);
					return false;
				});

				container.data('jsteps', {
					'state': state,
					'options': options
				});
			});
		}

		jsteps.__tabClick = function(tab) 
		{
			var container = $(this),
				data = container.data('jsteps');

			if (data.options.bindToTabs)
			{
				jsteps.ajaxStep.apply(this, [$(tab).data('jindex') || 0]);
			}
			console.log(1);
			return false;
		}

		jsteps.unbindTabs = function() {
			var container = $(this),
				data = container.data('jsteps');

			data.options.bindToTabs = false;
			container.data('jsteps', data);

		}

		jsteps.bindTabs = function() {
			var container = $(this),
				data = container.data('jsteps');

			data.options.bindToTabs = true;
			container.data('jsteps', data);
		}

		jsteps.next = function() {
			var container = $(this);
			var data = container.data('jsteps');

			var index = data.state.currentIndex + data.state.stepCount;

			if (index >= data.state.totalSteps)
				index = data.state.currentIndex;

			jsteps.step.apply(this, [index]);
		}

		jsteps.prev = function() {
			var container = $(this);
			var data = container.data('jsteps');	

			var index = data.state.currentIndex - data.state.stepCount;

			if (index <= 0)
				index = 0;

			jsteps.step.apply(this, [index]);
	
		}

		jsteps.step = function(index) {
			var container = $(this);
			var data = container.data('jsteps');

			index = index <= 0 ? 0 : (index >= data.state.totalSteps ? data.state.totalSteps - 1: index);	

			if (false === data.options.onBeforeChange.apply(this, [data.state.currentIndex, index])) {
				return this;
			}

			var tabs = container.find(data.options.tabs).removeClass('active');
			var panes = container.find(data.options.panels).addClass('hidden');

			$(tabs.get(index)).addClass('active');
			var active = $(panes.get(index)).removeClass('hidden');

			// location.hash = active.attr('id');

			data.state.currentIndex = index;
			container.data('jsteps', data);
			data.options.onChanged.apply(this, [index]);

			$('html,body').animate({ scrollTop: 0 }, 'slow');		
		}

		jsteps.ajaxStep = function(next, callback) {

			var container = $(this);
			var options = $.extend(true, jsteps.ajaxStep.options, container.data('ajaxStep') || {});
			var data = container.data('jsteps');

			if (data.state.currentIndex < next) {
				for(var i = data.state.currentIndex + data.state.stepCount; i < next; ) {
					if ($.inArray(i, options.validated))
						return;
					i += data.state.stepCount;
				}

				if (options.ajaxing) return;

				options.validating 	= data.state.currentIndex;
				options.step 		= next;
				options.ajaxing		= true;
				options.callback 	= callback || function() {};

				var tab = container.find('.form-tab-contents .rfq-group').get(data.state.currentIndex);
				options.form = $(tab).children('form');

				container.data('ajaxStep', options);

				$.ajax({
					url: options.form.attr('action'),
					data: options.form.serializeArray(),
					type: 'post',
					dataType: 'json',
					cache: false,
					success: function(result) {

						if (options.form) options.form.find('span.help-block').remove();

						var step 		= options.step;

						options.callback.apply(container, [options.step, options.form, result]);						

						options.ajaxing = false;
						options.step 	= null;
						options.form 	= null;
						
						// clear errors first
						container.find('.has-error').removeClass('has-error');
						$('.form-messages').remove();

						if (result.success === true && -1 === $.inArray(options.validating, options.validated)) {
							options.validated.push(step);
						}
						else if (!result.success) {
							ErrorHandler(result);

							if (-1 !== (index = $.inArray(options.validating, options.validated))) {
								options.validated = options.validated.splice(index, 1);
							}
						}	

						container.data('ajaxStep', options);
						if (result.success)
							$(container).jsteps('step', step);	
					},
					error: function(xhr) {
						console.log(xhr.responseText);

						options.ajaxing = false;
						options.step = null;
						options.form = null;

						container.data('ajaxStep', options);
					}
				});	
			}
			else {
				$(container).jsteps('step', next);
			}
		}

		jsteps.ajaxStep.options = {
			ajaxing : false,
			step: null,
			form: null,
			validating: null,
			validated: [],
			callback: function() {}
		};

		jsteps.ajaxStep.container = null;

		window.ErrorHandler = function(result) {
			var getFieldNames = function(items, base, container) {
				container = container || [];

				for(var i in items) {
					var key = base +'['+ i +']';

					if (typeof items[i] === 'object') {

						if ($.isArray(items[i])) {
							var isString = items[i].shift();

							if (typeof isString === 'string') {
								container[key] = isString;	
								continue;
							}
							items[i].unshift(isString);
						}
						getFieldNames(items[i], key, container);
					}
					else {
						if (/(\d+)/.test(i) === false) {
							if (typeof container[key] !== 'array')
								container[key] = [];
							container[key].push(items[i]);
						}
						else container[key] = items[i];
					}
				}
				return container;
			}
			var SearchError = false;
			for(var i in result) {
				var fields = getFieldNames(result[i], i);
				for(var i in fields) {
					var inp = $('[name="'+ i +'"]');
					var msg = $.isArray(fields[i]) ? msg = fields[i][0] : fields[i];
					var parent = null;
					if (!inp.length) {
						var val = i.match(/\[([a-z\-0-9]+)\]$/i);
						
						if (val) {
							var inp = $('#qid-'+val[1]+' .question-disp > label');
						}
					} else {
						parent = $(inp.parents('.form-group:first').get(0));
					}

					if (i.match(/(ClientUserID)/))
						SearchError = true;
					inp.parents('.form-group:first, .question-disp:first').addClass('has-error');
					var p = inp.parents('.Address').addClass('has-error');
					if (!p.find('.u-m-a-label').siblings('.help-block').length)
						$("<span class='help-block' style='position:inherit;'>This question is mandatory.</span>")
							.insertAfter(p.find('.u-m-a-label'));
					if (inp.parents('.SelectMulti').length)
						$("<span class='help-block'>"+ msg +"</span>").insertAfter(inp);
					else if (inp.parents('.Boolean').length) {
						var parent = inp.parents('.Boolean:first');
						var label = parent.find('.question-disp > label');

						$("<span class='help-block'>"+ msg +"</span>")
							.insertAfter(label)
							.css({
								position: 'relative',
    							bottom: 'auto'
							});
					}
				}
			}
			var msg = '<div class="form-messages alert alert-danger grid-75 centered v-space-2x">';
			msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			msg += 'Please fill the highlighted fields correctly.</div>';
						
			$('.form-tab-contents').prepend($(msg));

			if (SearchError)
				alert("Please search for a contact first and select.");
		}
		// Scroll to title
		$(".form-section-title").animate({
            scrollTop: 0
        }, 600);

	})(jQuery);

	$(document).ready(function(){
		var enableButtons = function (step, form, result) {
			if (form) {
				form.find('.btn')
					.removeClass('spin')
					.attr('disabled', false);
			}
		} 
		var submit = function(step, form, result) {
			if (typeof result.success === 'undefined' || !result.success)
				return enableButtons(step, form, result);

			var container = $(this);
			var ajaxOptions = container.data('ajaxStep') || {};

			ajaxOptions.submitting = true;
			container.data('ajaxStep', ajaxOptions);

			var forms = $('.rfq-form form').serializeArray();
			$.ajax({
				url: $('#form-submit form').attr('action'),
				type: 'post',
				dataType: 'json',
				cache: false,
				data: forms,
				success: function(result) {
					
					ajaxOptions.submitting = false;
					container.data('ajaxStep', ajaxOptions);
					enableButtons(step, form, result);

					if (typeof window.RFQFormCallback === 'function')
						return RFQFormCallback.apply(container, [result]);
					else {
						alert('Your RFQRefNum: '+ result.RFQRefNum);
					}
				},
				error: function(xhr) {
					// console.log(xhr.responseText);
					ajaxOptions.submitting = false;
					container.data('ajaxStep', ajaxOptions);
					enableButtons(step, form, result);

					if (typeof window.RFQFormCallback === 'function')
						return RFQFormCallback.apply(container, [{error: '"Ops! Something wen\'t wrong, please try again later."'}]);
					else alert("Ops! Something wen't wrong, please try again later.");
				}
			});
		};

		$('.btn-back').bind('click', function() {
			$('.rfq-form').jsteps('prev');
			return false;
		});
		$('.rfq-group.last form').addClass('last');
		$('.rfq-group form').submit(function(){
			var form  = $(this);
			var steps = $('.rfq-form');
			var data  = steps.data('jsteps');

			form.find('.btn')
				.attr('disabled', true);
			form.find('.btn-submit')
				.addClass('spin');

			callback = form.hasClass('last') ? (window.RFQFormSubmit || submit) : enableButtons;

			steps.jsteps('ajaxStep', data.state.currentIndex + data.state.stepCount, callback);

			return false;
		});
		
		$('.rfq-form').jsteps({
			tabs: '.form-tabs li',
			panels: '.form-tab-contents .rfq-group',
			startIndex: 0,
			onBeforeChange: function(step) {
				var container = $(this);
				var ajaxOptions = container.data('ajaxStep') || {};

				if (ajaxOptions.submitting) {
					return false;
				}
				return true;	
			}
		});
	});
</script>