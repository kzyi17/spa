<?php
namespace Home\Controller;
use Think\Controller;
class MerchantController extends Controller {
    
    
    public function merchantList(){
        $this->display('./merchantList');
    }
    
    public function detail(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->display('./merchantDetail');
    }
    
    public function map(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->display('./map_merchant');
    }
}