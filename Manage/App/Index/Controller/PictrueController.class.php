<?php
namespace Index\Controller;
use Think\Controller;
use Think\Model;
/*
 * 图片上传处理类
 */
class PictrueController extends CommonController {

	public function pic(){
		$specIndex = I('get.specIndex');
		$isSimple = I('get.isSimple');//是否调用图库
		$fold = I('get.fold');//保存模块文件夹
		if(!empty($isSimple)){
			$this->assign('isSimple',$isSimple);//是否从图库中选择图片 默认为null有图库
		}
		$this->assign('specIndex',$specIndex);
		$this->assign('fold',$fold);
		$this->display();
	}

	//获取图片列表
	public function getPhotoList()
	{
		$M = M('ProductSpecPhoto');
		$photoRs = $M->field('img_photo')->select();
		echo json_encode($photoRs);
	}


	/**
	 * 规格图片上传
	 */
	public function uploadFile()
	{
		//上传状态
		$state = false;

		//规格索引值
		$specIndex = I('get.specIndex') ? I('get.specIndex') : 0;
		//保存上传路径文件夹
		$fold = I('get.fold') ? I('get.fold') : '';
		//本地上传方式
		if(isset($_FILES['attach']) && $_FILES['attach']['name'][0]!=''){
			$config = array(
					'maxSize' => C('upload_maxSize'),   // 设置附件上传大小
					'exts'    => C('upload_exts'),      // 设置附件上传类型
					'rootPath'=> C('upload_rootPath'),  // 设置附件上传目录
			);
			if($fold !='')
				$config['rootPath'] = $config['rootPath'].$fold.'/';
			$upload = new \Think\Upload($config);// 实例化上传类
			// 上传文件
			$info   =   $upload->upload();
			if($fold !='')
				$fileName = '/uploads/'.$fold.'/'.$info[0]['savepath'].$info[0]['savename'];
			else
			$fileName = '/uploads/'.$info[0]['savepath'].$info[0]['savename'];
			if(!$info) {// 上传错误提示错误信息
				$state = false;
			}else{// 上传成功
				$state = true;
				$Photo = M('ProductSpecPhoto');
				$Photo->add(array('img_photo'=>$fileName));
			}
		}
		//图库选择方式
		else if($fileName = I('post.selectPhoto'))
		{
			$state = true;
		}

		//根据状态值进行
		if($state == true){

			$this->actJs($specIndex,$fileName);
		}
		else
		{
			$message = '添加图片失败';
			$this->redirect('Pictrue/pic',false);
			$this->showMessage($message);
		}


	}

	//与parent的js交换
	function actJs($specIndex,$fileName)
	{
		echo '
		<script type="text/javascript">
			if(parent.length>=2)
				winObj = parent[parent.length-2];
			else
				winObj = parent;
			if(!winObj.updatePic && parent[parent.length-1].updatePic)
				winObj=parent[parent.length-1];
			winObj.updatePic("'.$specIndex.'","'.$fileName.'");
		</script>';
	}

	/**
	 * @brief 显示错误信息（dialog框）
	 * @param string $message	错误提示字符串
	 */
	public static function showMessage($message)
	{
		echo '<script type="text/javascript">art.dialog.tips("'.$message.'")</script>';
		exit;
	}

}