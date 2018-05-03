<?php
/**
 * Created by PhpStorm.
 * User: fish
 * Date: 2018/3/20
 * Time: 9:40
 */

namespace app\egg\controller;


use app\api\model\AccMoney;
use app\api\model\AccUsers;
use app\auth\controller\BaseController;
use app\egg\model\EggUser;
use think\Request;

class EggTradlistController extends BaseController
{
    /**
     * 供子盘用户设置转盘接口后自动获取限额数据
     *
     * @param Request $request
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTradInfo(Request $request)
    {
        $param    = $request->only('tokensup');
        $tokensup = isset($param['tokensup']) ? trim($param['tokensup']) : null;
        if (!$tokensup) {
            return [
                'status' => 404,
                'msg'    => '无此用户'
            ];
        }
        $tokenint = AccUsers::getTokenintByTokensup($tokensup);
        $eggUser  = EggUser::getByUser($tokenint);
        if (!$eggUser) {
            return [
                'status' => 404,
                'msg'    => '没用该用户的注额设置'
            ];
        }

        $accMoney = AccMoney::getMoneyByUser($tokenint);
        if (!$accMoney) {
            return [
                'status' => 404,
                'msg'    => '用户没有资金记录'
            ];
        }

        return [
            'status' => 200,
            'data'   => [
                'trad'  => $eggUser,
                'acc_money' => $accMoney
            ]
        ];
    }
}