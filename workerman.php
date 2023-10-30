<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;

$users = [];

$ws_worker = new Worker("websocket://0.0.0.0:8080");
$ws_worker->onWorkerStart = function() use (&$users)
{
    $inner_tcp_worker = new Worker("tcp://127.0.0.1:1234");
    $inner_tcp_worker->onMessage = function($connection, $data) use (&$users) {
        $data = json_decode($data);
        if (isset($users[$data->user])) {
            $webconnection = $users[$data->user];
            $webconnection->send($data->message);
        }
    };
    $inner_tcp_worker->listen();
};

$ws_worker->onConnect = function($connection) use (&$users)
{
    $connection->onWebSocketConnect = function($connection) use (&$users)
    {
        $users[$_GET['user']] = $connection;
    };
};

$ws_worker->onClose = function($connection) use(&$users)
{
    $user = array_search($connection, $users);
    unset($users[$user]);
};

// Run worker
Worker::runAll();
