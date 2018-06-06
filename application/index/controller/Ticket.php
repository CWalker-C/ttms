<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-5
 * Time: 下午2:42
 */

namespace app\index\controller;

use think\Controller;
use app\index\model\Ticket as TicketModel;

class Ticket extends Controller
{
    public function index()
    {
        return [
            'key'   => 'value'
        ];
    }

    //查询是否有票
    public function findTicket()
    {
        $validate = validate('find_ticket');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => $validate->getError(),
                'data'      => ''
            ];
        }

        $data =  input('post.');

        $user = new TicketModel();
        $res = $user->findTicket($data);
        if ($res) {

        }
        if ($res) {

        }

    }

    //待支付


    //购票
    public function buyTicket()
    {
        $user = new User();
        $res = $user->isInLogin();
        if ($res == 0) {

            //用户没有登录
            return [
                'status'    => 1,
                'msg'       => 'the user didn\'t log in',
                'data'      => ''
            ];
        }

        $validate = validate('buy_ticket');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => $validate->getErr(),
                'data'      => ''
            ];
        }

        $customer = new TicketModel;
        $res = $customer->buyTicket($data);
        if ($res) {

        }
        if ($res) {

        }
    }
}