<?php


namespace Hanson\WorkException;


use Carbon\Carbon;
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
//            $work = \EasyWeChat::work();

            $message = [
                'Time:' . Carbon::now()->toDateTimeString(),
                'Environment:' . config('app.env'),
                'Project Name:' . config('app.name'),
                'Url:' . $this->url,
                'Exception:' . sprintf('%s(code:%d): %s at %s:%d', $this->exception, $this->code, $this->message, $this->file, $this->line),
                'Exception Trace:' . $this->trace,
            ];

            $work->chat->send([
                'chatid' => 404,
                'msgtype' => 'text',
                'text' => [
                    'content' => implode(PHP_EOL, $message)
                ]
            ]);
        } catch (\Exception $exception) {

        }
    }
}