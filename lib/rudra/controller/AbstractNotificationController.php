<?php
/*
 * @category Lib @package Test Suit @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com) @license GNU
 */
include_once (RUDRA . "/controller/AbstractController.php");

/**
 *
 * @author Lalit Tanwar
 *        
 */
abstract class AbstractNotificationController extends AbstractController {
	
	protected $_db;
	protected $token;

	public function setToken($_token) {
		$this->token = $_token;
	}
	
	/**
	 * public function __construct(AbstractDb $db) { $this->_db = $db; }
	 */
	
	/**
	 *
	 * @param int $recipientUid        	
	 * @return int
	 */
	public function fetchNumberByRecipientUid($tokenId) {
		return $this->_db->fetch(
				"SELECT count(*) as count " .
						 " FROM notification WHERE tokenId = %d AND isNew = 1", 
						$this->token )->count;
	}
	
	/**
	 *
	 * @param int $recipientUid
	 * @return int
	 */
	public function fetchAllNotifications($after) {
		return $this->_db->fetchAll(
				"SELECT * FROM notification WHERE tokenId = %d AND id > %d",
				$this->token,$after);
	}

	/**
	 *
	 * @param int $recipientUid        	
	 * @param int $eventId        	
	 */
	public function add($tokenId, $eventId) {
		$this->_db->update(
				"INSERT INTO " .
						 " notification (`id`, `tokenId`, `eventId`, `isNew`) VALUES (NULL, '%d', '%d', '1')", 
						$this->token, $eventId );
	}
	/**
	 *
	 * @param int $recipientUid
	 * @param int $eventId
	 */
	public function push($eName,$eData) {
		$this->_db->update(
				"INSERT INTO " .
				" notification (`id`, `tokenId`, `eName`, `eData`) VALUES (NULL, '%s', '%s', '%s')",
				$this->token, $eName,json_encode($eData) );
	}

	/**
	 *
	 * @param int $recipientUid        	
	 */
	public function removeAll($tokenId) {
		$this->_db->update("DELETE FROM " . " notification WHERE tokenId = %d", 
				$this->token );
	}

}