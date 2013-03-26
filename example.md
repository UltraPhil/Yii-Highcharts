Yii-Highcharts Examples
=====

This file contain examples and explanation on how to use the Extension.

If you are familiar with HighCharts, most (if not all) of the time you can replace HighCharts' js syntax with an array-oriented 
PHP Syntax. If you are not, I highly suggest you dive into 2-3 examples on HighCharts website. This 'Documentation'
is not a thourough, nor complete documentation of all the possibilities of HighCharts. It is only a plugin designed
to use HighCharts in a Yii-Fashioned way, with working batik support.

Below is a complete, working pie chart:

```php

    $this->Widget('ext.highcharts.HighchartsWidget', array(
	'options' => array(
            'gradient' => array('enabled'=> true),
	    'credits' => array('enabled' => false),
	    'exporting' => array('enabled' => false),
	    'chart' => array(
		'plotBackgroundColor' => null,
		'plotBorderWidth' => null,
		'plotShadow' => false,
		'height' => 400,
		'width' => 750,
	    ),
	    'legend' => array(
		'enabled' => false,
	    ),
	    'title' => array(
		'text' => 'Title of the chart'
	    ),
	    'tooltip' => array(
		'pointFormat' => '{series.name}: <b>{point.percentage}%</b>',
		'percentageDecimals' => 1,
	    ),
	    'plotOptions' => array(
		'pie' => array(
		    'allowPointSelect' => true,
		    'cursor' => 'pointer',
		    'dataLabels' => array(
			'enabled' => true,
			'color' => '#000000',
			'connectorColor' => '#000000',
			'formatter' => "js:function(){return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';}",
		    ),
		)
	    ),
	    'series' => array(
		array(
		    'type' => 'pie',
		    'name' => '% d\'utilisation',
		    'data' => array(array('label1', 63.51), array('label2', 35.14), array('label3', 1.35)),
		)
	    ),
	    )));
?>

```

