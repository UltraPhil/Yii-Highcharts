<?php

/**
 * HighChartsExport formModel
 * Keeps data sent by the HighCharts export function.
 */
class HighchartExport extends CFormModel
{

    public $type = "";
    public $svg = "";
    public $filename = "";
    public $width = 800;
    public $ext = "";

    public function rules()
    {
	return array(
	    // Type and SVG data are required
	    array('type, svg', 'required'),
	    // width needs to be an integer
	    array('width', 'numerical', 'integerOnly' => true),
	    // filename and type need to be strings
	    array('filename, type', 'type', 'type' => 'string'),
	);
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
	return array(
	    'type' => 'File extension',
	    'svg' => 'SVG data',
	    'filename' => 'File name',
	    'width' => 'Width'
	);
    }

    public function beforeValidate()
    {
	if (get_magic_quotes_gpc())
	    $this->svg = stripslashes($this->svg);

	if (count($this->filename) == 0)
	    $this->filename = "chart";

	if (!in_array($this->type, array('image/png', 'application/pdf', 'image/svg+xml', 'image/jpeg')))
	    $this->type = 'image/jpeg';

	return parent::beforeValidate();
    }

    public function afterValidate()
    {
	parent::afterValidate();

	$this->filename = str_replace(' ', '_', $this->filename);

	// Chops data
	$typeSplitted = explode('/', $this->type);
	$this->ext = $typeSplitted[1];
	if ($this->ext == 'jpeg')
	    $this->ext = 'jpg';
    }

}
