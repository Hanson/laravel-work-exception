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
        'corp_id' => 'wx34e688a622367ad4',
        'agent_id' => 1000010,
        'secret'   => 'SfaIxqwI9CnrLKJVXlQWMTT9gK8La70U9TyvkNCH5Sc',

        'chatid' => 404,
    ],
];