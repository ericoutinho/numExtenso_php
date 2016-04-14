<?php

	/**
		* Classe para escrever valores por extenso
		* @author Eric Coutinho <ericoutinho@gmail.com>
		* @package numextenso.class.php
		* @version 0.1
		* @copyright GPL © 2015
		* @param $post = Número a ser escrito
		* @param $moeda = FALSE como default; TRUE para exibir a moeda
	**/ 


	class NumExtenso{

		public $unidade 		= array('','um','dois','três','quatro','cinco','seis','sete','oito','nove');
		public $dezenaEspec		= array('11'=>'onze','12'=>'doze','13'=>'treze','14'=>'catorze','15'=>'quinze','16'=>'dezesseis','17'=>'dezessete','18'=>'dezoito','19'=>'dezenove');
		public $dezena 			= array('','dez','vinte','trinta','quarenta','cinquenta','sessenta','setenta','oitenta','noventa');
		public $centena 		= array('','cem','duzentos','trezentos','quatrocentos','quinhentos','seiscentos','setecentos','oitocentos','novecentos');
		public $milhar 			= array('','mil, ','milhão, ','bilhão, ','trilhão, ', 'quadrlhão, ');
		public $milharp			= array('','mil, ','milhões, ','bilhões, ','trilhões, ', 'quadrilhões, ');
		public $extenso;


		public function __construct($post,$moeda=NULL){

			# converter a string em numero -> 1.000,00
			$valor 		= number_format($post,2,',','.');
			$partes 	= explode(',', $valor);
			$inteiro 	= explode('.', $partes[0]);
			$decimal 	= $partes[1];

			for($i = sizeof($inteiro), $v = 0 ; $i >= 0, $v <= sizeof($inteiro)-1 ; $i--, $v++){

				$ml = ($inteiro[$v] > 1) ? $this->milharp[$i -1] : $this->milhar[$i -1];
				$this->extenso .= $this->centenas($inteiro[$v]) . " " . $ml . "";

			}

			# reais
			$this->extenso .= ($moeda == TRUE) ? "reais" : "";

			# centavos
			$comp = ($moeda == TRUE) ? " centavos" : " décimos";
			$this->extenso .= ($decimal > 0) ? " e " . $this->centavos($decimal) . $comp : "";

		}


		public function centavos($cents){

			if($cents <= 0){
				return FALSE;
			}elseif($cents > 10 && $cents < 20){
				return $this->dezenaEspec[$cents{0}];
			} else {
				return $this->dezena[$cents{0}] . " e " . $this->unidade[$cents{1}];
			}

		}


		public function centenas($centenas){
			
			$size = strlen($centenas);

			# unidades
			if( $size == 1) {
				return $this->unidade[$centenas];

			#dezenas
			}elseif ($size == 2) {

				# onze, doze, treze, etc
				if($centenas > 10 && $centenas < 20) {
					return $this->dezenaEspec[$centenas];

				# demais dezenas
				} else {
					return $this->dezena[$centenas{0}] . " e " . $this->unidade[$centenas{1}];
				}
			
			#centenas
			} elseif ($size == 3) {

				# dezenas da centena
				# converter string em interger
				$dz = intval(substr($centenas,-2));
				# centena = cento ou cem
				$ct = ($centenas{0} == 1 && $dz > 0) ? "cento" : $this->centena[$centenas{0}];

				# final da centena 00
				if ($dz == 0){
					return $this->centena[$centenas{0}];

				# final da centena de 1 a 9
				} elseif($dz > 0 && $dz < 10) {
					return $ct . " e " . $this->unidade[$dz];

				# final da centena 11 a 19
				} elseif ($dz > 10 && $dz < 20){
					return $ct . " e " . $this->dezenaEspec[$dz];

				# todo o resto
				} else {
					return $ct . " e " . $this->dezena[$centenas{1}] . " e " . $this->unidade[$centenas{2}];
				}
			}

		}


	}


?>