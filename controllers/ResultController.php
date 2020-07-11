<?php


class ResultController {
	
	
	private $_stockName;
	private $_startDate;
	private $_endDate;
	private $_csvPath;
	private $_csvArray;
	
	public function __construct(){
	
	}
	
	
	public function setInputs($request){
		$this->_stockName = $request->stock_name;
		$this->_startDate = $request->start_date;
		$this->_endDate = $request->end_date;
	}
	
	public function storeCSV($array){
			
		$path = 'storage/csv/';
		$fileName = 'stock_file_'.strtotime("now").'.csv';
		$fullPath = $path.$fileName;
		
		$file = fopen($fullPath,"w");
		foreach ($array as $line)
		{
		  fputcsv($file, $line);
		}
		fclose($file);
		
		$this->_csvPath = $fullPath;
		$this->_csvArray = $array;
	}
	
	
	public function filterByStockName(){
		
		
		$i = 0;
		$manufacturer_key = null;
		$needles = array($this->_stockName);
		$results = array();
		$columns = array();
		
		$path = $this->_csvPath;
		
		if(($handle = fopen($path, 'r')) !== false) {
		    while(($data = fgetcsv($handle, 4096, ',')) !== false) {
		        if($i == 0)  {
		            // sets the key where column to search
		            $columns = $data;
		            $i++; $manufacturer_key = array_search('stock_name', $data);
		        } else {
					
		            foreach($needles as $needle) {
		                if(stripos($data[$manufacturer_key], $needle) !== false) {
		                    $results[] = $data;
		                }
		            }
		        }
		    }
		    fclose($handle);
		}

		array_unshift($results, $columns);
		
		
		$result = $this->filterDateRange($results);
		
		if(!empty($result))
		{
			$res = $result;
			$emptyResult = 	0;
		}
		else
		{
			$res = $results;
			$emptyResult = 	1;
		}
		
		$this->_csvArray = $res;
		
		return [
			'result' => $res,
			'emptyResult' => $emptyResult
		];
		
	}
	
	
	private function filterDateRange($array){
		
		$startDate= strtotime($this->_startDate);
		$endDate = strtotime($this->_endDate);
		
		$key = $this->getArrayKey('date');
				
		$startDate = array_filter($array, function ($val) use ($startDate,$key) {
		    return  strtotime($val[$key]) > $startDate;
		});
		
		$filteredArray = array_filter($startDate, function ($val) use ($endDate,$key) {
		    return  strtotime($val[$key]) < $endDate;
		});
		
		return $filteredArray;
		
	}
	
	public function getArrayKey($key){
		
		$i = 0;
		$keyVal = null;
			
		$path = $this->_csvPath;
		
		if(($handle = fopen($path, 'r')) !== false) {
		    while(($data = fgetcsv($handle, 4096, ',')) !== false) {
		        if($i == 0)  {
		            $i++; $keyVal = array_search($key, $data);
		        }
		    }
		    fclose($handle);
		}
		
		return $keyVal;
	}
	
	public function calcValues(){
		
		$price = $this->getArrayKey('price');
		$aValues = [];
		
		foreach ($this->_csvArray as $key => $value)
		{
			if($key != 0)
			{
				$aValues[$key] = $value[$price];
			}
		}
		
		$stdDev= $this->meanStdDev($aValues);
		$lossProfit = $this->lossProfit($aValues);
		
		return [
			'maximumDate' => $lossProfit['maximumDate'],
			'minimumDate' => $lossProfit['minimumDate'],
			'mean' => $stdDev['mean'],
			'stdDev' => $stdDev['stdDev']
		];
	}
	
	public function meanStdDev($aValues){
		
		$fMean = array_sum($aValues) / count($aValues);
		
		$fVariance = 0.0;
		
		foreach ($aValues as $i)
		{
			$fVariance += pow($i - $fMean, 2);

		}
		$size = count($aValues) - 1;
		
		$stdDev =  (float) sqrt($fVariance)/sqrt($size);
	
		return [
			'mean' => $fMean,
			'stdDev' => $stdDev
		];
	}
	
	
	public function lossProfit($aValues){
		
		$min = min($aValues);
		$max = max($aValues);
		
		$minKey = array_search($min,$aValues);
		$maxKey = array_search($max,$aValues);
		
		$date = $this->getArrayKey('date');
		
		$minimumDate = ($this->_csvArray[$minKey][$date]);
		$maximumDate = ($this->_csvArray[$maxKey][$date]);
		
		return [
			'maximumDate' => $maximumDate,
			'minimumDate' => $minimumDate
		];
		
	}
	
	
}
