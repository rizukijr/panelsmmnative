!function($) {
    var MorrisCharts = function() {};

    //creates line chart
    MorrisCharts.prototype.createLineChart = function(element, data, xkey, ykeys, labels, opacity, Pfillcolor, Pstockcolor, lineColors) {
        Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          fillOpacity: opacity,
          pointFillColors: Pfillcolor,
          pointStrokeColors: Pstockcolor,
          behaveLikeLine: true,
          gridLineColor: '#eef0f2',
          hideHover: 'auto',
          resize: true, //defaulted to true
          lineColors: lineColors
        });
    },
    //creates area chart
    MorrisCharts.prototype.createAreaChart = function(element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
        Morris.Area({
            element: element,
            pointSize: 0,
            lineWidth: 0,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            hideHover: 'auto',
            resize: true,
            gridLineColor: '#eef0f2',
            lineColors: lineColors
        });
    },
    //creates area chart with dotted
    MorrisCharts.prototype.createAreaChartDotted = function(element, pointSize, lineWidth, data, xkey, ykeys, labels, Pfillcolor, Pstockcolor, lineColors) {
        Morris.Area({
            element: element,
            pointSize: 3,
            lineWidth: 1,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            hideHover: 'auto',
            pointFillColors: Pfillcolor,
            pointStrokeColors: Pstockcolor,
            resize: true,
            gridLineColor: '#eef0f2',
            lineColors: lineColors
        });
    },
    //creates Bar chart
    MorrisCharts.prototype.createBarChart  = function(element, data, xkey, ykeys, labels, lineColors) {
        Morris.Bar({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            hideHover: 'auto',
            resize: true, //defaulted to true
            gridLineColor: '#eeeeee',
            barColors: lineColors
        });
    },
    //creates Stacked chart
    MorrisCharts.prototype.createStackedChart  = function(element, data, xkey, ykeys, labels, lineColors) {
        Morris.Bar({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            stacked: true,
            labels: labels,
            hideHover: 'auto',
            resize: true, //defaulted to true
            gridLineColor: '#eeeeee',
            barColors: lineColors
        });
    },
    //creates Donut chart
    MorrisCharts.prototype.createDonutChart = function(element, data, colors) {
        Morris.Donut({
            element: element,
            data: data,
            resize: true, //defaulted to true
            colors: colors
        });
    },
    MorrisCharts.prototype.init = function() {
    
 //creating area chart
        var $areaData = [
                { y: '2010', a: 0, b: 0, c:0 },
                { y: '2011', a: 75, b: 65, c:30 },
                { y: '2012', a: 22, b: 32, c:32 },
                { y: '2013', a: 75, b: 65, c:30 },
                { y: '2014', a: 50, b: 40, c:30 },
                { y: '2015', a: 75, b: 65, c:30 },
                { y: '2016', a: 0, b: 0, c:0 }
            ];
        this.createAreaChart('morris-area-example', 0, 0, $areaData, 'y', ['a', 'b','c'], ['Site A', 'Site B','Site C'], ['#16198d', '#51e4ff', '#e7e8ef']);


        //create line chart
        var $data  = [
            { y: '2006', a: 10, b: 0 },
      { y: '2007', a: 20,  b: 12 },
      { y: '2008', a: 50,  b: 40 },
      { y: '2009', a: 75,  b: 65 },
      { y: '2010', a: 50,  b: 40 },
      { y: '2011', a: 80,  b: 70 },
      { y: '2012', a: 100, b: 90 }
          ];
        this.createLineChart('morris-line-example', $data, 'y', ['a', 'b'], ['Series A', 'Series B'],['0.1'],['#ffffff'],['#999999'], ['#6164c1', '#13dafe']);

       
       
        //creating bar chart
        var $barData  = [
            { y: '2009', a: 98, b: 60 , c: 40 },
            { y: '2010', a: 70,  b: 60 , c: 20 },
            { y: '2011', a: 50,  b: 45 , c: 65 },
            { y: '2012', a: 96,  b: 51 , c: 82 },
            { y: '2013', a: 88,  b: 44 , c: 35 },
            { y: '2014', a: 95,  b: 29 , c: 48 },
            { y: '2015', a: 99, b: 72 , c: 36 }
        ];
        this.createBarChart('morris-bar-example', $barData, 'y', ['a', 'b', 'c'], ['Value X', 'Value Y', 'Value Z'], ['#6164c1', '#13dafe', '#f1f2f7']);

        //creating Stacked chart
        var $stckedData  = [
              { y: '2006', a: 100, b: 90 },
        { y: '2007', a: 75,  b: 65 },
        { y: '2008', a: 50,  b: 40 },
        { y: '2009', a: 75,  b: 65 },
        { y: '2010', a: 50,  b: 40 },
        { y: '2011', a: 75,  b: 65 },
        { y: '2012', a: 100, b: 90 }
        ];
        this.createStackedChart('morris-bar-stacked', $stckedData, 'y', ['a', 'b'], ['Series A', 'Series B'], ['#13dafe', '#ebeff2']);
 //creating donut chart
        var $donutData = [
                {label: "Safari View", value: 12},
                {label: "Mozila View", value: 30},
                {label: "Chrome View", value: 20}
            ];
        this.createDonutChart('morris-donut-example', $donutData, ['#6164c1', '#13dafe', '#99d683']);
    },
  
       
    //init
    $.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.MorrisCharts.init();
}(window.jQuery);