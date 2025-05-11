<?php
// websocket-server.php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

class ChatNotificationServer implements MessageComponentInterface {
    protected $clients;
    protected $userConnections; // Map userId to connection

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->userConnections = [];
        echo "WebSocket server started\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        // Expect userId as query param in connection URL
        parse_str($conn->httpRequest->getUri()->getQuery(), $query);
        if (isset($query['userId'])) {
            $userId = $query['userId'];
            $this->userConnections[$userId] = $conn;
            $conn->userId = $userId;
            echo "New connection for user $userId\n";
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Expect JSON message with type and data
        $data = json_decode($msg, true);
        if (!$data) return;

        if ($data['type'] === 'new_message') {
            $receiverId = $data['receiverId'];
            if (isset($this->userConnections[$receiverId])) {
                $conn = $this->userConnections[$receiverId];
                $notification = [
                    'type' => 'notification',
                    'message' => $data['message'],
                    'senderId' => $data['senderId']
                ];
                $conn->send(json_encode($notification));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        if (isset($conn->userId)) {
            unset($this->userConnections[$conn->userId]);
            echo "Connection closed for user {$conn->userId}\n";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Run the server
$port = 8080;
$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new ChatNotificationServer()
        )
    ),
    $port
);

echo "WebSocket server listening on port $port\n";
$server->run();
