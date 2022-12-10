<?php
namespace App\Interfaces;

interface AuthGuardInterface {
    public function getTokenRow();
    public function getTokenId($guard);
    public function getTokenName($guard);
}
