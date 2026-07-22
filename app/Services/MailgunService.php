<?php

namespace App\Services;

use RuntimeException;

class MailgunService
{
    protected string $apiKey;
    protected string $domain;
    protected string $endpoint;
    protected string $fromAddress;
    protected string $fromName;

    public function __construct()
    {
        $this->apiKey      = config('services.mailgun.secret');
        $this->domain      = config('services.mailgun.domain');
        $this->endpoint    = config('services.mailgun.endpoint', 'api.mailgun.net');
        $this->fromAddress = config('mail.from.address');
        $this->fromName    = config('mail.from.name');
    }

    /**
     * Send email via Mailgun REST API using PHP streams (no cURL, no temp files).
     */
    public function send(string $to, string $subject, string $html, string $text = ''): void
    {
        $params = http_build_query([
            'from'    => "{$this->fromName} <{$this->fromAddress}>",
            'to'      => $to,
            'subject' => $subject,
            'html'    => $html,
            'text'    => $text ?: strip_tags($html),
        ]);

        $url = "https://{$this->endpoint}/v3/{$this->domain}/messages";

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", [
                    'Authorization: Basic ' . base64_encode('api:' . $this->apiKey),
                    'Content-Type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($params),
                ]),
                'content'         => $params,
                'ignore_errors'   => true,
                'timeout'         => 15,
            ],
        ]);

        $response = file_get_contents($url, false, $context);

        // $http_response_header is set automatically by file_get_contents
        $statusLine = $http_response_header[0] ?? 'HTTP/1.1 500';
        preg_match('/\s(\d{3})\s/', $statusLine, $matches);
        $statusCode = (int) ($matches[1] ?? 500);

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new RuntimeException("Mailgun API error [{$statusCode}]: {$response}");
        }
    }
}
