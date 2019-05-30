  @inject("ClientHelper", "App\Helpers\ClientHelper")

  <script type="text/javascript">
  
  @if(!empty($Client))
  	var recommendations = {!! json_encode($ClientHelper->getRecommendedPolicies($Client['ClientID'], false)) !!};
  @else
  	var recommendations = [];
  @endif

  @if(count($user_policies) > 0)
	var user_policies = {!! json_encode((array)$user_policies) !!}
  @else
	var user_policies = [];
  @endif
  
  	var form_url = '{{ route('quotes.form', ['POLICYTYPEID', 'FORMTYPEID']) }}';
  
  	$(document).ready(function(){
		$('a.policies').click(function() {
			var PolicyTypeID = $(this).data('policy') || null;
			var policy = user_policies[PolicyTypeID] || {};
			
			if (policy) {
				$('#compare-modal').modal('show');
			}
			else if (policy.is_requested) {
				$('#acknowledgement-modal').modal('show');
			}
			return false;
		});
	});

  	var randomColors = [
			'#3c8dbc', '#00c0ef', '#f39c11', '#00a65a', '#dd1717', '#4cda3e', '#460000', '#cec937',
			'#f2a314', '#9b784e', '#fdb02c', '#ca6b60', '#a58784', '#5b6daf', '#354ea9', '#4b772f',
			'#405830'
		];
  	var colorSets = {!! json_encode(config('colors')) !!};
  	var data1 = [['Major', 'Degrees']], data2 = [['Major', 'Degrees']], colors = [], slices = {}, refs = {};
  	var nums = [];
  	var getColor = function(SetID) {
  		var rand = function() {
  			var num = Math.ceil(Math.random() * randomColors.length - 1);
  			if ($.inArray(num, nums) !== -1) 
  				return rand();
  			return num;
  		}
  		if (SetID && (set = colorSets[SetID])) return set;
  		return randomColors[rand()];
  	};

  	function drawWheel() {
		
		var checkPolicy = function(recommendation) {
			var policy = recommendation.PolicyType || {};
			console.log(policy);
			if (policy.active) {
				$('#acknowledgement-modal').modal('show');
			}
			else if (policy.PolicyTypeID && (policy.RFQFormTypeID || policy.FormTypeID)) {
				location.href = form_url.replace('POLICYTYPEID', policy.PolicyTypeID)
										.replace('FORMTYPEID', policy.FormTypeID || policy.RFQFormTypeID);
			}
		}

	  	for(var i = 0; i < recommendations.length; i++) {
	  		// if (typeof policies[recommendations[i].PolicyTypeID] === 'undefined'
	  		// 	|| !policies[recommendations[i].PolicyTypeID] )
	  		// 	continue;
	  		
	  		recommendations[i].active = typeof user_policies[recommendations[i].PolicyTypeID] !== 'undefined';
	  		refs[recommendations[i].PolicyType.DisplayText] = recommendations[i];
	  		refs.count = refs.count ? refs.count + 1 : 1;
	  	}	

	  	var sliceWidth = Math.ceil(100 / refs.count - 1);
	  	var labels = {};
	  	
	  	for(var i in refs) {
	  		if (i === 'count') continue;
	  		var label = refs[i].PolicyType.DisplayText.substring(0, 10);
	  		labels[ label ] = refs[i].PolicyType.DisplayText; 
	  		data1.push([
	  			label,
	  			refs[i].PolicyType.Name == 'WorkersComp' ? 0 : sliceWidth
	  		]);
	  		data2.push([
	  			label,
	  			refs[i].PolicyType.Name == 'WorkersComp' ? 100 : 0
	  		]);
	  		colors.push(refs[i].active ? getColor(refs[i].PolicyTypeID) : '#CFD0D2');
	  	}

	  	var cdata1 = google.visualization.arrayToDataTable(data1);
		var cdata2 = google.visualization.arrayToDataTable(data2);

		    var options = { 
		    	pieSliceText: 'label',
		    	width: 450,
		    	height: 500,
		    	legend: 'none',
		    	chartArea: {
		    		width: '95%',
		    		height: '99%'
		    	},
		    	tooltip: { trigger: 'none'},
		    	diff: {
		    		innerCircle: { 
		    			radiusFactor: 0.4,
		    			pieSliceText: 'label' 
		    		} 
		    	},
				colors: colors
		    };

		    var chartDiff = new google.visualization.PieChart(document.getElementById('piechart_diff'));

		    var diffData = chartDiff.computeDiff(cdata2, cdata1);
		    var selectHandler = function() {
		    	var selected = chartDiff.getSelection()[0];
		    	if (selected) {
		    		var value = cdata1.getValue(selected.row, selected.column || 0);
		    		var label = labels[value] || value;
		    		var policy = refs[label] || {};

		    		checkPolicy(policy);
		    	}
		    };
		    var errorRendering = function() {
		    	alert("Ops! we're unable to generate wheel please try refreshing the page.");
		    };
		    var adjustLabel = function() {
		        // Note: You will probably need to tweak these deltas
		        // for your labels to position nicely.
		        var xDelta = 35;
		        var yDelta = 13;
		        var appendBreakLine = function(chunks) {
		        	var ret = [];
		        	for(var i=0; i <= chunks.length ;i++) {
		        		ret.push(chunks[i]);
		        		if (i && i % 2 === 0) 
		        			ret.push("<br />");
		        	}
		        	return ret;
		        }
		       	$('svg > g:first').bind('mouseover mousemove', function(e) { e.stopPropagation(); });
		        $('[text-anchor]').each(function(i, el) {
		        	var text = $(this);
		        	var label = labels[ text.text().trim() ];
		        	
		        	if (label) {
		        		var g = text.parent();
		        		g.find('foreignObject').remove();
		        		var fo = document.createElementNS("http://www.w3.org/2000/svg", "foreignObject");
		        		var x = parseInt(el.getAttribute('x'));
		    			var y = parseInt(el.getAttribute('y'));

		    			var wDelta = Math.ceil((text.width() - 70) / 2);
		    			var yDelta = 12 * Math.ceil(label.length / 12);
		    			
		    			if (!label.match(/Workers\sCompensation/) && data1.length <= 3)
		    				x = 30;
		    			else if (x > 195)
		    				x = x - 15;
		    			if (y < 160)
		    				y = y - 10;
		    			else if (y > 160 && y < 250)
		    				y -= yDelta / 2;
		    			else y -= yDelta;
		    			
		        		fo.setAttribute('x', x);
		            	fo.setAttribute('y', y);
		        		
		        		text1 = document.createElement("P");
		        		text1.innerText = label;

		        		text1.setAttribute('text-anchor', "start");
			        	text1.setAttribute('x', x);
		                text1.setAttribute('y', y);
		                text1.setAttribute('font-family', "Arial");
			        	text1.setAttribute('font-size', 12);
			        	text1.setAttribute('stroke', "none");
			        	text1.setAttribute('stroke-width', "0");
			        	text1.setAttribute('fill', "#ffffff");
			        	text1.setAttribute('style', 'font-size:11px;display:block;width:70px;')
			        	fo.appendChild(text1);
			        	g.append(fo);
			        	text.addClass('hidden');
		        	}
		        });
		        // addCenterLabel();
		    };
		    var addCenterLabel = function() {

		    	$('ellipse').each(function(i, el) {
		    		var fo = document.createElementNS("http://www.w3.org/2000/svg", "foreignObject");
		    		var width = el.getAttribute('cx') || 100;
		    		var height = el.getAttribute('cy') || 35;
		        	var text1 = document.createElement("P");
		        	
		        	fo.setAttribute('x', 168);
		        	fo.setAttribute('y', 255.2);

		        	text1.innerHTML = 'Workers Compensation';

		        	text1.setAttribute('text-anchor', "start");
		        	text1.setAttribute('x', 150);
	                text1.setAttribute('y', 230.55);
	                text1.setAttribute('font-family', "Arial");
		        	text1.setAttribute('font-size', 13);
		        	text1.setAttribute('stroke', "none");
		        	text1.setAttribute('stroke-width', "0");
		        	text1.setAttribute('fill', "#ffffff");

	                fo.appendChild(text1);
	                el.parentNode.appendChild(fo);
		        });
		    }
		    google.visualization.events.addListener(chartDiff, 'select', selectHandler);
		    google.visualization.events.addListener(chartDiff, 'select', adjustLabel);
	 		google.visualization.events.addListener(chartDiff, 'error', errorRendering);
	 		google.visualization.events.addListener(chartDiff, 'ready', adjustLabel);
	 		google.visualization.events.addListener(chartDiff, 'onmouseover', adjustLabel);
	 		google.visualization.events.addListener(chartDiff, 'onmouseout', adjustLabel);
		    chartDiff.draw(diffData, options);

		    // google.visualization.events.trigger(chartDiff, 'onmouseover');
	}

	if (!recommendations.length) {
		$('#wheel-container').html("<h5 class='text-center error'>You don't have recommended policies as of now.</h5>");
	}
	else {
		google.charts.load('current', {packages:['corechart']});
		google.charts.setOnLoadCallback(drawWheel);	
	}
</script> 