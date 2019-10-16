<?php

namespace Name;

class Repository
{
    public function __construct()
    {
        session_start();
    }

    public function destroy()
    {
        session_destroy();
    }

    public function all()
    {
        return array_values($_SESSION);
    }

    public function find(int $id)
    {
        return $_SESSION[$id];
    }

    public function save($item)
    {
        $item['id'] = uniqid();
        $_SESSION[$item['id']] = $item;
    }
}