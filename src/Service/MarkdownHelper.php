<?php
/**
 * Created by PhpStorm.
 * User: iiordache
 * Date: 20/06/2018
 * Time: 17:13
 */

namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{

    private $cache;

    private $markdown;
    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger)
    {
        $this->cache = $cache;

        $this->markdown = $markdown;

        $this->logger = $markdownLogger;
    }

    /**
     * @param string $source
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $source): string
    {

        if (stripos($source, 'bacon')!== false){
            $this->logger->info('They are talking about bacon again');
        }

        $item = $this->cache->getItem('markdown_'.md5($source));
        if (!$item->isHit()){
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();

    }
}