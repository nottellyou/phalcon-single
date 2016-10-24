<?php

class Log extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource("log");
    }
}
