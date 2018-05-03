<?php
/**
 * Created by PhpStorm.
 * User: fish
 * Date: 2018/3/7
 * Time: 11:04
 */

namespace app\index\controller;


use app\api\model\AccUsers;
use app\auth\controller\AdminBaseController;

class AdminIndexController extends AdminBaseController
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function genToken()
    {
        $users = AccUsers::where('id', '>', 1)->select();
        $upds = [];
        foreach ($users as $user) {
            $user->tokensup = gen_token($user->username, 'token_sup');
            array_push($upds, $user->toArray());
        }
        $uModel = new AccUsers();
        $uModel->saveAll($upds);
        $nUsers = AccUsers::where('id', '>', 1)->select();
        return $nUsers;
    }
}