<?php

namespace TeleBot\System\Types;

use GuzzleHttp\Client;
use TeleBot\System\Interfaces\IFile;
use GuzzleHttp\Exception\GuzzleException;

class File implements IFile
{

    /** @var string $resourceUrl */
    private string $resourceUrl = 'https://api.telegram.org/file/bot{token}/{path}?file_id={file_id}';

    /** @var string $downloadUrl */
    private string $downloadUrl = '';

    /** @var array $file */
    protected array $file;

    /** @var Client $client */
    private Client $client;

    /**
     * default constructor
     *
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->file = $file;
        $this->client = new Client([
            'verify' => false,
            'base_uri' => 'https://api.telegram.org'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getLink(string|int $idOrIndex): ?string
    {
        try {
            $endpoint = '/bot' . getenv('TG_BOT_TOKEN', true) . '/getFile';
            $response = $this->client->get($endpoint, [
                'query' => [
                    'file_id' => is_numeric($idOrIndex) ? $this->file[$idOrIndex]['file_id'] : $idOrIndex
                ]
            ]);

            $response = json_decode($response->getBody(), true);
            $this->downloadUrl = str_replace('{token}', getenv('TG_BOT_TOKEN', true), $this->resourceUrl);
            $this->downloadUrl = str_replace('{path}', $response['result']['file_path'], $this->downloadUrl);
            $this->downloadUrl = str_replace('{file_id}', $response['result']['file_id'], $this->downloadUrl);

            return $this->downloadUrl;
        } catch (GuzzleException $ex) {};
        return null;
    }

    /**
     * @inheritDoc
     */
    public function saveAs(string $filename = null): ?string
    {
        try {
            if (!$filename) {
                $urlPath = parse_url($this->downloadUrl, PHP_URL_PATH);
                $filename = md5((uniqid('file_') . time()));
                $filename .= '.' . pathinfo($urlPath, PATHINFO_EXTENSION);
            }

            $response = $this->client->get($this->downloadUrl);
            $saved = (bool)file_put_contents("tmp/{$filename}", $response->getBody());
            if ($saved) return $filename;
        } catch (GuzzleException $ex) {}
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getSize(bool $readable = false): int|string
    {
        if (!$readable) return $this->file['file_size'];
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $fileSize = $this->file['file_size'];
        $n = 0;

        while ($fileSize >= 1024 && $n++ < count($units))
            $fileSize /= 1024;

        return join(' ', [number_format($fileSize, 2), $units[$n]]);
    }
}