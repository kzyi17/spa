<?php
namespace Index\Model;
use Think\Model;
class ShopConfModel extends Model {


	/**
	 * 网址基础保存
	 * @return Ambigous <multitype:number string , multitype:number string Ambigous <string, mixed> >|multitype:number string Ambigous <string, mixed>
	 * @author  haichao
	 */
	public  function saveConfig(){
		$message = null;
		$inputArray = I('post.data');
		$form = I('get.form');
		switch($form)
		{
			case "base_conf":
				{
					if(isset($_FILES['logo']['name']) && $_FILES['logo']['name']!='')
					{
						$config = array(
								'maxSize' => C('upload_maxSize'),   // 设置附件上传大小
								'exts'    => C('upload_exts'),    // 设置附件上传类型
								'rootPath'=> C('upload_rootPublicPath'),  // 设置附件上传目录
								'autoSub' =>false,
								'replace' =>true,
						);
						$upload = new \Think\Upload($config);// 实例化上传类
						$upload->saveName = 'logo';
						$info   =   $upload->upload();
						$fileName = $info['logo']['savename'];
						if(!$info) {// 上传错误提示错误信息
							$message = '图片上传失败';
						}else{// 上传成功
							$inputArray['logo'] = $fileName;
						}
					}
				}
				break;

			case "site_footer_conf":
				{
					$inputArray['site_footer_code']=preg_replace('![\\r\\n]+!',"",$_POST['content']);
				}
				break;

			case "index_slide":
				{
					$config_slide = array();
					if(isset($_POST['slide_name']))
					{
						foreach($_POST['slide_name'] as $key => $value)
						{
							$config_slide[$key]['name'] = $value;
							$config_slide[$key]['url']  = $_POST['slide_url'][$key];
							$config_slide[$key]['img']  = $_POST['slide_img'][$key];
							$config_slide[$key]['position']  = $_POST['slide_position'][$key];
						}
					}

					if( isset($_FILES['slide_pic']) )
					{
						$config = array(
								'maxSize' => C('upload_maxSize'),   // 设置附件上传大小
								'exts'    => C('upload_exts'),    // 设置附件上传类型
								'rootPath'=> C('upload_rootPath'),  // 设置附件上传目录
						);
						$rootPath = $config['rootPath'];
						$config['rootPath'] = $rootPath.'uploads/indexSlide/';
						$upload = new \Think\Upload($config);// 实例化上传类
						// 上传文件
						$info   =   $upload->upload();
						if($info)
						{
							foreach($info as $key=>$value)
							{
								if(file_exists($rootPath.$config_slide[$key]['img'])){
									unlink($rootPath.$config_slide[$key]['img']);
								}
								$config_slide[$key]['img']='/uploads/indexSlide/'.$value['savepath'].$value['savename'];
							}
						}

					}
					$inputArray['index_slide'] = serialize($config_slide);

				}
				break;

			case "guide_conf":
				{

					$guideName = I('post.guide_name');
					$guideLink = I('post.guide_link');
					$data      = array();

					$guideObj = M('guide');

					if(!empty($guideName))
					{
						foreach($guideName as $key => $val)
						{
							if(!empty($val) && !empty($guideLink[$key]))
							{
								$data[$key]['name'] = $val;
								$data[$key]['link'] = $guideLink[$key];
							}
						}
					}
					//清空导航栏
					$guideObj->where(true)->delete();

					if(!empty($data))
					{
						//插入数据
						foreach($data as $order => $rs)
						{
							$dataArray = array(
									'order' => $order,
									'name'  => $rs['name'],
									'link'  => $rs['link'],
							);
							$dataArrays[] = $dataArray;

							$lastId = $guideObj->add($dataArray);
						}
						$datas[$form] = serialize($dataArrays);
						$configs = M('shop_conf')->field('id')->select();
						if($configs)
							M('shop_conf')->where('id ='.$configs[0]['id'])->save($datas);
						else
							M('shop_conf')->add($datas);
						//跳转方法
						return  $lastId ? array('status' => 1,'info' => '导航栏保存成功','url' =>u('system/index')):array('status' =>0,'info' => '导航栏保存失败','url' =>u('system/index'));
						exit();
					}
				}
				break;

			case "shopping_conf":
				break;

			case "show_conf":
				{
					if( isset($inputArray['auto_finish']) && $inputArray['auto_finish']=="" )
					{
						$inputArray['auto_finish']=="0";
					}
				}
				break;

			case "mail_conf":
				break;
			case "system_conf":
				break;
			case "service_online":
				{
					$serviceName = I('post.service_name');
					$serviceQQ   = I('post.service_qq');
					$data        = array();
					foreach($serviceName as $key => $val)
					{
						$data[] = array('name' => $serviceName[$key],'qq' => $serviceQQ[$key]);
					}
					$inputArray['service_online'] = serialize($data);
				}
				break;
		}

		if($message == null)
		{

			$configFile = realpath('App/Common/Conf/site.php');
			$configStr = "";

			//读取配置信息内容
			if(file_exists($configFile))
			{
				$configStr   = file_get_contents($configFile);
				$configArray = include($configFile);
			}
			if(trim($configStr)=="" || $configArray ==null)
			{
				$configStr   = "<?php return array( \r\n);?>";
				$configArray = array();
			}
			//保存数据表
			$datas[$form] = serialize($inputArray);

			$configs = M('shop_conf')->field('id')->select();

			if($configs){
				$lstid = M('shop_conf')->where('id ='.$configs[0]['id'])->save($datas);
			}else{
				M('shop_conf')->add($datas);
			}
			$inputArray = array_merge($configArray,$inputArray);
			$configData = var_export($inputArray,true);

			$configStr = "<?php return {$configData}?>";
			//写入配置文件
			if($form != 'guide_conf'){

				$fp = fopen($configFile, 'w+');
				fputs($fp, $configStr);
				fclose($fp);
			}
			return array('status' => 1,'info' => '配置保存成功','url' =>u('system/index'));
		}
		else
		{
			return array('status' => 0,'info' => $message,'url' =>u('system/index'));
		}
	}


}