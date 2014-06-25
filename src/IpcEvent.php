<?php

namespace MarkInJapan\Ipc;

use Zend\EventManager\Event;

class IpcEvent extends Event
{
	/**
	 * @var mixed Message
	 */
	protected $_message;

	/**
	 * Get message
	 * @return mixed
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * Set message
	 * @param mixed $message Message
	 * @return self
	 */
	public function setMessage($message)
	{
		$this->_message = $message;
		return $this;
	}
}