<?php

class sanityChecker{
	
	protected $errorHandler;
	protected $fileList;
	
	public function __construct(){
		
		$this->errorHandler = new errorHandler();
		
		
		//load up the files to be checked
		$fileList['/errors/'] = [
			"error.php",
			"errorHandler.php",
			"sanityChecker.php"
		];
		
		$fileList['/settings/'] = [
			"settingsHandler.php"
		];
		
		$fileList['/users/'] = 
			["user.php",
			 "userHandler.php"
			];
		
		$fileList['/web/'] = 
			["webCore.php"
			];
		
		$fileList['/'] = [
			"fileHandler.php",
			"uploadHandler.php"
		];
		
		
		$this->fileList = $fileList;
		
	}
	
	public function check(){
		// false = quiet
		// true = verbose
		
		//file checking
		$okay = true;
		$missing = [];
		
		//var_dump($this->fileList);
		foreach ($this->fileList as $dir => $files){
			
			foreach ($files as $file){
				
				//var_dump($file);
				$location = $GLOBALS['dir']. '/lib' . $dir . $file;
				
				if (file_exists($location)){
					
					
					
				}else{
					
					
					$okay = false;
					
					array_push($missing, $location);
				}
				
					
			}
		}		
		
		
		
	}
	
	
	
}


?>