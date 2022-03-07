<?php declare(strict_types=1);

require_once('vendor/autoload.php');

use App\Spiders\MyCharacterSpider;
use App\Spiders\RetainerSpider;
use RoachPHP\Roach;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->usePutenv(true);
$dotenv->load(__DIR__ . '/.env');

// Roach::startSpider(MyCharacterSpider::class);
Roach::startSpider(RetainerSpider::class);
