<?php
/**
 * Created by PhpStorm.
 * User: cony
 * Date: 14-3-3
 * Time: 下午2:52
 */
namespace Admin\Model;
use Think\Model;
class ProductModel extends Model {

    public function listProduct($firstRow = 0, $listRows = 20,$__map) {
        $M = M("Product");
        $list = $M->where($__map)->order("`published` DESC")->limit("$firstRow , $listRows")->select();
        $statusArr = array("待审核", "已发布");
        $aidArr = M("Admin")->field("`aid`,`email`,`nickname`")->select();
        foreach ($aidArr as $k => $v) {
            $aids[$v['aid']] = $v;
        }
        unset($aidArr);
        $map['type']='p';
        $cidArr = M("Category")->field("`cid`,`name`")->where($map)->select();
        foreach ($cidArr as $k => $v) {
            $cids[$v['cid']] = $v;
        }
        unset($cidArr);
        $zidArr = M("levy")->field("`id`,`name`")->select();
        foreach ($zidArr as $k => $v) {
            $zids[$v['cid']] = $v;
        }
        unset($zidArr);
        foreach ($list as $k => $v) {
            $list[$k]['aidName'] = $aids[$v['aid']]['nickname'] == '' ? $aids[$v['aid']]['email'] : $aids[$v['aid']]['nickname'];
            $list[$k]['cidName'] = $cids[$v['cid']]['name'];
            $list[$k]['zname'] = $zids[$v['cid']]['name'];
            // print_r($zids[$v['id']]['name']);
        }
        // die;
        return $list;
    }

    public function category() {
        if (IS_POST) {
            $act = $_POST[act];
            $data = $_POST['data'];
            $data['name'] = addslashes($data['name']);
            $data['type'] ='p';
            $M = M("Category");
            if ($act == "add") { //添加分类
                unset($data['cid']);
                $data['type']= $_POST['type'];
                if ($M->where($data)->count() == 0) {
                    return ($M->add($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功添加到系统中', 'url' => U('Product/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 添加失败');
                } else {
                    return array('status' => 0, 'info' => '系统中已经存在分类' . $data['name']);
                }
            } else if ($act == "edit") { //修改分类
                if (empty($data['name'])) {
                    unset($data['name']);
                }
                if ($data['pid'] == $data['cid']) {
                    unset($data['pid']);
                }
                return ($M->save($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功更新', 'url' => U('Product/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 更新失败');
            } else if ($act == "del") { //删除分类
                unset($data['pid'], $data['name']);
                if($M->where('pid='.$data['cid'].' AND cid!='.$data['cid'])->count()>0){
                    return (array('status' => 0, 'info' => $data['name'] . '存在下级分类，请先删除'));
                    exit;
                }
                return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功删除', 'url' => U('Product/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 删除失败');
            }
        } else {
            $map['type']='p';
            $cat = new \Org\Util\Category('Category', array('cid', 'pid', 'name', 'fullname'),$map);
            return $cat->getList();               //获取分类结构
        }
    }

     

     public function addProduct() {
        $M = M("Product");
        $data['info'] = $_POST['info'];
        $data['info']['title']=strip_tags($data['info']['title']);
        $data['info']['status']=$_POST['info']['status'];
        $data['info']['cid']=$_POST['info']['cid'];
        $data['info']['jyid'] = $_POST['info']['jyid'];
        $data['info']['rid'] = $_POST['info']['rid'];
        $data['info']['zdid'] = implode(',',$_POST['zdid']);
        $data['info']['hid'] = implode(',',$_POST['hid']);
        $data['info']['tid'] = $_POST['info']['tid'];
        $data['info']['qid'] = implode(',',$_POST['qid']);
        $data['info']['jid'] = implode(',',$_POST['jid']);
        $data['info']['zid'] = $_POST['info']['zid'];
        $data['info']['fb'] = $_POST['info']['fb'];
        $data['info']['tote'] = $_POST['info']['tote'];
        $data['info']['rate'] = $_POST['info']['rate'];
        $data['info']['term'] = $_POST['info']['term'];
        $data['info']['published'] = time();
        $data['info']['end'] = time()+$data['info']['term']*24*3600;
        $$data['info']['price'] = $_POST['info']['price'];
        $data['info']['psize'] = $_POST['info']['psize'];
        $data['info']['keywords'] = $_POST['info']['keywords'];
        $data['info']['require'] = $_POST['info']['require'];
        $data['info']['explain'] = $_POST['info']['explain'];
        $data['info']['ransom'] = $_POST['info']['ransom'];
        $data['info']['description'] = $_POST['info']['description'];
        $data['info']['summary']=nl2br($data['info']['summary']);
        $data['info']['content'] = $_POST['info']['content'];
        $data['info']['contract'] = $_POST['info']['contract'];
        $data['aid'] = $_SESSION['my_info']['aid'];
        if(empty($data['info']['title'])){
            return array('status' => 0, 'info' => "请输入标题！",'url'=>__SELF__);
        }
        $id = $M->save($data['info']);
        // echo M()->getLastsql();die;
        if ($id) {
            return array('status' => 1, 'info' => "已经更新", 'url' => U('Product/index'));
        } else {
            return array('status' => 0, 'info' => "更新失败，请刷新页面尝试操作");
        }
    }

    public function currency() {
        if (IS_POST) {
            $act = $_POST[act];
            $data = $_POST['data'];
            $data['name'] = addslashes($data['name']);
            $data['type'] ='p';
            $M = M("Currency");
            if ($act == "add") { //添加分类
                unset($data[cid]);
                $data['type']= $_POST['type'];
                if ($M->where($data)->count() == 0) {
                    return ($M->add($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功添加到系统中', 'url' => U('Product/currency', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 添加失败');
                } else {
                    return array('status' => 0, 'info' => '系统中已经存在分类' . $data['name']);
                }
            } else if ($act == "edit") { //修改分类
                if (empty($data['name'])) {
                    unset($data['name']);
                }
                if ($data['pid'] == $data['cid']) {
                    unset($data['pid']);
                }
                return ($M->save($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功更新', 'url' => U('Product/currency', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 更新失败');
            } else if ($act == "del") { //删除分类
                unset($data['pid'], $data['name']);
                if($M->where('pid='.$data['cid'].' AND cid!='.$data['cid'])->count()>0){
                    return (array('status' => 0, 'info' => $data['name'] . '存在下级分类，请先删除'));
                    exit;
                }
                return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功删除', 'url' => U('Product/currency', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 删除失败');
            }
        } else {
            // echo 222;die;
            // $map['type']='p';
            $cat = new \Org\Util\Currency('Currency', array('cid', 'pid', 'name', 'fullname'),$map);
            return $cat->getList();               //获取分类结构
        }
    }
}