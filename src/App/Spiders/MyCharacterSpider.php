<?php

namespace App\Spiders;

use App\ItemProcessors\MyCharacterProcessor;
use Generator;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use RoachPHP\Downloader\Middleware\CookieMiddleware;
use RoachPHP\Downloader\Middleware\UserAgentMiddleware;
use RoachPHP\Http\Request;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class MyCharacterSpider extends BasicSpider
{
    /**
     * @var string
     */
    public const START_URL = 'https://jp.finalfantasyxiv.com/lodestone/character';
    /**
     * @var array<array-key, array<array-key, mixed>>
     */
    public array $itemProcessors = [
        [
            MyCharacterProcessor::class,
            [],
        ],
    ];

    public array $downloaderMiddleware = [
        [
            UserAgentMiddleware::class,
            ['userAgent' => 'Mozilla/5.0 (compatible; RoachPHP/0.3.0)'],
        ],
        [
            CookieMiddleware::class,
            []
        ],
    ];
    private string $myCharacterId;
    private string $myCharacterLdstSess;

    public function __construct()
    {
        parent::__construct();
        /** @var string myCharacterId */
        $this->myCharacterId = getenv('MY_CHARACTER_ID');
        /** @var string myCharacterLdstSess */
        $this->myCharacterLdstSess = getenv('MY_CHARACTER_LDST_SESS');
    }

    /**
     * @return array<array-key, Request>
     */
    protected function initialRequests(): array
    {
        $charUrl = self::START_URL . '/' . $this->myCharacterId;

        return [
            new Request(
                'GET',
                $charUrl,
                [$this, 'parse'],
                [
                    'headers' => [
                        'Cookie' => [
                            $this->myCharacterLdstSess
                        ],
                    ],
                    'debug' => true,
                    // 'cookies' => $cookieJar,
                    'allow_redirects' => true,
                ]
            ),
        ];
    }

    /**
     * @param  Response  $response
     * @return Generator<mixed, ParseResult, mixed, mixed>
     */
    public function parse(Response $response): Generator
    {
        $characterNameXpath = '//*[@id="character"]/div[1]/a[1]/div[2]/p[1]';
        $characterName = $response->filterXPath($characterNameXpath)->text();

        yield $this->item([
            'character_name' => $characterName,
            'retainers_link' => $retainersLink,
        ]);
    }
}
