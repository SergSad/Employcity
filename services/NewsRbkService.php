<?php declare(strict_types=1);

namespace app\services;

use app\application\request\ClientRequestService;
use app\domain\News;
use app\domain\NewsRepositoryInterface;
use app\domain\NewsServiceInterface;
use DOMDocument;
use http\Exception\InvalidArgumentException;

class NewsRbkService implements NewsServiceInterface
{
    private NewsRepositoryInterface $newsRepository;
    private ClientRequestService $clientRequestService;
    private DOMDocument $document;

    public function __construct(
        NewsRepositoryInterface $newsRepository,
        ClientRequestService    $clientRequestService,
        DOMDocument $document)
    {
        $this->newsRepository = $newsRepository;
        $this->clientRequestService = $clientRequestService;
        $this->document = $document;
    }

    public function getUrl(): string
    {
        $now = time();

        return "https://www.rbc.ru/v10/ajax/get-news-feed/project/rbcnews.uploaded/lastDate/$now/limit/15";
    }

    public function saveNews(): void
    {
        $posts = $this->getPosts();

        foreach ($posts as $post) {
            $this->newsRepository->create($post);
        }
    }

    private function getPosts()
    {
        $body = $this->clientRequestService->getBodyPage($this->getUrl(), true);

        $result = json_decode($body->getContents(), true);

        if (!isset($result['items'])) {
            throw new InvalidArgumentException();
        }

        $posts = [];
        foreach ($result['items'] as $item) {
            $url = $this->getLink($item['html']);
            if ($this->newsRepository->existByOrderId($this->getOwnIdFromLink($url))) {
                continue;
            }
            $posts[] = $this->getNews($url);
        }

        return $posts;
    }

    private function getLink(string $html): string
    {
        $this->document->loadHTML($html);

        $list = $this->document->getElementsByTagName('a');

        return $list[0]->getAttribute('href');
    }

    private function getNews(string $url): News
    {
        $body = $this->clientRequestService->getBodyPage($url);
        $internalErrors = libxml_use_internal_errors(true);
        $this->document->loadHTML($body->getContents());
        libxml_use_internal_errors($internalErrors);
        $xpath = new \DOMXPath($this->document);
        $title = $xpath->evaluate('//div[@class="article__header__title"]//h1')[0]->textContent . PHP_EOL;
        $description = $xpath->evaluate('//div[@class="article__text__overview"]//span')[0]->textContent . PHP_EOL;
        $img = $xpath->evaluate('//div[@class="article__main-image__wrap"]//picture//img')[0];
        $descriptionArr = $xpath->query("//*[contains(@class, 'article__text')]//p");

        foreach ($descriptionArr as $item) {
            $description .= $item->textContent . PHP_EOL;
        }

        $news = new News(
            trim($title),
            trim($description),
            $this->getOwnIdFromLink($url)
        );

        if ($img) {
            $news->setImage(trim($img->getAttribute('src')));
        }

        return $news;
    }

    private function getOwnIdFromLink(string $url): string
    {
        $parseUrl = parse_url($url);
        $pathArr = explode('/', $parseUrl['path']);

        return end($pathArr);
    }
}
