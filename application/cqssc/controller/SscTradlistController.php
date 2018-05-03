<?php
/**
 * Created by PhpStorm.
 * User: fish
 * Date: 2018/3/20
 * Time: 9:40
 */

namespace app\cqssc\controller;


use app\api\model\AccMoney;
use app\api\model\AccUsers;
use app\auth\controller\BaseController;
use app\cqssc\model\SscUser;
use think\Request;

class SscTradlistController extends BaseController
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
            $this->jsonData['status'] = 404;
            $this->jsonData['msg']    = '无此用户';
            return $this->jsonData;
        }
        $tokenint = AccUsers::getTokenintByTokensup($tokensup);
        $sscUser  = SscUser::getByUser($tokenint);
        if (!$sscUser) {
            $this->jsonData['status'] = 404;
            $this->jsonData['msg']    = '没用该用户的注额设置';
            return $this->jsonData;
        }

        $accMoney = AccMoney::getMoneyByUser($tokenint);
        if (!$accMoney) {
            $this->jsonData['status'] = 404;
            $this->jsonData['msg']    = '用户没有资金记录';
            return $this->jsonData;
        }

        $this->jsonData['data']['trad'] = $sscUser;
        $this->jsonData['data']['acc_money'] = $accMoney;
        return $this->jsonData;
    }
}