<?php

class InterfaceAction extends Action {
	
	public function _initialize(){
		$this->interface = M('interface');
		$this->project = M('project');
		$this->category = M('category');
	}
	
	public function index(){
		$projectId = (int)$this->_get('projectid');
		if ($projectId) {
			$data = $this->project->find($projectId);
			if (!$data) $this->error('项目不存在');
			
			$interfaceList = array();
			$categoryList = array('0' => '其他');
			
			$commonData = $this->interface->where('project_id='.$projectId)->select();
			$categoryData = $this->category->where('project_id='.$projectId)->field('id,name')->select();
			foreach ($categoryData as $k => $v) {
				$categoryList[$v['id']] = $v['name'];
			}
			
			
			foreach ($commonData as $k => $v) {
				$interfaceList[$categoryList[$v['category_id']]][$k] = $v;
			}
			
			
			/* $categoryList = $this->category->where('project_id='.$projectId)->field('id,name')->select();
			foreach ($categoryList as $k => $v) {
				if ($interfaceData = $this->interface->where('category_id='.$v['id'])->field('id,name,updated_at')->select()) {
					$interfaceList[$v['name']] = $interfaceData;
				}
			} */
			
			$this->assign('interfaceList', $interfaceList);
			$this->assign('data', $data);
			$this->display();
		}
		
	}
	
	
	public function detail() {
		$id = (int)$this->_get('id');
		if ($id) {
			$interfaceData = $this->interface->find($id);
			if (!$interfaceData) $this->error('接口不存在');
			$data = $this->project->find($interfaceData['project_id']);
			if (!$data) $this->error('项目不存在');
			
			
			/* $interfaceList = array();
			$categoryList = $this->category->where('project_id='.$interfaceData['project_id'])->field('id,name')->select();
			foreach ($categoryList as $k => $v) {
				if ($commonData = $this->interface->where('category_id='.$v['id'])->field('id,name')->select()) {
					$interfaceList[$v['name']] = $commonData;
				}
			} */
			$interfaceList = array();
			$categoryList = array('0' => '其他');
				
			$commonData = $this->interface->where('project_id='.$interfaceData['project_id'])->select();
			$categoryData = $this->category->where('project_id='.$interfaceData['project_id'])->field('id,name')->select();
			foreach ($categoryData as $k => $v) {
				$categoryList[$v['id']] = $v['name'];
			}
				
				
			foreach ($commonData as $k => $v) {
				$interfaceList[$categoryList[$v['category_id']]][$k] = $v;
			}
			

			
			$inputJson = json_decode($interfaceData['input_json'], true);
			$inputUrl = array();
			foreach ($inputJson as $k => $v) {
				$inputUrl[$v['name']] = $v['sample'];
			}
			$inputUrl = http_build_query($inputUrl);
			
			$interfaceData['input_json'] = json_decode($interfaceData['input_json'], true);
			
			if ($interfaceData['input_json']) {
				$urlKey = '?'. $inputUrl;
			} else {
				$urlKey = '';
			}
			
			$interfaceData['success_json'] = json_decode($interfaceData['success_json'], true);
			$interfaceData['fail_json'] = json_decode($interfaceData['fail_json'], true);
			
			
			if (strpos($interfaceData['path_product'], 'http') === 0) {
				$interfaceData['path_product'] .= $urlKey;
			} else {
				if ($interfaceData['path_product']) {
					$interfaceData['path_product'] = trim($data['host_product'], '/').'/'.trim($interfaceData['path_product'], '/').$urlKey;
				}
			}
			
			if (strpos($interfaceData['path_develop'], 'http') === 0) {
				$interfaceData['path_develop'] .= $urlKey;
			} else {
				if ($interfaceData['path_develop']) {
					$interfaceData['path_develop'] = trim($data['host_develop'], '/').'/'.trim($interfaceData['path_develop'], '/').$urlKey;
				}
			}
			
			
			if (strpos($interfaceData['path_faker'], 'http') === 0) {
				$interfaceData['path_faker'] = trim($interfaceData['path_faker'], '/').U('interface/faker')."/interfaceid/{$id}/".$urlKey;
			} else {
				$interfaceData['path_faker'] = trim($data['host_faker'], '/').U('interface/faker')."/interfaceid/{$id}".'/'.trim($interfaceData['path_faker'], '/').$urlKey;
			}
			
			
			
			
			$this->assign('data', $data);
			$this->assign('interfaceData', $interfaceData);
			$this->assign('interfaceList', $interfaceList);
			$this->assign('httpStatus', C('STATUS_HTTP'));
			$this->display(); 
		}
		
		
	}
	

	
	public function create() {
		if ($this->isPost()) {
			$id = (int)$this->_post('id');
			$data = array();
			$name = $this->_post('name');
			if (!$name) $this->error('请输入接口名称');
			$data['name'] = $name;
			$data['http_type'] = (int)$this->_post('http_type');
			$data['category_id'] = (int)$this->_post('category_id');
			$data['project_id'] = $id;
			$data['detail'] = $this->_post('detail');
			$data['path_product'] = $this->_post('path_product');
			$data['path_develop'] = $this->_post('path_develop');
			$data['path_faker'] = $this->_post('path_faker');
			$data['input_url'] = $this->_post('input_url');
			
			$inputName = $this->_post('input_name');
			$inputSample = $this->_post('input_sample');
			$inputDetail = $this->_post('input_detail');
			
			$inputJson = array();
			
			if ($inputName[0]) {
				foreach ($inputName as $k => $v) {
					$inputJson[$k]['name'] = $v;
					$inputJson[$k]['sample'] = $inputSample[$k];
					$inputJson[$k]['detail'] = $inputDetail[$k];
				}
			}
			
			$data['input_json'] = json_encode($inputJson);
			$data['success_url'] = $this->_post('success_url');
			$data['output_success'] = $this->_post('output_success');
			
			
			$successJson = array();
			$output_success_name = $this->_post('output_success_name');
			$output_success_sample = $this->_post('output_success_sample');
			$output_success_detail = $this->_post('output_success_detail');
			$output_success_type = $this->_post('output_success_type');
			
			if ($output_success_name[0]) {
				foreach ($output_success_name as $k => $v) {
					$successJson[$k]['name'] = $v;
					$successJson[$k]['sample'] = $output_success_sample[$k];
					$successJson[$k]['detail'] = $output_success_detail[$k];
					$successJson[$k]['type'] = $output_success_type[$k];
				}
			}
			
			$data['success_json'] = json_encode($successJson);
			
			$data['fail_url'] = $this->_post('fail_url');
			$data['output_fail'] = $this->_post('output_fail');
			
			$failJson = array();
			$output_fail_name = $this->_post('output_fail_name');
			$output_fail_sample = $this->_post('output_fail_sample');
			$output_fail_detail = $this->_post('output_fail_detail');
			$output_fail_type = $this->_post('output_fail_type');
			
			if ($output_fail_name[0]) {
				foreach ($output_fail_name as $k => $v) {
					$failJson[$k]['name'] = $v;
					$failJson[$k]['sample'] = $output_fail_sample[$k];
					$failJson[$k]['detail'] = $output_fail_detail[$k];
					$failJson[$k]['type'] = $output_fail_type[$k];
				}
			}
			
			$data['fail_json'] = json_encode($failJson);
			$data['created_at'] = time();
			$data['updated_at'] = time();
			
			if ($this->interface->create($data)) {
				if ($interfaceId = $this->interface->add($data)) {
					$this->redirect('/interface/detail?id='.$interfaceId);
				} else {
					$this->error($this->interface->getError());
				}
			}
			
		}
		
		$projectId = (int)$this->_get('projectid');
		if ($projectId) {
			$projectData = $this->project->find($projectId);
			if (!$projectData) $this->error('项目不存在');
			
			$categoryList = $this->category->where('project_id='.$projectId)->field('id,name')->select();
			$categoryData = array();
			foreach ($categoryList as $k => $v) {
				$categoryData[$v['id']] = $v['name'];
			}

			$this->assign('httpStatus', C('STATUS_HTTP'));
			$this->assign('projectData', $projectData);
			$this->assign('categoryData', $categoryData);
			$this->display();
		}
	}
	
