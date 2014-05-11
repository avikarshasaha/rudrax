<?php
/*
 * @category Lib @package Test Suit @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com) @license GNU
 */
include_once (RUDRA . "/AbstractController.php");
/**
 * 
 * @author Lalit Tanwar
 *
 */
class AbstractNotificationController extends AbstractController {
	private $_db;
	public function __construct(AbstractDb $db) {
		$this->_db = $db;
	}
	/**
	 *
	 * @param int $recipientUid        	
	 * @return int
	 */
	public function fetchNumberByRecipientUid($recipientUid) {
		return $this->_db->fetch ( "SELECT count(*) as count " . " FROM notification WHERE recipientUid = %d AND isNew = 1", $recipientUid )->count;
	}
	/**
	 *
	 * @param int $recipientUid        	
	 * @param int $eventId        	
	 */
	public function add($recipientUid, $eventId) {
		$this->_db->update ( "INSERT INTO " . " notification (`id`, `recipientUid`, `eventId`, `isNew`) VALUES (NULL, '%d', '%d', '1')", $recipientUid, $eventId );
	}
	/**
	 *
	 * @param int $recipientUid        	
	 */
	public function removeAll($recipientUid) {
		$this->_db->update ( "DELETE FROM " . " notification WHERE recipientUid = %d", $recipientUid );
	}
}