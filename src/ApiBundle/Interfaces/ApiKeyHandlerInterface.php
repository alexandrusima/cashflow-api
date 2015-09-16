<?php 
namespace ApiBundle\Interfaces;

interface ApiKeyHandlerInterface {
    public function getUsernameFromApiKey($apiKey);
}