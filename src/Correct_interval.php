<?php

namespace Name;

class Correct_interval
{
	public function correct($interval)
    {
    	$error = '';
		$regexp = '/(([0-1]\d|2[0-3])(:[0-5]\d)-([0-1]\d|2[0-3])(:[0-5]\d))$/u';
		if (preg_match($regexp, $interval) === 0) {
			$error = "Ожидается интервал времени в форме ЧЧ:ММ-ЧЧ:ММ";
		}
		return $error;
    }

}