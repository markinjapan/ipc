<?php

namespace MarkInJapan\Ipc\Adapter;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use MarkInJapan\Ipc\IpcEvent;

class DbAdapter extends AbstractAdapter
{
    /**
     * Send IPC message
     * @param string $message
     * @return self
     */
    public function send($message)
    {
        // Ensure message is string
        if (is_string($message) === false) {
            throw new InvalidArgumentException('Message to send must be a string');
        }

        $this->_last_send = $message;

        // Update channel
        $this->_gateway->update(array(
            $this->_send_identifier => $this->_last_send,
        ), $this->_getChannel());

        // Trigger IPC "sent" event
        $e = new IpcEvent('sent', $this);
        $e->setMessage($this->_last_send);
        $this->getEventManager()->trigger($e);

        return $this;
    }

    /**
     * Receive IPC message
     * @return string
     */
    public function receive()
    {
        // Read channel
        $channel = $this->_gateway->select($this->_getChannel());
        if (count($channel) === 0) {
            throw new RuntimeException('Channel could not be found');
        }

        // Extract message from channel
        $this->_last_recv = $channel->current()[$this->_recv_identifier];

        // Trigger IPC "received" event
        $e = new IpcEvent('received', $this);
        $e->setMessage($this->_last_recv);
        $this->getEventManager()->trigger($e);

        return $this->_last_recv;
    }

    protected function _getChannel()
    {
        // If channel is string or integer, assume channel column is "id"
        if (is_string($this->_channel) or is_integer($this->_channel)) {
            return array('id' => $this->_channel);
        }
        // Else if channel is array, assume columm => identifier pair
        elseif (is_array($this->_channel)) {
            return $this->_channel;
        }
        else {
            throw new InvalidArgumentException('Channel must be either a string, integer, or column => identifier pair');
        }
    }
}