<?php 

class jasper2php
{
	
	public $jasperfile;
	public $outputpath;
	public $user;
	public $libpath;
	public $filename;
	
	private $parameters;
	
	const TYPE_STRING = 'String';
	const TYPE_DATE = 'Date';
	const TYPE_NUMBER = 'Number';
	
	function __construct()
	{
		$this->parameters = array();
	}
	
	/*
	 * Author: Jesse Rovira
	 * Description: The function calls, based on Shell Command, the java JVM, running the jasperRPT program, send him
	 * some parameters, there are:
	 * 1. the lib path, where the config.properties could be found;
	 * 2. the jasperfile, the report itself;
	 * 3. the output path, where te report will be generated;
	 * 4. the name of the user, or any other user information you want to pass! Important, this name can't contain spaces!!!
	 * 5. the filename, if want to specify one. Otherwise, you can tell "auto" to filename;
	 * 6. the parameters of the report, whose values will be passed with the syntax 'parameter_name#parameter_value'
	 * The output comes with this hashtag syntax, where out of the tag, will be the report content, that is, the PDF content you can 
	 * display to your user! Otherwise, it'll get you an "error" string. Needs to get an strong outside information (ToDo list);
	 */
	function runReport()
	{
		$strparam = "";
		foreach ($this->parameters as $param)
		{
			list ($pname, $pvalue) = split("[#]", $param);
			$strparam .= " ".$pname . "#" . $pvalue;
		}
		$output = shell_exec("java -jar ".$this->libpath."jasperRPT.jar ".$this->libpath." ".$this->jasperfile." ".$this->outputpath." ".$this->user." ".$this->filename.$strparam);
		$pos = stripos($output, "#reportname#");
		if($pos>=0)
		{
			return substr($output, $pos+12);
		}
		else
		{
			return "error";
		}
	}
	
	/*
	 * Author: Jesse Rovira
	 * Description: The function give the user conditions to establish their parameters to be send to report.
	 * $name: the name of the parameter, EXACTLY EQUAL inside the report;
	 * $value: the value of the parameter; See, Date is not a specific parameter or type, because it's like an string to Java Parameter. You'll need to treat as Date inside your report!
	 * $type: some constant type to distinguish, more specifically, the String parameter, once strings needs to be pass inside ""
	 * This is a void function. 
	 */
	function addParameter($name, $value, $type)
	{
		if($type==self::TYPE_STRING)
		{
			$this->parameters[sizeof($this->parameters)]=$name.'#"'.$value.'"';
		}
		else
		{
			$this->parameters[sizeof($this->parameters)]=$name.'#'.$value;
		}
	}
	
}
?>