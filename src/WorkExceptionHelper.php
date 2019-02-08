<?php


namespace Hanson\WorkException;


use Exception;
use Illuminate\Support\Facades\Cache;

class WorkExceptionHelper
{

    protected $withTrace = false;

    public function handle(Exception $exception)
    {
        if ($this->notifyEachTime()) {
            $this->notify($exception);

            return;
        }

        $cache = $this->getCache($exception);

//        if (!$cache) {
//            $this->cache($exception);

            $this->notify($exception);
//        }
    }

    public function withTrace()
    {
        $this->withTrace = true;

        return $this;
    }

    public function notify(Exception $exception)
    {
        ExceptionJob::dispatch(
            request()->fullUrl(),
            get_class($exception),
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $this->withTrace ? $exception->getTraceAsString() : null
        );
    }

    /**
     * 是否每次都通知
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    private function notifyEachTime()
    {
        return config('work_exception.notify.every');
    }

    private function interval()
    {
        return config('work_exception.notify.interval', 5);
    }

    private function getCache(Exception $exception)
    {
        return Cache::get($this->cacheKey($exception));
    }

    private function cacheKey(Exception $exception)
    {
        return config('work_exception.cache.prefix').get_class($exception).$exception->getLine();
    }

    private function cache(Exception $exception)
    {
        Cache::put($this->cacheKey($exception), 1, $this->interval());
    }

}