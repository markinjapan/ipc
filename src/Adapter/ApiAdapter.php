<?php

namespace MarkInJapan\Ipc\Adapter;

use InvalidArgumentException;
use MarkInJapan\RestClient\ApiGateway;
use MarkInJapan\Ipc\IpcEvent;

class ApiAdapter extends AbstractAdapter
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

        $this->_gateway->update($this->_channel, array(
            $this->_send_identifier => $this->_last_send,
        ));

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
        $channel = $this->_gateway->fetch($this->_channel);
        if (count($channel) === 0) {
            throw new RuntimeException('Channel could not be found');
        }

        // Extract message from channel
        $this->_last_recv = $channel->current()->{$this->_recv_identifier};

        // Trigger IPC "received" event
        $e = new IpcEvent('received', $this);
        $e->setMessage($this->_last_recv);
        $this->getEventManager()->trigger($e);

        return $this->_last_recv;
    }
}