<?php

namespace MarkInJapan\Ipc\Adapter;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

abstract class AbstractAdapter implements IpcAdapterInterface, EventManagerAwareInterface
{
	use EventManagerAwareTrait;

    /**
     * @var object Gateway to IPC realm
     * @todo "Realm" sounds like LoTR... use better name
     */
    protected $_gateway;

    /**
     * @var string Channel within IPC realm
     */
    protected $_channel;

    /**
     * @var string Identifier for "send" part of message
     */
    protected $_send_identifier;

    /**
     * @var string Identifier for "receive" part of message
     */
    protected $_recv_identifier;

    /**
     * @var string Last "send" message
     */
    protected $_last_send;

    /**
     * @var string Last "recieve" message
     */
    protected $_last_recv;

    /**
     * Constructor
     * @param object $gateway Gateway to IPC realm
     * @param string $channel Chanel within IPC realm
     * @param string $send_identifier Part of message used for sending (other party will receive here)
     * @param string $recv_identifier Part of message used for receiving (other party will send here)
     */
    public function __construct($gateway, $channel, $send_identifier, $recv_identifier)
    {
        // Ensure send and receive identifers are string type
        if (is_string($send_identifier) === false) {
            throw new InvalidArgumentException('Send identifier must be a string');
        }
        if (is_string($recv_identifier) === false) {
            throw new InvalidArgumentException('Receive identifier must be a string');
        }

        $this->_gateway = $gateway;
        $this->_channel = $channel;
        $this->_send_identifier = $send_identifier;
        $this->_recv_identifier = $recv_identifier;
    }

    /**
     * Send IPC message
     * @param string $message
     * @return self
     */
    abstract public function send($message);

    /**
     * Receive IPC message
     * @return string
     */
    abstract public function receive();

    /**
     * Get last "send" message
     * @return string
     */
    public function getLastSend()
    {
        return $this->_last_send;
    }

    /**
     * Get last "receive" message
     * @return string
     */
    public function getLastReceive()
    {
        return $this->_last_recv;
    }
}