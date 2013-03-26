<?php

/**
 * HighchartsWidget class file.
 * 
 * Examples and documentation is provided in the example.md file. 
 * A wiki may be available in a near future.
 * 
 * Original work by Milo Schuman. Due to Inactivity on his googlecode project
 * (and failed attempts to contact him), we forked the project to add new 
 * features.
 *
 * Original Author : Milo Schuman
 * Original project link : http://yii-highcharts.googlecode.com/
 * 
 * @author Milo Schuman <miloschuman@gmail.com>
 * @author Philippe Desmarais <https://github.com/UltraPhil>
 * @author Pier-Luc Faucher <https://github.com/razwiss>
 * @link https://github.com/UltraPhil/Yii-Highcharts
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 0.2
 */

class HighchartsWidget extends CWidget
{

    public $options = array();
    public $htmlOptions = array();

    /**
     * Makes the widget an action provider by overriding actions().
     * Adds the "Export" action to allow server-side rendering of png/pdf/svg/jpeg/etc..
     *
     * @return array actions
     */
    public static function actions()
    {
	return array(
	    'Export' => 'application.extensions.highcharts.actions.actionExport'
	);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
	$id = $this->getId();
	$this->htmlOptions['id'] = $id;

	echo CHtml::openTag('div', $this->htmlOptions);
	echo CHtml::closeTag('div');

	// check if options parameter is a json string
	if (is_string($this->options))
	{
	    if (!$this->options = CJSON::decode($this->options))
		throw new CException(Yii::t('HighChart', 'The options parameter is not valid JSON.'));
	}

	// merge options with default values
	$defaultOptions = array('chart' => array('renderTo' => $id), 'exporting' => array('enabled' => true));
	$this->options = CMap::mergeArray($defaultOptions, $this->options);
	$jsOptions = CJavaScript::encode($this->options);

	$this->registerScripts(__CLASS__ . '#' . $id, "var chart = new Highcharts.Chart($jsOptions);");
    }

    /**
     * Publishes and registers the necessary script files.
     *
     * @param string the id of the script to be inserted into the page
     * @param string the embedded script to be inserted into the page
     */
    protected function registerScripts($id, $embeddedScript)
    {
	$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
	$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
	$scriptFile = YII_DEBUG ? '/highcharts.src.js' : '/highcharts.js';

	$cs = Yii::app()->clientScript;
	$cs->registerCoreScript('jquery');
	$cs->registerScriptFile($baseUrl . $scriptFile);

	// register exporting module if enabled via the 'exporting' option
	if ($this->options['exporting']['enabled'])
	{
	    $scriptFile = YII_DEBUG ? 'exporting.src.js' : 'exporting.js';
	    $cs->registerScriptFile("$baseUrl/modules/$scriptFile");
	}
        
        if ( isset($this->options['gradient']) && $this->options['gradient']['enabled']){
            $scriptFile = "gradientFill.js";
            $cs->registerScriptFile("$baseUrl/$scriptFile");
        }
        
	# $cs->registerScript($id, $embeddedScript);
	// register global theme if specified vie the 'theme' option
	if (isset($this->options['theme']))
	{
	    $scriptFile = $this->options['theme'] . ".js";
	    $cs->registerScriptFile("$baseUrl/themes/$scriptFile");
	}
	$cs->registerScript($id, $embeddedScript, CClientScript::POS_LOAD);
    }

}