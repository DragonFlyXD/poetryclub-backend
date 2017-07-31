<?php

/**
 * 自定义 辅助函数
 */

// 获取用户信息
if (!function_exists('user')) {
    function user($drive = 'api')
    {
        // 若指定驱动
        if ($drive) {
            return app('auth')->guard($drive)->user();
        }
        return app('auth')->user();
    }
}

// 获取用户ID
if (!function_exists('id')) {
    function id($drive = 'api')
    {
        // 若指定驱动
        if ($drive) {
            return app('auth')->guard($drive)->id();
        }
        return app('auth')->id();
    }
}

// 检验用户是否登录
if (!function_exists('check')) {
    function check($drive = 'api')
    {
        // 若指定驱动
        if ($drive) {
            return app('auth')->guard($drive)->check();
        }
        return app('auth')->check();
    }
}

// 将目标转化为 collection 对象
if (!function_exists('collection')) {
    function collection($target)
    {
        // 如果目标已为 collection 对象,则直接返回
        if ($target instanceof \Illuminate\Support\Collection) {
            return $target;
        }
        // 否则将目标实例化为 collection 对象返回
        return collect($target);
    }
}

// 格式化评分
if (!function_exists('rating')) {
    function rating($target)
    {
        // 如果目标是数组
        if (is_array($target)) {
            $target = key($target);
        }
        return floatval(preg_replace('/(rating_)(\d+)/', '$2', $target));
    }
}

