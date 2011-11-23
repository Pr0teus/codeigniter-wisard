<?php  

/**
* Wisard
*
* An open source library for implement a WISARD neural Network in PHP
*
* @package		Wisard
* @author		Felipe A. Espósito A.K.A Pr0teus
* @since		Version 1.0
* @filesource
**/

class Wisard {

	var $discriminators;
	
	
	public function Wisard(){
		$this->discriminators = new ArrayObject();
	}
	
	
	/*
	 * Add um discriminator in the Wisard
	* $discriminator instance of Discriminator
	*/
	public function addDiscriminator($discriminator)
	{
		$this->discriminators->append($discriminator);
	}
	/*
	 * Remove um discriminator from the Wisard
	* $discriminator instance of Discriminator
	*/
	public function delDiscriminator($discriminator){
		//$this->discriminators->
	}
	/*
	 * Print all the discriminators in the Wisard
	*/
	public function showDiscriminators(){
		print "There is a total of: ".$this->discriminators->count()." discriminators";
		for($i=0;$i<$this->discriminators->count();$i++){
			print "<br/>".$this->discriminators[$i]->getClassName();
		}
	}
	
	public function show(){
		print_r($this->discriminators);
	}
	
	public function detect($pattern){
		$best_score = 0;
		$second_score =0;
		$winner_class ="";
		
		for($i=0;$i<$this->discriminators->count();$i++){			
			$points = $this->discriminators[$i]->check($pattern);
			 if($points>$best_score){
			 	$second_score = $best_score;
			 	$best_score = $points;
			 	$winner_class = $this->discriminators[$i]->getClassName();
			 }
		}
		
		return "Winner class:".$winner_class." com score :".$best_score;
	}



}

class Discriminator{

	var $class_name = "";
	var $neurons;
	var $neurons_bits;
	var $training = 0;
	var $knowledge;
	

	public function Discriminator($class_name,$neurons,$neurons_bits)
	{
		$this->class_name    = $class_name;
		$this->neurons       = $neurons;
		$this->neurons_bits  = $neurons_bits;
		
		//Set of neurons for each discriminator
		$this->knowledge = new ArrayObject();
		for($i=0;$i<$this->neurons;$i++){
			$this->knowledge[$i] = new RAM($this->neurons_bits);
		}
	}

	public function training($training_set){
		$this->training++;
		for($i=0;$i<$this->knowledge->count();$i++){	
			$this->knowledge[$i]->activate_neurons($training_set[$i]);
		}
	}
	
	public function check($pattern){
		$neurons_activated =0;
		for($i=0;$i<$this->knowledge->count();$i++){
			$neurons_activated = $neurons_activated + $this->knowledge[$i]->check($pattern[$i]);
		}		
		return $neurons_activated;
	}
	
	public function clear(){
		for($i=0;$i<$this->neurons;$i++){
			$this->knowledge[$i]->clear();
		}
	}

	public function getClassName(){
		return $this->class_name;
	}
	
	public function show(){
		print "<br/>Class: ".$this->class_name."<br/>";
		print "<br/>Neurons: ".$this->neurons." with: ".$this->neurons_bits." bits each!<br/>";
		print "<pre>";
		print_r($this->knowledge);
		print "</pre>";
	}

	}

	class RAM {

		var $bits;
		var $ram;

		public function RAM($b){
			$this->bits = $b;
			$this->ram  = new ArrayObject();
			for($i=0;$i<$this->bits;$i++){
				$this->ram[$i]=false;
			}
		}

		public function clear(){
			for($i=0;$i<$this->bits;$i++){
				$this->ram[$i]=false;
			}
		}

		public function neurons_activated(){
			$count = 0;
			for($i=0;$i<$this->ram->count();$i++){
				if($this->ram[$i]==true){
					$count++;
				}
			}
			return $count;
		}
		
		public function check($pattern){
			$count = 0;
			for($i=0;$i< $this->bits;$i++){
				if(($this->ram[$i]==true) && ($pattern[$i]==true)){
					$count++;
				}
			}
			return $count;
		}
		
		public function activate_neurons($pattern){
	
			for($i=0;$i< $this->bits;$i++){
				if(($this->ram[$i]==false) && ($pattern[$i]==true)){
					$this->activate_neuron($i);
				}
			}
		}

		public function activate_neuron($i){
			try
			{
				if($this->ram->offsetExists($i))
				{
					$this->ram[$i]=!$this->ram[$i];
				}				
			}
			catch(Exception $e)
			{
				echo "<h1>Erro</h1>";
				echo $e->getMessage();
			}
		}

		public function show(){
			print  ("<br/>RAM with".$this->bits." bits<br/>");
			print ("<pre>");
			print_r($this->ram);
			print ("</pre>");

		}

	}
	?>