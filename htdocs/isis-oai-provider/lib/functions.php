<?php

function range_date($startdate, $enddate) {

	$startdate = strtotime(str_replace("-", "/", $startdate));
	$enddate = strtotime(str_replace("-", "/", $enddate));

	// range de data precisa ser de uma data mais antiga até uma data mais recente
	if($enddate < $startdate) {
		return false;
	}

	$dates = array();
	$current = $startdate;

	// criação do array de datas
	while( true ) {


		$dates[date("Y", $current)][date("m", $current)][] = date("d", $current);
		$current = strtotime( "+1 day", $current );

		if($current > $enddate) break;
	}

	$response = array();
	foreach($dates as $year => $months) {

		$is_complete = true;
		
		// caso seja um ano completo, ele imprime "ano$". Ex: 2011$
		if(count($dates[$year]) == 12) {	
					
			foreach($months as $month => $days) {
				
				if(count($days) < 31 && count($days) < 30) {					
					

					// meses com menos de 28 são sempre incompletos
					if(count($days) < 28) {
						$is_complete = false;

					// se for com 29 e o mês for 2, ele vai testar se é bissexto
					} elseif(count($days) == 29 && $month == 2) {
						
						// se não for bissexto, ele é imcompleto
						if(!checkdate($month, $days[28], $year)) {
							$is_complete = false;
						}
					
					// se for com 28 ou 29 dias fora do mês 2, significa que é imcompleto
					} elseif((count($days) == 28 || count($days) == 29) && $month != 2) {
						$is_complete = false;
					}

				// se tiver 30 dias, mas poder ir até 31, é imcompleto
				} elseif(count($days) == 30) {
					
					if(checkdate($month, 31, $year)) {
						$is_complete = false;
					}
				}
			}
			

			if($is_complete) {
				$response[] = $year . "$";
				
				// pula o resto do loop, pois este ano já foi totalmente incluido
				continue;
			}

			
		} 
			
		$is_complete = true;

		foreach($months as $month => $days) {
			
			if(count($days) < 31 && count($days) < 30) {					
				
				// meses com menos de 28 são sempre incompletos
				if(count($days) < 28) {
					$is_complete = false;

				// se for com 29 e o mês for 2, ele vai testar se é bissexto
				} elseif(count($days) == 29 && $month == 2) {
					
					// se não for bissexto, ele é imcompleto
					if(!checkdate($month, $days[28], $year)) {
						$is_complete = false;
					}
				
				// se for com 28 ou 29 dias fora do mês 2, significa que é imcompleto
				} elseif((count($days) == 28 || count($days) == 29) && $month != 2) {
					$is_complete = false;
				}

			// se tiver 30 dias, mas poder ir até 31, é imcompleto
			} elseif(count($days) == 30) {
				
				if(checkdate($month, 31, $year)) {
					$is_complete = false;
				}
			}		
				
			if($is_complete) {
				// printa todos os meses completos (Ex: 201105$)
				$response[] = $year . $month . "$";

			} else {


				// separa os dias pela sua dezena (0, 1, 2, 3)
				foreach($days as $day) {
					$days_count[$year . $month][substr($day, 0, 1)][] = $day;
				}

				foreach($days_count as $ym => $days) {
					
					// só trata o array desta data
					if($ym != $year . $month) {
						continue;
					}

					foreach($days as $init => $group_day) {

						// se o grupo desta dezena tiver 10, siginifica que é uma dezena completa
						if(count($group_day) == 10) {

							// (Ex: 2011051$)
							$response[] = $year . $month . $init . "$";
						
						} elseif(count($group_day) == 9 && $init == 0) {						
							$response[] = $year . $month . $init . "$";
							
						} elseif(in_array(30, $group_day) && in_array(31, $group_day)) {
							$response[] = $year . $month . $init . "$";
							
						// se não tiver 10, significa que está incompleto
						} else {

							// itera nos dias e printa o dia completo (Ex: 20110501)
							foreach($group_day as $day_finish) {
								
								$response[] = $year . $month . $day_finish;
							}
						}
					}
				}
			}
			$is_complete = true;
		}
	}

	$response[] = str_replace("/", "", date("Ymd",$enddate));

	return $response;
}

?>