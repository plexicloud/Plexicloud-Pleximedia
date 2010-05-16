<?php
/*
 * jomtube Comments abstract base class
 * 
 * Author: Matt Beckman - Infinovation, LLC
 * Website: http://www.infinovation.com
 * 
 * This abstraction layer is used by the jomtube plugin system.
 *  
 */
abstract class BaseJVComments {
	
	// private members
	private $m_id;
	private $m_videoID;
	private $m_userID;
	private $m_date;
	private $m_notify;
	private $m_title;
	private $m_comment;
	private $m_published;
	private $m_parentID;
	
	// abstract members
	
	// public members
	public function __construct($id = -1, $videoID = -1, $userID = -1
	, $date = "", $notify = -1, $title = "", $comment = "", $published = -1
	, $parentID = -1)
	{
		$this->setID($id);
		$this->setVideoID($videoID);
		$this->setUserID($userID);
		$this->setDate($date);
		$this->setNotify($date);
		$this->setTitle($title);
		$this->setComment($comment);
		$this->setPublished($published);
		$this->setParentID($parentID);
	}
	
	public function getID() {
		return $this->m_id;
	}
	
	public function setID($id) {
		$this->m_id = $id;
	}
	
	public function getVideoID() {
		return $this->m_videoID;
	}
	
	public function setVideoID($videoID) {
		$this->m_videoID = $videoID;
	}
	
	public function getUserID() {
		return $this->m_userID;
	}
	
	public function setUserID($userID) {
		$this->m_userID = $userID;
	}
	
	public function getNotify() {
		return $this->m_notify;
	}
	
	public function setNotify($notify) {
		$this->m_notify = $notify;
	}
	
	public function getDate($date) {
		return $this->m_date;
	}
	
	public function setDate($date) {
		$this->m_date = $date;
	}
	
	public function getTitle() {
		return $this->m_title;
	}
	
	public function setTitle($title) {
		$this->m_title = $title;
	}
	
	public function getComment() {
		return $this->m_comment;
	}
	
	public function setComment($comment) {
		$this->m_comment = $comment;
	}
	
	public function getPublished() {
		return $this->m_published;
	}
	
	public function setPublished($published) {
		$this->m_published = $published;
	}
	
	public function getParentID() {
		return $this->m_parentID;
	}
	
	public function setParentID($parentID) {
		$this->m_parentID = $parentID;
	}
	
}

?>