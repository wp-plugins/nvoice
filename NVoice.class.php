<?php

class NVoice_Ivona {

	/**
	 * Konstruktor
	 *
	 * @param string $email
	 * @param string $haslo
	 * @param string $url
	 * @param integer $voice	 
	 */

	function __construct($email, $haslo, $url, $voice) {

		$this->client = new SoapClient($url, array('trace' => true, 'exceptions' => true));

		$this->glos = $voice;
		$this->email = $email;
		$this->haslo = $haslo;

	}

	function PobierzToken() {

		$input = array('email' => $this->email);
		$token = $this->client->__soapCall('getToken', $input); // pobierz token.
		$md5 = md5(md5($this->haslo).$token);

		return array ('token' => $token, 'md5' => $md5);

	}

	/**
	 * Dodanie nowego nagrania.
	 *
	 * @param string $nazwa
	 * @param string $tresc
	 *
	 * @return string
	 */

	function DodajNagranie($nazwa, $tresc) {

			$data = $this->PobierzToken();

			$input = array (

				'token' => $data['token'],
				'md5' => $data['md5'],
				'name' => $nazwa,
				'text' => $tresc,
				'voiceId' => $this->glos,

			);


		$licencja = $this->client->__soapCall('addUtterance', $input);

		$plik = $this->ZakupLicencje($licencja);

		return $plik['url']; // zwraca ścieżkę do pliku audio


	}

	/**
	 * Zakup licencje
	 *
	 * @param integer $id
	 *
	 * @return array
	 */


	function ZakupLicencje($id) {


			$data = $this->PobierzToken();


			$LicencjaInput = array (

				'token' => $data['token'],
				'md5' => $data['md5'],
				'utteranceId' => $id,
				'downloadType' => 'many',
				'encoder_name' => 'mp3/22050',
				'license' => 'free',
				'unlimitedClicks' => 'yes',

			);
				

		return $this->client->__soapCall('buyLicense', $LicencjaInput);

	}



	/**
	 * Pobierz wszystkie pliki audio.
	 *
	 * @return array
	 */


	function PokazPliki() {


			$data = $this->PobierzToken();


			$input = array (

				'token' => $data['token'],
				'md5' => $data['md5'],

			);
				

		return $this->client->__soapCall('listUtterances', $input);

	}


	/**
	 * Usuń plik.
	 *
	 * @param integer $id
	 */


	function UsunPlik($id) {


			$data = $this->PobierzToken();


			$input = array (

				'token' => $data['token'],
				'md5' => $data['md5'],
				'utteranceId' => $id,

			);

		return $this->client->__soapCall('deleteUtterance', $input);

	}


	/**
	 * Usuwa wszystkie pliki audio hostowane. [po co śmiecić?]
	 */


	function UsunWszystkiePliki() {

		foreach ($this->PokazPliki() as $p) {

			$this->UsunPlik($p->utteranceId);

		}

	}




}
?>