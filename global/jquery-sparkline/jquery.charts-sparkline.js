  
$( document ).ready(function() {
    
    var DrawSparkline = function() {
        $('#sparkline1').sparkline([15, 23, 55, 35, 54, 45, 66, 47, 30], {
            type: 'line',
            width: $('#sparkline1').width(),
            height: '160',
            chartRangeMax: 50,
            lineColor: '#13dafe',
            fillColor: 'rgba(19, 218, 254, 0.3)',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });
    
        $('#sparkline1').sparkline([0, 13, 10, 14, 15, 10, 18, 20, 0], {
            type: 'line',
            width: $('#sparkline1').width(),
            height: '160',
            chartRangeMax: 40,
            lineColor: '#6164c1',
            fillColor: 'rgba(97, 100, 193, 0.3)',
            composite: true,
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });
    
        $('#sparkline2').sparkline([5, 6, 2, 9, 4, 7, 10, 12], {
            type: 'bar',
            height: '160',
            barWidth: '7',
            barSpacing: '5',
            barColor: '#f96262'
        });
		 $('#sparkline12').sparkline([2, 7, 10, 9, 4, 7, 10, 12, 6, 10], {
            type: 'bar',
            height: '160',
            barWidth: '7',
            barSpacing: '5',
            barColor: '#f96262'
        });
		
		$('#sparkline8').sparkline([5, 6, 2, 8, 9, 4, 7, 10, 11, 12, 10], {
            type: 'bar',
            height: '45',
            barWidth: '7',
            barSpacing: '4',
            barColor: '#99d683'
        });
		
		$('#sparkline11').sparkline([5, 6, 2, 8, 9, 4, 7, 10, 11, 12, 10], {
            type: 'bar',
            height: '45',
            barWidth: '7',
            barSpacing: '4',
            barColor: '#13dafe'
        });
        
        $('#sparkline3').sparkline([20, 40, 30, 10], {
            type: 'pie',
            width: '160',
            height: '160',
            sliceColors: ['#13dafe', '#6164c1', '#4c5667', '#f6f6f6']
        });
		
		$('#sparkline9').sparkline([20, 40, 30], {
            type: 'pie',
            width: '50',
            height: '45',
            sliceColors: ['#13dafe', '#6164c1', '#f1f2f7']
        });
    
        $('#sparkline4').sparkline([0, 50, 40, 30, 40, 42, 50, 35, 40], {
            type: 'line',
            width:'100%',
            height: '165',
            chartRangeMax: 50,
            lineColor: '#fff',
            fillColor: 'transparent',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)'
        });
		
		
    
        $('#sparkline4').sparkline([0, 10, 28, 14, 25, 26, 30, 28, 12], {
            type: 'line',
            width: '100%',
            height: '165',
            chartRangeMax: 40,
            lineColor: '#13dafe',
            fillColor: 'transparent',
            composite: true,
            highlightLineColor: 'rgba(255,255,255,0.3)',
            highlightSpotColor: '#13dafe'
        });
    	$('#sparkline10').sparkline([0, 23, 43, 35, 44, 45, 56, 37, 40, 45, 56, 7, 10], {
            type: 'line',
            width: '120',
            height: '45',
            chartRangeMax: 50,
            lineColor: '#fb6d9d',
            fillColor: 'transparent',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)'
        });
        $('#sparkline6').sparkline([1, 5, 6, 8, 7, 5, 9, 10, 11, 12, 4, 10, 8, 5, 13, 10], {
            type: 'bar',
            height: '165',
            barWidth: '10',
            barSpacing: '3',
            barColor: '#c3edb4'
        });
    
        $('#sparkline6').sparkline([1, 5, 6, 8, 7, 5, 9, 10, 11, 12, 4, 10, 8, 5, 13, 10], {
            type: 'line',
            width: '100%',
            height: '165',
            lineColor: '#4c5667',
            fillColor: 'transparent',
            composite: true,
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)'
        });
        
        
    },
        DrawMouseSpeed = function () {
            var mrefreshinterval = 500; // update display every 500ms
            var lastmousex=-1; 
            var lastmousey=-1;
            var lastmousetime;
            var mousetravel = 0;
            var mpoints = [];
            var mpoints_max = 30;
            $('html').mousemove(function(e) {
                var mousex = e.pageX;
                var mousey = e.pageY;
                if (lastmousex > -1) {
                    mousetravel += Math.max( Math.abs(mousex-lastmousex), Math.abs(mousey-lastmousey) );
                }
                lastmousex = mousex;
                lastmousey = mousey;
            });
            var mdraw = function() {
                var md = new Date();
                var timenow = md.getTime();
                if (lastmousetime && lastmousetime!=timenow) {
                    var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
                    mpoints.push(pps);
                    if (mpoints.length > mpoints_max)
                        mpoints.splice(0,1);
                    mousetravel = 0;
                    $('#sparkline5').sparkline(mpoints, {
                        tooltipSuffix: ' pixels per second',
                        type: 'line',
                        width:'100%',
                        height: '165',
                        chartRangeMax: 50,
                        lineColor: '#6164c1',
                        fillColor: 'rgba(97, 100, 193, 0.8)',
                        highlightLineColor: 'rgba(24,147,126,.1)',
                        highlightSpotColor: 'rgba(24,147,126,.2)',
                    });
                }
                lastmousetime = timenow;
                setTimeout(mdraw, mrefreshinterval);
            }
            // We could use setInterval instead, but I prefer to do it this way
            setTimeout(mdraw, mrefreshinterval); 
        };
    
    DrawSparkline();
    DrawMouseSpeed();
    
    var resizeChart;

    $(window).resize(function(e) {
        clearTimeout(resizeChart);
        resizeChart = setTimeout(function() {
            DrawSparkline();
            DrawMouseSpeed();
        }, 300);
    });
});