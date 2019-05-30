<script>
	var layers = {}, colors = {};
	var randomColors = [
		'#3c8dbc', '#00c0ef', '#f39c11', '#00a65a', '#dd1717', '#4cda3e', '#460000', '#cec937',
		'#f2a314', '#9b784e', '#fdb02c', '#ca6b60', '#a58784', '#5b6daf', '#354ea9', '#4b772f',
		'#405830', '#ffefc6', '#0109c2', '#531442', '#3e07c9', '#d758d4', '#7138c2', '#407784'
	];
	var colorGenerator = function() {
		return '#' + (0x1000000 + Math.random() * 0xFFFFFF).toString(16).substr(1,6);
	}
	var colorSets = {!! json_encode(config('colors')) !!};
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
  		var coreData = [['Major', 'Degrees']], secondData= [['Major', 'Degrees']], thirdData = [['Major', 'Degrees']];

  		$('#wheel > div').html("");

		layers.core = {count: 0};
		layers.second = {count: 0};
		layers.third = {count: 0};
		colors.core = [];
		colors.second = [];
		colors.third = [];

		for(var i=0; i < recommended.length;i++) {
			var label = recommended[i].PolicyType.DisplayText.substring(0, 10);
			var layer = recommended[i].PolicyType.Name == 'WorkersComp' ?
							'core' : 'second';
							//'core' : (!i || i % 2 !== 0 ? 'second' : 'third');

			layers[layer][label] = recommended[i];

			if (layer === 'core') 
				coreData.push([label, 100]);
			else if (layer === 'second')
				secondData.push([label, 10]);
			else 
				thirdData.push([label, 10]);
			colors[layer].push(recommended[i].active ? getColor(recommended[i].PolicyTypeID) : '#CFD0D2');
			layers[layer].count++;
		}
		
	     var adjustLabel = function(layer) {
		        var Layer = layers[layer] || {};

		        $('#'+layer+'layer [text-anchor]').each(function(i, el) {
		        	var text = $(this);
		        	var _layer = Layer[text.text().trim()] || {};
		        	var policy = _layer.PolicyType || {};
		        	var label = policy.DisplayText;
		        	var isSingle = Layer.count == 1;

		        	if (label) {
		        		var g = text.parent();
		        		g.find('foreignObject').remove();
		        		var fo = document.createElementNS("http://www.w3.org/2000/svg", "foreignObject");
		        		var x = parseInt(el.getAttribute('x'));
		    			var y = parseInt(el.getAttribute('y'));

		    			var wDelta = Math.ceil((text.width() - 70) / 2);
		    			var yDelta = 12 * Math.ceil(label.length / 12);

		    			if (policy.Name === 'WorkersComp')
		    				x -= 10;
		    			else if (isSingle) x = 30;
		    			// x = x;
		    			if (y < 250) {
		    				y -= 10;
		    				if (policy.Name !== 'WorkersComp') x -= 10;
		    			}
		    			else if (y > 250 && x > 300) {
		    				x -= 10;
		    				y -= yDelta;
		    			}
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
			        	text1.setAttribute('style', 'font-size:11px;display:block;width:70px;color:#000')
			        	fo.appendChild(text1);
			        	g.append(fo);
			        	text.addClass('hidden');
		        	}
		        });
		        // addCenterLabel();
		    };
	
			coreData = google.visualization.arrayToDataTable(coreData);
			secondData = google.visualization.arrayToDataTable(secondData);
			//thirdData = google.visualization.arrayToDataTable(thirdData);

		    var options = { 
		    	pieSliceText: 'label',
		    	legend: 'none',
		    	chartArea: {
		    		width: '95%',
		    		height: '95%'
		    	},
		    	backgroundColor: 'transparent',
		    	tooltip: { trigger: 'none'}
		    };

		    var coreOptions = $.extend({}, options, {
		    	width: 200,
		    	height: 200,
		    	colors: colors.core,
		    });

		    var secondOptions = $.extend({}, options, {
		    	width: 450,
		    	height: 450,
		    	colors: colors.second/*,
		    	pieHole: 0.45*/
		    });

			/*var thirdOptions = $.extend({}, options, {
		    	width: 750,
		    	height: 750,
		    	colors: colors.third/*,
		    	pieHole: 0.6
		    });	 */ 

		    var coreChart = new google.visualization.PieChart(document.getElementById('corelayer'));
		    var secondChart = new google.visualization.PieChart(document.getElementById('secondlayer'));
		    //var thirdChart = new google.visualization.PieChart(document.getElementById('thirdlayer'));

		    var selectHandlerCore = function() {

		    }
		    var selectHandlerSecond = function() {

		    }
		    var selectHandlerThird = function() {

		    }
		    var adjustLabelCore = function() {
		    	adjustLabel('core');
		    }
		    var adjustLabelSecond = function() {
		    	adjustLabel('second');
		    }
		    var adjustLabelThird = function() {
		    	adjustLabel('third');	
		    }

		    google.visualization.events.addListener(coreChart, 'select', selectHandlerCore);
		    google.visualization.events.addListener(coreChart, 'select', adjustLabelCore);
	 		// google.visualization.events.addListener(coreChart, 'error', errorRendering);
	 		google.visualization.events.addListener(coreChart, 'ready', adjustLabelCore);
	 		google.visualization.events.addListener(coreChart, 'onmouseover', adjustLabelCore);
	 		google.visualization.events.addListener(coreChart, 'onmouseout', adjustLabelCore);

	 		google.visualization.events.addListener(secondChart, 'select', selectHandlerCore);
		    google.visualization.events.addListener(secondChart, 'select', adjustLabelSecond);
	 		// google.visualization.events.addListener(secondChart, 'error', errorRendering);
	 		google.visualization.events.addListener(secondChart, 'ready', adjustLabelSecond);
	 		google.visualization.events.addListener(secondChart, 'onmouseover', adjustLabelSecond);
	 		google.visualization.events.addListener(secondChart, 'onmouseout', adjustLabelSecond);

	 		/*google.visualization.events.addListener(thirdChart, 'select', selectHandlerCore);
		    google.visualization.events.addListener(thirdChart, 'select', adjustLabelThird);
	 		// google.visualization.events.addListener(thirdChart, 'error', errorRendering);
	 		google.visualization.events.addListener(thirdChart, 'ready', adjustLabelThird);
	 		google.visualization.events.addListener(thirdChart, 'onmouseover', adjustLabelThird);
	 		google.visualization.events.addListener(thirdChart, 'onmouseout', adjustLabelThird);*/
	 		
	 		var rows = /*thirdData.getNumberOfRows() + */secondData.getNumberOfRows() + coreData.getNumberOfRows();

	 		if (!rows) {
	 			$('#wheel .empty').removeClass('hidden');
	 		}
	 		else $('#wheel .empty').addClass('hidden');

	 		/*if (thirdData.getNumberOfRows())
		    	thirdChart.draw(thirdData, thirdOptions);*/
		    if (secondData.getNumberOfRows())
		    	secondChart.draw(secondData, secondOptions);
		    if (coreData.getNumberOfRows())
		    	coreChart.draw(coreData, coreOptions);

		    $(document).ready(function() {
		    	$('svg > g:first').bind('mouseover mousemove', function(e) { e.stopPropagation(); });
		    })
		
	}

	google.charts.load('current', {packages:['corechart']});
  google.charts.setOnLoadCallback(drawWheel);

  window.drawWheel = drawWheel;
</script>
