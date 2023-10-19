<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

return [
    'dingTalk' => [
        'default' => [
            'access_token' => \Hyperf\Support\env('DINK_TALK_DEFAULT_TOKEN', 'a2cc359877fc37b160e7253beb2e291e0eb5affdbc152e3d65ee17c0cfcdeb3b'),
            'access_secret' => \Hyperf\Support\env('DINK_TALK_DEFAULT_SECRET', ''),
        ]
    ]
];