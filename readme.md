Yii-Highcharts
=====

What is Yii-Highcharts?
--------------
Yii-Highcharts is an extension to include charts, OOP-style, PHP, in your YII applications. With Batik exporting !

Disclaimer:
This plugin was originally created by Milo Schuman and is available @ https://code.google.com/p/yii-highcharts/

However, due to numerous attempts to contact the author for fixes, and the fact that the project seems staled (a bug
 dated on Mar-2012 with no answer whatsoever), we decided to fork and release the plugin with our modifications.


What we're planning to support:
- Charts
- Exports as Yii native controller actions.


Current Version 0.2 (2013-03-26 07:56)
--------------
Added support for gradient filling your charts.

For this to work, you have to add an entry 'gradient' in the 'options' array of the widget declaration:

```php

$this->Widget('ext.highcharts.HighchartsWidget', array(
	'options' => array(
		'gradient' => array('enabled' => true),
		[...]
	)
	[...]
)

```


Version 0.1 (2012-12-21 13:00)
--------------
First Commit !

This version includes the raw, functional extension used in an other project. 
Further updates will bring the project to a more cleaner look.

It is basically a modified version of the existing yii extension that can be found here:
http://www.yiiframework.com/extension/highcharts/

After an attempt to contact the original developer (without reponse), I forked the project.

What this version does more:
- Exporting via Apache batik as a yii native controller action.


The project comes with a fully-loaded "assets" folder. it contains the required files to
use the extension. Please note that the released versions will only support the included versions of highcharts
and batik. Should you upgrade one of the files for whatever reason is at your discretion.