<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function deploy()
    {
        error_reporting(1);
        $target = '/var/www/poetryclub'; // 生产环境web目录
        $token = 'dragonfly-poetryclub';
        $wwwUser = 'www-data';
        $wwwGroup = 'www-data';
        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['token']) || $json['token'] !== $token) {
            exit('error request');
        }
        $repo = $json['repository']['name'];
        $cmd = "cd $target && git pull";
        shell_exec($cmd);
    }
}
