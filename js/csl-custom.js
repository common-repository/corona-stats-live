( function ( $ ) {
    "use strict";

    $(document).ready(function(){

		$('.do-donut').each(function() {
			var values = $(this).html();
			var config = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: values.split(","),
						backgroundColor: [
							"rgba(255,0,0,1)",
							window.chartColors.orange,
							window.chartColors.yellow,
							window.chartColors.green,
							window.chartColors.blue,
						],
						label: 'Dataset 1'
					}],
					labels: [
						'Total Cases',
						'Active Cases',
						'Recovered',
						'Deaths'
					]
				},
				options: {
					responsive: true
				}
			};
			var ctx = document.getElementById('donut-canvas');
			var ctx = document.getElementById('donut-canvas').getContext('2d');		
			var ctx = $("#donut-canvas");

			var donutGraph = new Chart(ctx, config);
		});	

	    $('.do-chart').each(function(){
	    	var div = $(this);
	    	var canvasID = div.attr('canvas-id');
	    	var chartType = div.attr('chart-type');
	    	var dataCount = div.attr('data-count');
	    	var stats1 = div.find('span').html();
	    	var stats2 = div.find('span').next('span').html();
	    	var stats3 = div.find('span').next('span').next('span').html();
	    	var labelss = div.find('span').next('span').next('span').next('span').html();


			var chartData = {
				labels: labelss.split(","),
				borderDashOffset: 0.0,
				fill: false,
				spanGaps: true,

				datasets: [{
					type: chartType,
					label: 'Total Cases',
					fill: false,
					backgroundColor: "rgba(230,38,35,1)",
					data: stats1.split(','),
					borderColor: "rgba(255,0,0,1)",
					borderWidth: 2
				}, {
					type: chartType,
					label: 'New Cases',
					fill: false,
					backgroundColor: "rgba(0,136,204,1)",
					data: stats2.split(',')
				}, {
					type: chartType,
					label: 'Recovered',
					fill: false,
					backgroundColor: "rgba(71,164,73,1)",
					data: stats3.split(',')
				},
				]

			};

			var ctx = document.getElementById(canvasID);
			var ctx = document.getElementById(canvasID).getContext('2d');

			var barGraph = new Chart(ctx, {
				 type: 'bar',
				 data: chartData
			});				

	    });


	});    
} ( jQuery ) );