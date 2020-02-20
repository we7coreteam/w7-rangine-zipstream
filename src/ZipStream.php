<?php
/**
 * Rangine ZipStream
 *
 * (c) We7Team 2019 <https://www.rangine.com>
 *
 * document https://wiki.w7.cc/chapter/1?id=7#
 *
 * visited https://www.rangine.com for more details
 */

namespace W7\ZipStream;


use W7\Http\Message\Server\Response;
use ZipStream\Option\Archive as ArchiveOptions;

/**
 * Class ZipStream
 * @package W7\ZipStream
 */
class ZipStream extends \ZipStream\ZipStream {
	const DEFAULT_SEND_HTTP_HEADER_CALLBACK = 'header';

	private $response;

	public function __construct(?string $name = null, ?ArchiveOptions $opt = null) {
		parent::__construct($name, $opt);

		if ($this->opt->getOutputStream() instanceof \Swoole\Http\Response) {
			$this->response = Response::loadFromSwooleResponse($this->opt->getOutputStream());
		} elseif ($this->opt->getOutputStream() instanceof Response) {
			$this->response = $this->opt->getOutputStream();
		} else {
			throw new \RuntimeException('Please specify swoole response object');
		}
	}

	public function send(string $str): void {
		if ($this->opt->getHttpHeaderCallback() == self::DEFAULT_SEND_HTTP_HEADER_CALLBACK) {
			$this->opt->setHttpHeaderCallback(function ($headerString) {
				$this->withSendHeader($headerString);
			});
		}
		
		if ($this->need_headers) {
			$this->sendHttpHeaders();
		}
		$this->need_headers = false;

		$this->response->withContent($str)->write();
	}

	protected function withSendHeader($headerString): void {
		list($name, $value) = explode(': ', $headerString);
		if (empty($name) || empty($value)) {
			return false;
		}
		$this->response = $this->response->withHeader($name, $value);
	}
}