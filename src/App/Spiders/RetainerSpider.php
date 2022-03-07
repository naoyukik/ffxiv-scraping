<?php

namespace App\Spiders;

use App\ItemProcessors\MyCharacterProcessor;
use App\ItemProcessors\RetainerProcessor;
use Generator;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use RoachPHP\Downloader\Middleware\CookieMiddleware;
use RoachPHP\Downloader\Middleware\UserAgentMiddleware;
use RoachPHP\Http\Request;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class RetainerSpider extends BasicSpider
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
            RetainerProcessor::class,
            [],
        ],
    ];
    /**
     * @var array<array-key, array<array-key, mixed>>
     */
    public array $downloaderMiddleware = [
        [
            UserAgentMiddleware::class,
            ['userAgent' => 'Mozilla/5.0 (compatible; RoachPHP/0.3.0)'],
        ],
        [
            CookieMiddleware::class,
            [],
        ],
    ];
    private string $myCharacterLdstSess;
    private string $myCharacterEndpoint;

    public function __construct()
    {
        parent::__construct();
        /** @var string myCharacterId */
        $myCharacterId = getenv('MY_CHARACTER_ID');
        /** @var string myCharacterLdstSess */
        $this->myCharacterLdstSess = getenv('MY_CHARACTER_LDST_SESS');
        $this->myCharacterEndpoint = vsprintf('%s/%s', ['url' => self::START_URL, 'my_character_id' => $myCharacterId]);
    }

    /**
     * @return array<array-key, Request>
     */
    protected function initialRequests(): array
    {
        $url = sprintf('%s/%s', $this->myCharacterEndpoint, 'retainer');

        return [
            new Request(
                'GET',
                $url,
                [$this, 'parse'],
                [
                    'headers' => [
                        'Cookie' => [
                            $this->myCharacterLdstSess
                        ],
                    ],
                    'debug' => false,
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
        $retainersCssSelector = $response->filter(
            'body > div.ldst__bg > div.ldst__contents.clearfix > 
            div.ldst__main > div > div.retainer__data.js__toggle_wrapper > ul > li > a'
        );
        // $retainersXpath = '/html/body/div[3]/div[2]/div[1]/div/div[1]/ul';
        // $retainers = $response->filterXPath($retainersXpath);

        yield $this->item([
            'retainers1' => $retainersCssSelector,
            'retainers2' => $retainersCssSelector,
        ]);
    }
}
