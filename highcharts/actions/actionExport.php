<?php

/**
 * This class exports an Highcharts' charts.
 * Original file by Highslide Software (was Yii-adapted).
 *
 * Batik Java SVG toolkits homepage: http://xmlgraphics.apache.org/batik/
 *
 * Original header:
 * ****************************************************************************
 * This file is part of the exporting module for Highcharts JS.
 * www.highcharts.com/license
 *
 *
 * Available POST variables:
 *
 * $tempName string The desired filename without extension
 * $type string The MIME type for export.
 * $width int The pixel width of the exported raster image. The height is
 * calculated.
 * $svg string The SVG source code to convert.
 * ****************************************************************************
 */
class actionExport extends CAction
{

    /**
     * @var string Path to batik executable (jar).  Setted by the constructor.
     */
    private $batik_path;

    /**
     * @var string Complete path to temp directory.  Includes trailing slash.
     */
    private $tempFile_path;

    /**
     * @var string Temporary name to export data.  Setted by the constructor
     */
    private $tempFile_name;

    /**
     * @var HighchartExport A model that contains POST data.  Validates input data.
     */
    private $model;

    public function __construct($controller, $id)
    {
	parent::__construct($controller, $id);
	Yii::import('application.extensions.highcharts.models.HighchartExport');

	$this->batik_path = Yii::getPathOfAlias('application.extensions.highcharts.assets') . '/batik/batik-rasterizer.jar';
	$this->tempFile_path = Yii::app()->getBasePath() . '/runtime/highcharts';
	$this->tempFile_name = md5(rand());

	if (!is_dir($this->tempFile_path))
	    if (!mkdir($this->tempFile_path))
		throw new Exception("Could not create temp directory.");
    }

    /**
     * Outputs SVG data with corresponding headers to client.
     *
     * @param string $svgData SVG data to be sent.
     */
    private function OutputSVG()
    {
	$filename = $this->model->filename;

	header("Content-Disposition: attachment; filename=$filename.svg");
	header("Content-Type: " . $this->model->type);
	echo $this->model->svg;

	Yii::app()->end();
    }

    /**
     * Writes SVG data to a temporary file.
     * @throws Exception permissions are invalid.
     */
    private function WriteSVGTempFile()
    {
	if (!file_put_contents("$this->tempFile_path/$this->tempFile_name.svg", $this->model->svg))
	    throw new Exception("Couldn't create temporary file. Check that the directory permissions for the temp. directory are set to 777.");
    }

    /**
     * Deletes the temporary SVG file and corresponding exported file.
     */
    private function DeleteSVGTempFile()
    {
	unlink("$this->tempFile_path/$this->tempFile_name.svg");
	unlink("$this->tempFile_path/$this->tempFile_name." . $this->model->ext);
    }

    /**
     * Exports the SVG data with Batik
     */
    private function ExportSVG()
    {
	$outputFile = "$this->tempFile_path/$this->tempFile_name." . $this->model->ext;

        //This creates a string that can be read  by shell_exec to launch batik (a java executable .jar file)
	$command = "java -jar $this->batik_path" . " -m " . $this->model->type .
                " -d $outputFile" . " -w " . $this->model->width . " $this->tempFile_path/$this->tempFile_name.svg";


	$output = shell_exec($command);

	if (!is_file($outputFile) || filesize($outputFile) < 10)
	    throw new Exception("Error while converting SVG.  Command: $command  Output: $output");
    }

    /**
     * Outputs the exported SVG (pdf, jpeg, png, etc) to the client.
     */
    private function OutputExportedSVG()
    {
	$outputFile = "$this->tempFile_path/$this->tempFile_name." . $this->model->ext;
	$realFileName = $this->model->filename . '.' . $this->model->ext;

	header("Content-Disposition: attachment; filename=$realFileName");
	header("Content-Type: " . $this->model->type);
	echo file_get_contents($outputFile);
    }

    /**
     * Run the exporting action.
     * Will first attempt to create a temporary file, which
     * will then be read to create the real file.
     * 
     * The temp file is deleted afterwards.
     * 
     * @throws Exception If the data is invalid.
     */
    public function run()
    {
	$this->model = new HighchartExport;
	$this->model->attributes = $_POST;

	if (!$this->model->validate())
	    throw new Exception("invalid data:");

	if ($this->model->ext == 'svg+xml')
	    $this->OutputSVG($this->model->svg);

	$this->WriteSVGTempFile($this->model->svg);
	$this->ExportSVG();
	$this->OutputExportedSVG();
	$this->DeleteSVGTempFile();
    }

}

?>
