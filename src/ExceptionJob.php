<?php


namespace Hanson\WorkException;


use Carbon\Carbon;
use EasyWeChat\Factory;
use EasyWeChat\OpenWork\Work\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url;

    private $exception;
    
    private $message;
    private $code;
    private $file;
    private $line;
    private $trace;

    public function __construct($url, $exception, $message, $code, $file, $line, $trace)
    {
        $this->message = $message;
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
        $this->url = $url;
        $this->trace = $trace;
        $this->exception = $exception;
    }
    
    public function handle()
    {
        try {
            /** @var Application $work */
            $work = Factory::work(config('work_exception.work'));

            $message = [
                '环境: ' . config('app.env'),
                '项目名称:' . config('app.name'),
                '出错链接: ' . $this->url,
                '异常信息: ' . sprintf('%s(code:%d): %s at %s:%d', $this->exception, $this->code, $this->message, $this->file, $this->line),
                $this->trace ? '异常追踪: ' . $this->trace : null,
            ];

            $result = $work->chat->send([
                'chatid' => config('work_exception.work.chatid'),
                'msgtype' => config('work_exception.work.msgtype', 'textcard'),
                'textcard' => [
                    'title' => config('app.name'),
                    'description' => sprintf("<div>环境： %s</div><div>出错链接： %s</div><div>异常信息： %s</div>", config('app.env'), $this->url, sprintf('%s(code:%d): %s at %s:%d', $this->exception, $this->code, $this->message, $this->file, $this->line)),
                    'url' => $this->url
                ],
                'text' => [
                    'content' => implode(PHP_EOL, $message)
                ]
            ]);

            if ($result['errcode'] != 0) {
                logger($result['errmsg']);
            }
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }
}