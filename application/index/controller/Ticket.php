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
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

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

        if (is_array($res)) {
            return [
                'status'    => 0,
                'msg'       => '',
                'data'      => $res
            ];
        }
        if ($res == -1) {
            return [
                'status'    => 1,
                'msg'       => 'the film is not shown',
                'data'      => ''
            ];
        }
        if ($res == -2) {
            return [
                'status'    => 1,
                'msg'       => 'no plan for the film',
                'data'      => ''
            ];
        }
    }

    //待支付
    public function inPayment()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        /*$user = new User;
        $res = $user->isInLogin();
        if ($res == 0) {

            //用户没有登录
            return [
                'status'    => 1,
                'msg'       => 'the user didn\'t log in',
                'data'      => ''
            ];
        }*/

        $validate = validate('in_payment');
        if (!$validate->check(input('post.'))) {
            return [
                'status' => 1,
                'msg' => $validate->getError(),
                'data' => ''
            ];
        }
        $data = input('post.');

        $user = new TicketModel();
        $res = $user->inPayment($data);
        if (is_array($res)) {
            return [
                'status'    => 0,
                'msg'       => '',
                'data'      => $res
            ];
        }
        if ($res == 1) {
            return [
                'status'    => 1,
                'msg'       => 'excessive number of tickets',
                'data'      => ''
            ];
        }
        if ($res == -1) {
            return [
                'status'    => 1,
                'msg'       => 'the film has gone down',
                'data'      => ''
            ];
        }
        if ($res == -2) {
            return [
                'status'    => 1,
                'msg'       => 'the film is not shown',
                'data'      => ''
            ];
        }
        if ($res == -4) {
            return [
                'status'    => 1,
                'msg'       => 'the seat is being bought',
                'data'      => ''
            ];
        }
        if ($res == -5) {
            return [
                'status'    => 1,
                'msg'       => 'the seat has benn bought',
                'data'      => ''
            ];
        }
    }

    //购票
    public function buyTicket()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $user = new User();
        $res = $user->isInLogin();
        /*if ($res == 0) {

            //用户没有登录
            return [
                'status'    => 1,
                'msg'       => 'the user didn\'t log in',
                'data'      => ''
            ];
        }*/

        $validate = validate('buy_ticket');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => $validate->getError(),
                'data'      => ''
            ];
        }
        $data = input('post.');
        $customer = new TicketModel;

        $res = $customer->buyTicket($data);
        if (is_array($res)) {   //购票成功
            return [
                'status'    => 0,
                'msg'       => '',
                'data'      => $res
            ];
        }
        if ($res = -1) {
            return [
                'status'    => 1,
                'msg'       => 'not buying ticket according to the normal process',
                'data'      => ''
            ];
        }
        if ($res == -2) {
            return [
                'status'    => 1,
                'msg'       => 'buying ticket overtime',
                'data'      => ''
            ];
        } else {
            return [
                'status'    => 1,
                'msg'       => 'the server is busy now',
                'data'      => ''
            ];
        }

    }
}