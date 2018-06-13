<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-13
 * Time: 上午9:52
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Ticket as TicketModel;

class Ticket extends Controller
{
    //票房信息
    public function findBoxOffice()
    {
        if (request()->isPost()) {
            $adminor = new TicketModel();
            $res = $adminor->findBoxOffice();
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
    }
}