<?php
ini_set('memory_limit', '4095M');  // large input file and large output file needs incremented memory limit than default limit.
set_time_limit(0); // large execution time
/**
 * Problem --
 
   A large group of friends from the town of Nocillis visit the vineyards of Apan to taste wines. The vineyards produce many fine wines and the friends decide to buy as many as 3 bottles of wine each if they are available to purchase. Unfortunately, the vineyards of Apan have a peculiar restriction that they can not sell more than one bottle of the same wine. So the vineyards come up with the following scheme: They ask each person to write down a list of up to 10 wines that they enjoyed and would be happy buying. 
 
 * Input - 
 
   A two-column TSV file with the first column containing the ID (just a string) of a person and the second column the ID of the wine that they like. Example - 

	https://s3.amazonaws.com/br-user/puzzles/person_wine_3.txt 

 * Expected Output -
 
   First line contains the number of wine bottles sold in aggregate with your solution. Each subsequent line should be two columns, tab separated. The first column is an ID of a person and the second column should be the ID of the wine that they will buy.
   
   
 * @package    WineTaste (Wine Allotment Problem)
 * @author     Ankit Garg <ankitgarg90@gmail.com>
 * @version    1
  */
class WineTaste{
	/**
     * Class WineTaste
     */
	
	public	$wines_available;
	public  $wine_with_persons_choice;
	public	$final_list;
    public	$total_wines_sold;
	
	/**
     * Constructor function 
     *
     * Initialization of the variables.
     */
	 
	function __construct(){
	    
		$this->wines_available 		        = [];
		$this->wine_with_persons_choice		= [];
		$this->final_list  					= [];
		$this->total_wines_sold 		    = 0;
	}
    
	/**
     * function getWineList
     *
     * This Function creates the list of available wines in a Shop and choices of persons.   .
     *
     */
	
	public function getWineList($input_file){
	
	     $fp = fopen($input_file,"r");
		 while ( !feof($fp) ){
				$line = fgets($fp, 2048);
			    $data = str_getcsv($line, "\t");  // breaks and get data
				if($data[0]!=''){
					$person = trim($data[0]);
					$wine = trim($data[1]);
					if(!array_key_exists($wine, $this->wine_with_persons_choice)){
						$this->wine_with_persons_choice[$wine] = [];
					}
					$this->wine_with_persons_choice[$wine][] = $person;
					$this->wines_available[]=$wine;
				}
		 } 
		 fclose($fp); 
		 $this->wines_available = array_unique($this->wines_available);
		
	}
	
	
	/**
     * function getResults
     *
     * this function generate the list of wines that is sold to a person.
     *
     */
	public function getResults(){
	    
		foreach ($this->wines_available as $key => $wine){
		     foreach ($this->wine_with_persons_choice[$wine] as $keys => $person){
			      if(!array_key_exists($person, $this->final_list)){
						$this->final_list[$person][] = $wine;
						$this->total_wines_sold++;
						break;
				   }else{
						if(count($this->final_list[$person])<3){
							  $this->final_list[$person][] = $wine;
							  $this->total_wines_sold++;
							  break;
						}
				   }
			 }
		}
    }
	/**
     * function generateResultFile
     *
     * this function creates the tsv file with expected result .
     *
     */
	public function generateResultFile($output_file_name){
	
	   $fh = fopen($output_file_name, "w");
	   $heading="Total wines sold by vineyards is - ".$this->total_wines_sold;
		fwrite($fh, $heading );
		foreach ($this->final_list as $person=>$winelist){
			foreach ($this->final_list[$person] as $key => $wine){
				fwrite($fh, "\n".$person." \t ".$wine);
			}
		}
		fclose($fh);
		echo $heading.'<br /> Result has been generated. File name is - '.$output_file_name;		
	
	}
}
$wine = new WineTaste();
$wine->getWineList("person_wine_3.txt");
$wine->getResults();
$wine->generateResultFile('result_person_wine_3.txt');
?>