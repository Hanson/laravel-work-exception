<?php


namespace Hanson\WorkException;


use EasyWeChat\Factory;
use EasyWeChat\Work\Application;
use Illuminate\Console\Command;

class ChatCommand extends Command
{

    protected $signature = 'work:chat';

    public function handle()
    {
        /** @var Application $work */
        $work = Factory::work(config('work_exception.work'));


        $departments = $work->department->list();

        $this->table(['id', 'name', 'parentid', 'order'], $departments['department']);

        $depId = $this->ask('你所在的部门的id [null]', null);

        $users = $work->user->getDepartmentUsers($depId, true)['userlist'];

        $this->table(['userid', 'name'], array_map(function ($item) {
            return [$item['userid'], $item['name']];
        }, $users));

        $userId = $this->ask('你的userID');

        $userId2 = $this->ask('选多一个userID');

        $chatId = $this->ask('输入 chat id ，不输入将系统自动生成', null);

        $result = $work->chat->create([
            'userlist' => [$userId, $userId2],
            'owner' => $userId,
            'chatid' => $chatId
        ]);

        if ($result['errcode'] != 0) {
            $this->error($result['errmsg']);
        } else {
            $this->info('创建群聊成功， chat id:'.$result['chatid']);
        }
    }

}