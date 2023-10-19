<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\BotMessage\src;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Hyperf\Contract\ConfigInterface;
use Xhtkyy\HyperfTools\BotMessage\BotMessageInterface;

class DingTalk implements BotMessageInterface
{
    private bool $debug = false;
    private string $pipeline = 'default';
    private array $body = [];
    private array $config = [];
    private array $at = [];

    public function __construct(protected ConfigInterface $conf)
    {
        $this->config = $this->conf->get('bot_notice.dingTalk', []);
    }

    public function text(string $content): static
    {
        $this->body = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content,
            ]
        ];
        return $this;
    }

    public function markdown(string $title, string $content): static
    {
        $this->body = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $content,
            ]
        ];
        return $this;
    }

    private function decodeContent(mixed $content): string
    {
        return match (true) {
            is_string($content) => $content,
            is_array($content), is_object($content) => '``` ' . json_encode($content, JSON_UNESCAPED_UNICODE) . ' ```',
            default => (string)$content
        };
    }

    public function info(string $title, mixed $content)
    {
        $this->body = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => '### ' . $title . " \n --- \n " . $this->decodeContent($content),
            ]
        ];
        return $this;
    }

    public function error(string $title, mixed $content)
    {
        $this->body = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => "### " . "<font color=red>{$title}</font>" . " \n --- \n " . $this->decodeContent($content),
            ]
        ];
        return $this;
    }

    public function pipeline(string $pipeline): static
    {
        $this->pipeline = $pipeline;
        return $this;
    }

    public function debug()
    {
        $this->debug = true;
        return $this;
    }

    public function notice(): bool
    {
        if (empty($this->body)) return false;
        if ($this->debug && strtolower($this->conf->get('app_env', '')) != 'dev') return false;

        if (!empty($this->at)) {
            $this->body['at'] = [
                'isAtAll' => in_array('all', $this->at),
                'atMobiles' => $this->at
            ];
        }

        $client = new Client(['base_uri' => 'https://oapi.dingtalk.com']);
        try {
            $client->post('/robot/send?' . $this->getSignQuery(), [RequestOptions::JSON => $this->body]);
        } catch (GuzzleException|Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getSignQuery(): string
    {
        $secret = $this->config[$this->pipeline]['access_secret'] ?? '';
        $access_token = $this->config[$this->pipeline]['access_token'] ?? '';
        if (empty($secret) || empty($access_token)) throw new Exception();
        $timestamp = intval(microtime(true) * 1000);
        $sign = urlencode(base64_encode(hash_hmac('sha256', $timestamp . "\n" . $secret, $secret, true)));
        return http_build_query(compact("access_token", "timestamp", "sign"));
    }

    public function at(array $at): static
    {
        $this->at = $at;
        return $this;
    }
}