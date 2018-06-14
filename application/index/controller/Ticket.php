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

    //查询电影的演出计划
    public function findMoiveSche()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $validate = validate('find_movie_Sche');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => 'the data you input is not legal',
                'data'      => ''
            ];
        }
        $data =  input('post.');
        $user = new TicketModel();
        $res = $user->findMovieSche($data);
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
                'msg'       => 'the movie has gone',
                'data'      => ''
            ];
        }
        if ($res == -2) {
            return [
                'status'    => 1,
                'msg'       => 'no plan for the film',
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

    //查询演出座位售出情况
    public function findTicket()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $validate = validate('find_ticket');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => 'the data you input is not legal',
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
        if ($res == -2) {
            return [
                'status'    => 1,
                'msg'       => 'no plan for the film',
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
                'msg' => 'the data you input is not legal',
                'data' => ''
            ];
        }
        $data = input('post.');

        $user = new TicketModel();
        $res = $user->inPayment($data);
//        var_dump($res);
        if (is_array($res)) {
            return [
                'status'    => 0,
                'msg'       => '',
                'data'      => $res
            ];
        }
        if ($res == 2) {
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
        if ($res == 1) {
            return [
                'status'    => 1,
                'msg'       => 'the seat has been bought',
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
        if (!$validate->check(input('get.'))) {
            return [
                'status'    => 1,
                'msg'       => 'the data you input is not legal',
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
        if ($res == -1) {
            return [
                'status'    => 1,
                'msg'       => 'no payment',
                'data'      => ''
            ];
        }
        if ($res == -2) {
            return [
                'status'    => 1,
                'msg'       => 'no order information',
                'data'      => ''
            ];
        }
        if ($res == -3) {
            return [
                'status'    => 1,
                'msg'       => 'buying ticket overtime',
                'data'      => ''
            ];
        }
        if ($res == -4) {
            return [
                'status'    => 1,
                'msg'       => 'the film is not shown',
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

    //退票
    public function refundTicket()
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

        $validate = validate('refund_ticket');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => 'the data you input is not legal',
                'data'      => ''
            ];
        }
        $data = input('post.');
        $user = new TicketModel();
        $res = $user->refundTicket($data);
        if ($res == 0) {
            return [
                'status'    => 0,
                'msg'       => '',
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

    //用户查询自己的订单(未过期)
    public function findUserTicket()
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

        $user = new TicketModel();
        $res = $user->findUserTicket();
        if (is_array($res)) {
            return [
                'status'    => 0,
                'msg'       => '',
                'data'      => $res
            ];
        } else {
            return [
                'status'    => 1,
                'msg'       => 'the server is busy now',
                'data'      => ''
            ];
        }

    }

    public function findTicketStat()
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

        $validate = validate('find_ticket_stat');
        if (!$validate->check(input('post.'))) {
            return [
                'status'    => 1,
                'msg'       => 'the data you input is not legal',
                'data'      => ''
            ];
        }
        $data = input('post.');
        $user = new TicketModel();
        $res = $user->findTicketStat($data);
        if ($res == 0) {
            return [
                'status'    => 0,
                'msg'       => '',
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