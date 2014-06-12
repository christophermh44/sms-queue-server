<?php
	require_once __DIR__.'/JsonData.php';
	class Sms {
		private $receiverNumber;
		private $message;
		private $date;

		static $queueFile = 'queue';

		function Sms($receiverNumber, $message, $date) {
			$this->receiverNumber = $receiverNumber;
			$this->message = $message;
			$this->date = $date;
		}

		static function fromJson($serial) {
			$smsObject = json_decode($serial, true);
			$receiverNumber = $smsObject['receiverNumber'];
			$message = $smsObject['message'];
			$date = $smsObject['date'];
			$newSms = new Sms($receiverNumber, $message, $date);
			return $newSms;
		}

		function setReceiverNumber($receiverNumber) {
			$this->receiverNumber = $receiverNumber;
		}

		function setMessage($message) {
			$this->message = $message;
		}

		function setDate($date) {
			$this->date = $date;
		}

		function getReceiverNumber() {
			return $this->receiverNumber;
		}

		function getMessage() {
			return $this->message;
		}

		function getDate() {
			return $this->date;
		}

		function preserializeJson() {
			return [
				'receiverNumber' => $this->receiverNumber,
				'message' => $this->message,
				'date' => $this->date
			];
		}

		static function queueSms(Sms $sms) {
			$queue = JsonData::read(self::$queueFile);
			$queue[] = $sms->preserializeJson();
			JsonData::write(self::$queueFile, $queue);
		}

		static function retrieveAllSms() {
			$queue = JsonData::read(self::$queueFile);
			JsonData::write(self::$queueFile, []);
			return $queue;
		}
	}
?>
