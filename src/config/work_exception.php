<?php

return [
    'notify' => [

        /**
         * 是否每次错误都通知（建议 false，否则可能会轰炸）
         */
        'every' => false,

        /**
         * 分钟单位，该区间同一错误只提醒一次
         */
        'interval' => 5,
    ],

    'cache' => [
        'prefix' => 'work.exception.'
    ],

    'work' => [
        'corp_id' => 'xxx',
        'agent_id' => 'xxx',
        'secret'   => 'xxx',

        /**
         * 可通过 php artisan work:chat 创建
         */
        'chatid' => 'xxx',

        /**
         * 可选 textcard 或者 text
         */
        'msgtype' => 'textcard',
    ],
];