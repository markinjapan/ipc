<?php

namespace MarkInJapan\Ipc\Adapter;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;

class DbAdapter extends AbstractAdapter
{
    /**
     * Send IPC message
     * @param string $message
     * @return self
     */
    public function send($message)
    {
        $this->_last_send = $message;

        // Update channel
        $this->_gateway->update(array(
            $this->_send_identifier => $this->_last_send,
        ), array(
            'id' => $this->_channel,
        ));

        $this->getEventManager()->trigger('ipc.sent', $this, array('message' => $this->_last_send));

        return $this;
    }

    /**
     * Receive IPC message
     * @return string
     */
    public function receive()
    {
        // Read channel
        $channel = $this->_gateway->select(array(
            'id' => $this->_channel,
        ));
        if (count($channel) === 0) {
            throw new RuntimeException('Channel "' . $this->_channel . '" could not be found');
        }

        // Extract message from channel
        $this->_last_recv = $channel->current()[$this->_recv_identifier];

        $this->getEventManager()->trigger('ipc.received', $this, array('message' => $this->_last_recv));

        return $this->_last_recv;
    }
}