	public function edit() {
		if ($this->isPost()) {

			$id = (int)$this->_post('id');
			$originData = $this->interface->find($id);
			if (!$originData) $this->error('数据不存在');
			
			$data = array();
			$name = $this->_post('name');
			if (!$name) $this->error('请输入接口名称');
			$data['name'] = $name;
			$data['http_type'] = (int)$this->_post('http_type');
			$data['category_id'] = (int)$this->_post('category_id');
			$data['detail'] = $this->_post('detail');
			$data['path_product'] = $this->_post('path_product');
			$data['path_develop'] = $this->_post('path_develop');
			$data['path_faker'] = $this->_post('path_faker');
			$data['input_url'] = $this->_post('input_url');
			
			$inputName = $this->_post('input_name');
			$inputSample = $this->_post('input_sample');
			$inputDetail = $this->_post('input_detail');
				
			$inputJson = array();
			$originData['input_json'] = json_decode($originData['input_json'], true);
			$originInput = array();
			foreach ($originData['input_json'] as $k => $v) {
				$originInput[$v['name']] = $v['detail'];
			}
			
			if ($inputName[0]) {
				foreach ($inputName as $k => $v) {
					$inputJson[$k]['name'] = $v;
					$inputJson[$k]['sample'] = $inputSample[$k];
					$inputJson[$k]['detail'] = $inputDetail[$k] ? $inputDetail[$k] : $originInput[$v];
				}
			}
			
			$data['input_json'] = json_encode($inputJson);
			$data['success_url'] = $this->_post('success_url');
			$data['output_success'] = $this->_post('output_success');
			
			$successJson = array();
			$output_success_name = $this->_post('output_success_name');
			$output_success_sample = $this->_post('output_success_sample');
			$output_success_detail = $this->_post('output_success_detail');
			$output_success_type = $this->_post('output_success_type');

			$originData['success_json'] = json_decode($originData['success_json'], true);
			$originSuccess = array();
			foreach ($originData['success_json'] as $k => $v) {
				$originSuccess[$v['name']] = $v['detail'];
			}
			
			if ($output_success_name[0]) {
				foreach ($output_success_name as $k => $v) {
					$successJson[$k]['name'] = $v;
					$successJson[$k]['sample'] = $output_success_sample[$k];
					$successJson[$k]['detail'] = $output_success_detail[$k] ? $output_success_detail[$k] : $originSuccess[$v];
					$successJson[$k]['type'] = $output_success_type[$k];
				}
			}
			
			$data['success_json'] = json_encode($successJson);
				
			$data['fail_url'] = $this->_post('fail_url');
			$data['output_fail'] = $this->_post('output_fail');
				
			$failJson = array();
			$output_fail_name = $this->_post('output_fail_name');
			$output_fail_sample = $this->_post('output_fail_sample');
			$output_fail_detail = $this->_post('output_fail_detail');
			$output_fail_type = $this->_post('output_fail_type');
			
			$originData['fail_json'] = json_decode($originData['fail_json'], true);
			$originFail = array();
			foreach ($originData['fail_json'] as $k => $v) {
				$originFail[$v['name']] = $v['detail'];
			}
			
			if ($output_fail_name[0]) {
				foreach ($output_fail_name as $k => $v) {
					$failJson[$k]['name'] = $v;
					$failJson[$k]['sample'] = $output_fail_sample[$k];
					$failJson[$k]['detail'] = $output_fail_detail[$k] ? $output_fail_detail[$k] : $originFail[$v];
					$failJson[$k]['type'] = $output_fail_type[$k];
				}
			}
			
			$data['fail_json'] = json_encode($failJson);
			$data['updated_at'] = time();

			$this->interface->where('id='.$id)->save($data);

			if ($this->_post('isreturn')) {
				$this->redirect('/interface/edit?id='.$id);
			} else {
				$this->redirect('/interface/detail?id='.$id);
			}
			
			
			
		}
		
		$id = (int)$this->_get('id');
		if ($id) {
			$data = $this->interface->find($id);
			if (!$data) $this->error('数据不存在');
			
			$projectData = $this->project->find($data['project_id']);
			if (!$projectData) $this->error('项目不存在');
			
			$categoryList = $this->category->where('project_id='.$data['project_id'])->field('id,name')->select();
			$categoryData = array();
			foreach ($categoryList as $k => $v) {
				$categoryData[$v['id']] = $v['name'];
			}
			
			$data['input_json'] = json_decode($data['input_json'], true);
			$data['success_json'] = json_decode($data['success_json'], true);
			$data['fail_json'] = json_decode($data['fail_json'], true);
			
			$this->assign('data', $data);
			$this->assign('httpStatus', C('STATUS_HTTP'));
			$this->assign('projectData', $projectData);
			$this->assign('categoryData', $categoryData);
			$this->display();
		}
	}
	
	public function del() {
		$id = (int)$this->_get('id');
		if ($id) {
			$projectId = $this->interface->where('id='.$id)->getField('project_id');
			
			$this->interface->where('id='.$id)->delete();
			$this->redirect('/interface/index?projectid='.$projectId);
		}
	}
	
	public function refresh() {
		if ($this->isAjax()) {
			$url = $this->_get('url');
			if ($url) {
				$data= file_get_contents($url);
				echo $data;
				exit;
			}
		}
	}
	
	public function faker() {
		$id = (int)$this->_get('interfaceid');
		if ($id) {
			$out = $this->interface->where('id='.$id)->getField('output_success');
			echo $out;
			exit();
		}
	}
	
}