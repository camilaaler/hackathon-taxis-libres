<?php

class ffCiphers extends ffBasicObject {
	
	public function getAuthKey() {
		$key = 'sample-coding-key';
		if( defined('AUTH_KEY') ) {
			$key = AUTH_KEY;
		}
		
		return $key;
	}

    public function getNonceKey() {
        $key = 'sample-nonce-key';
        if( defined('NONCE_KEY') ) {
            $key = NONCE_KEY;
        }

        return $key;
    }

    public function generateRandomString( $length ) {
        $authKey = $this->getAuthKey();
        $nonceKey = $this->getNonceKey();
        $ourBasicModificator = '01sdsdad234567asd89asdfghbsdasdcdefasdshijklmnopqrasdxsadyzfdfdAdsdsLretMNOPhgfdQRSTUxaasdddsgsVWXYsapokijuhytgd';

        $modificator = base64_encode( $authKey . $ourBasicModificator . $nonceKey );

        return substr(str_shuffle( $modificator ), 0, $length);
    }

	private function _getKeyChar( $position, $key ) {
		$lengthOfKey = strlen( $key );
		if( $position >= $lengthOfKey ) {
			$position = $position % $lengthOfKey;
//			$position = $position - 1;
		}

		return $key[ $position ];
	}

	public function ffAdvancedCipher_encode( $value, $key = null ) {
		if( $key == null ) {
			$key = $this->getAuthKey();
		}

		$encodedString = '';

		$length = strlen( $value );
		for( $i = 0; $i < $length; $i++ ) {
			$letter = ord($value[ $i ]);
			$keyLetter = ord($this->_getKeyChar( $i, $key ));

			$newLetter= $letter + $keyLetter;


			if( strlen( $newLetter ) == 2 ) {
				$newLetter = '0' . $newLetter;
			}

			$encodedString .= $newLetter;
		}


		return $encodedString;
	}

	public function ffAdvancedCipher_decode( $value, $key = null ) {
		if( $key == null ) {
			$key = $this->getAuthKey();
		}

		$lettersEncoded = str_split( $value, 3 );

		$decodedString = '';
		foreach( $lettersEncoded as $letterPosition => $oneLetter ) {
			$keyValue = ord($this->_getKeyChar( $letterPosition, $key ));

			$charValue = chr($oneLetter - $keyValue);

			$decodedString .= $charValue;
		}

		return $decodedString;

	}

	public function freshfaceCipher_encode( $value, $key = null) {

		if( $key == null ) {
			$key = $this->getAuthKey();
		}

		//var_dump( $key );
		$keyNumber = 0;
		foreach( str_split($key) as $oneLetter ) {
			$keyNumber += ord($oneLetter);
		}
		
		$encodedValue = array();
		foreach( str_split( $value ) as $oneLetter ) {
			
			$oneLetterValue = ord($oneLetter);
			$oneLetterValueNew = $oneLetterValue + $keyNumber;

			$encodedValue[] = $oneLetterValueNew;
		}
		
		return implode( ',',$encodedValue);
	}
	
	public function freshfaceCipher_decode( $value, $key = null ) {
		
		if( $key == null ) {
			$key = $this->getAuthKey();
		}
		
		$keyNumber = 0;
		foreach( str_split($key) as $oneLetter ) {
			$keyNumber += ord($oneLetter);
		}
		
		$decodedValue = array();
		$splitedValue = explode(',', $value);
		
		foreach( $splitedValue as $oneLetterEncoded ) {
			$oneLetterDecoded = $oneLetterEncoded - $keyNumber;
			$decodedValue[] = chr( $oneLetterDecoded );
		}
		return implode('', $decodedValue );
	}
}