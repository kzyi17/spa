<?php
namespace Index\Controller;
/**
 * Simple excel generating from PHP5
 *
 * @package Utilities
 * @license http://www.opensource.org/licenses/mit-license.php
 * @author Oliver Schwarz <oliver.schwarz@gmail.com>
 * @version 1.0
 */

/**
 * Generating excel documents on-the-fly from PHP5
 * 
 * Uses the excel XML-specification to generate a native
 * XML document, readable/processable by excel.
 * 
 * @package Utilities
 * @subpackage Excel
 * @author Oliver Schwarz <oliver.schwarz@vaicon.de>
 * @version 1.1
 * 
 * @todo Issue #4: Internet Explorer 7 does not work well with the given header
 * @todo Add option to give out first line as header (bold text)
 * @todo Add option to give out last line as footer (bold text)
 * @todo Add option to write to file
 */
class Excel_XML
{

	/**
	 * Header (of document)
	 * @var string
	 */
        private $header = "<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

        /**
         * Footer (of document)
         * @var string
         */
        private $footer = "</Workbook>";

        /**
         * Lines to output in the excel document
         * @var array
         */
        private $lines = array();

        /**
         * Used encoding
         * @var string
         */
        private $sEncoding;
        private $coumllength;
        /**
         * Convert variable types
         * @var boolean
         */
        private $bConvertTypes;
        /**
         * Worksheet title
         * @var string
         */
        private $sWorksheetTitle;

        /**
         * Constructor
         * 
         * The constructor allows the setting of some additional
         * parameters so that the library may be configured to
         * one's needs.
         * 
         * On converting types:
         * When set to true, the library tries to identify the type of
         * the variable value and set the field specification for Excel
         * accordingly. Be careful with article numbers or postcodes
         * starting with a '0' (zero)!
         * 
         * @param string $sEncoding Encoding to be used (defaults to UTF-8)
         * @param boolean $bConvertTypes Convert variables to field specification
         * @param string $sWorksheetTitle Title for the worksheet
         */
        public function __construct($sEncoding = 'UTF-8', $bConvertTypes = false, $sWorksheetTitle = 'Table1')
        {
                $this->bConvertTypes = $bConvertTypes;
        	$this->setEncoding($sEncoding);
        	$this->setWorksheetTitle($sWorksheetTitle);
        }
        
        /**
         * Set encoding
         * @param string Encoding type to set
         */
        public function setEncoding($sEncoding)
        {
        	$this->sEncoding = $sEncoding;
        }

        /**
         * Set worksheet title
         * 
         * Strips out not allowed characters and trims the
         * title to a maximum length of 31.
         * 
         * @param string $title Title for worksheet
         */
        public function setWorksheetTitle ($title)
        {
                $title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);
                $title = substr ($title, 0, 31);
                $this->sWorksheetTitle = $title;
        }

        /**
         * Add row
         * 
         * Adds a single row to the document. If set to true, self::bConvertTypes
         * checks the type of variable and returns the specific field settings
         * for the cell.
         * 
         * @param array $array One-dimensional array with row content
         */
        private function addRow ($array,$height='30')
        {
        	$cells = "";
                foreach ($array as $k => $v):
                        $type = 'String';
                        if ($this->bConvertTypes === true && is_numeric($v)):
                                $type = 'Number';
                        endif;
                        $v = htmlentities($v, ENT_COMPAT, $this->sEncoding);
                        $cells .= "<Cell><Data ss:StyleID=\"s7\" ss:Type=\"$type\">" . $v . "</Data></Cell>\n"; 
                endforeach;
				
				
                $this->lines[] = "<Row ss:Height=\"$height\">\n" . $cells . "</Row>\n";
        }

        /**
         * Add an array to the document
         * @param array 2-dimensional array
         */
        public function addArray ($array,$widtharray=array(),$height='30')
        {
				$this->datalength = count($array[0]);
				$this->widtharray = $widtharray;
				$this->height = $height;
                foreach ($array as $k => $v){
						$height = $height ? $height : $this->height;
                       $this->addRow ($v,$height);
				}
        }


        /**
         * Generate the excel file
         * @param string $filename Name of excel file to generate (...xls)
         */
        public function generateXML ($filename = 'excel-export')
        {
			
                // correct/validate filename
                //$filename = preg_replace('/[^aA-zZ0-9\_\-]/', '', $filename);
    	
                // deliver header (as recommended in php manual)
                header("Content-Type: application/vnd.ms-excel; charset=" . $this->sEncoding);
				header("Content-Disposition: attachment; filename=\"" . $filename . ".xls\""); //作为附件方式
                //header("Content-Disposition: inline; filename=\"" . $filename . ".xls\"");//在线打开方式

                // print out document to the browser
                // need to use stripslashes for the damn ">"
                echo stripslashes (sprintf($this->header, $this->sEncoding));
				
                echo "\n<Worksheet ss:Name=\"" . $this->sWorksheetTitle . "\">\n<Table>\n";
				for ($key=0;$key<$this->datalength;$key++){
						$style = $this->widtharray;
						$width = $style[$key]['width'] ? $style[$key]['width'] : '100';
						if($key<2){
							echo '<Column ss:Index="'.($key+1).'" ss:StyleID="s6" ss:AutoFitWidth="0" ss:Width="'.$width.'" />';
						}else{
							echo '<Column ss:StyleID="s6" ss:AutoFitWidth="0" ss:Width="'.$width.'"/>';
						}
				}
                foreach ($this->lines as $line){
                        echo $line;
				}
                echo "</Table>\n</Worksheet>\n";
                echo $this->footer;
        }
		
		function export_csv(&$data, $title_arr, $file_name = '') {

			 ini_set("max_execution_time", "3600");
			$csv_data = '';

			/** 标题 */

			$nums = count($title_arr);

			for ($i = 0; $i < $nums - 1; ++$i) {

			$csv_data .= '"' . $title_arr[$i] . '",';

			}

			if ($nums > 0) {

			$csv_data .= '"' . $title_arr[$nums - 1] . "\"\r\n";

			}

			foreach ($data as $k => $row) {

			for ($i = 0; $i < $nums - 1; ++$i) {

			$row[$i] = str_replace("\"", "\"\"", $row[$i]);

			$csv_data .= '"' . $row[$i] . '",';

			}

			$csv_data .= '"' . $row[$nums - 1] . "\"\r\n";

			unset($data[$k]);

			}
			print_r($csv_data);die();
			$csv_data = mb_convert_encoding($csv_data, "cp936", "UTF-8");

			$file_name = empty($file_name) ? date('Y-m-d-H-i-s', time()) : $file_name;

			if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) { // 解决IE浏览器输出中文名乱码的bug

			$file_name = urlencode($file_name);

			$file_name = str_replace('+', '%20', $file_name);

			}

			$file_name = $file_name . '.csv';

			header("Content-type:text/csv;");

			header("Content-Disposition:attachment;filename=" . $file_name);

			header('Cache-Control:must-revalidate,post-check=0,pre-check=0');

			header('Expires:0');

			header('Pragma:public');

			echo $csv_data;
		}

}

?>