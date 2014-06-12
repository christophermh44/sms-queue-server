<?php
	require_once __DIR__.'/JsonData.php';

	class Auth {
		static $expires = 600; //seconds
		static $cannotAuthenticate = 'Could not authenticate application.';
		static $defaultAlphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		static $jsonFile = 'apps';
		static $tokensFile = 'tokens';
		static $tokenKey = 'token';
		static $validityKey = 'validity';
		static $expiresKey = 'expires';
		static $errorKey = 'error';
		static $keyKey = 'key';
		static $secretKey = 'secret';

		static function proceed($api_key, $api_secret) {
			$auth = JsonData::read(self::$jsonFile);
			$obj = [
				self::$errorKey => self::$cannotAuthenticate
			];
			if (isset($auth[$api_key]) && $auth[$api_key] == $api_secret) {
				$obj = [
					self::$tokenKey => self::generateToken(),
					self::$expiresKey => self::getExpires()
				];
				self::storeToken($api_key, $obj);
			}
			return $obj;
		}

		static function storeToken($api_key, $obj) {
			$tokens = JsonData::read(self::$tokensFile);
			$tokens[$api_key] = $obj;
			JsonData::write(self::$tokensFile, $tokens);
		}

		static function authenticate($api_key, $token, $authSuccess, $authFailed = null) {
			$tokens = JsonData::read(self::$tokensFile);
			$tokens = self::removeOldTokens($tokens);
			$authentication = false;
			if (isset($tokens[$api_key]) && $tokens[$api_key][self::$tokenKey] == $token) {
				$authentication = true;
				unset($tokens[$api_key]);
			}
			JsonData::write(self::$tokensFile, $tokens);
			return $authentication ? $authSuccess() : ($authFailed !== null ? $authFailed() : null);
		}

		static function removeOldTokens($tokens) {
			foreach ($tokens as $key => $token) {
				if ($token[self::$expiresKey] < time()) {
					unset($tokens[$key]);
				}
			}
			return $tokens;
		}

		static function generateToken($compareTo = [], $len = 32, $alphabet = null) {
			if ($alphabet === null) {
				$alphabet = self::$defaultAlphabet;
			}
			$alphabetLen = strlen($alphabet);
			$hash = '';
			do {
				$hash = '';
				for ($i=0; $i < $len; $i++) {
					$charIndex = rand(0, $alphabetLen - 1);
					$hash.= substr($alphabet, $charIndex, 1);
				}
			} while (array_search($hash, $compareTo) !== false);
			return $hash;
		}

		static function getExpires() {
			return time() + self::$expires;
		}

		static function createApp() {
			$auth = JsonData::read(self::$jsonFile);
			$key = self::generateToken(array_keys($auth));
			$secret = self::generateToken([], 64);
			$auth[$key] = $secret;
			JsonData::write(self::$jsonFile, $auth);
			return [
				self::$keyKey => $key,
				self::$secretKey => $secret
			];
		}

		static function removeApp($key) {
			$auth = JsonData::read(self::$jsonFile);
			unset($auth[$key]);
			JsonData::write(self::$jsonFile, $auth);
		}
	}
?>
