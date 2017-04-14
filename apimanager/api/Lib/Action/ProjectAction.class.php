<?php

class ProjectAction extends Action {
	
	public function _initialize(){
		$this->project = M('project');
	}
	
	public function index() {
		$id = (int)$this->_get('id');
		if ($id) {
			$data = $this->project->find($id);
			if (!$data) $this->error('数据不存在');
			
			$Interface = M('interface');
			$this->assign('count', $Interface->where('project_id='.$id)->count());
			$this->assign('data', $data);
			$this->assign('projectStatus', C('STATUS_PROJECT'));
			$this->display();
		}
	}
	
	public function create(){
		if ($this->isPost()) {
			$data = array();
			$name = $this->_post('name');
			if (!$name) $this->error('请输入项目名称');
			
			$data['name'] = $name;
			$data['status'] = (int)$this->_post('status');
			$data['host_product'] = $this->_post('host_product');
			$data['host_develop'] = $this->_post('host_develop');
			$data['host_faker'] = $this->_post('host_faker');
			$data['detail'] = $this->_post('detail') ? $this->_post('detail') : '';
			$data['created_at'] = time();
			
			if ($this->project->create($data)) {
				if ($this->project->add($data)) {
					$this->redirect('/index');
				} else {
					$this->error($this->project->getError());
				}
			} 
		}
		$this->assign('projectStatus', C('STATUS_PROJECT'));
		$this->display();
	}
	
	
	public function edit() {
		if ($this->isPost()) {
			$id = $this->_post('id');
			$data = array();
			$name = $this->_post('name');
			if (!$name) $this->error('请输入项目名称');
			
			$data['name'] = $name;
			$data['status'] = (int)$this->_post('status');
			$data['host_product'] = $this->_post('host_product');
			$data['host_develop'] = $this->_post('host_develop');
			$data['host_faker'] = $this->_post('host_faker');
			$data['detail'] = $this->_post('detail') ? $this->_post('detail') : '';
			$data['updated_at'] = time();
			
			$this->project->where('id='.$id)->save($data);
			
			$this->redirect('/index');
		}
		
		$id = (int)$this->_get('id');
		if ($id) {
			$data = $this->project->find($id);
			if (!$data) $this->error('数据不存在');
			$this->assign('projectStatus', C('STATUS_PROJECT'));
			$this->assign('data', $data);
			$this->display();
		}
	}
	
	public function del() {
		$id = (int)$this->_get('id');
		if ($id) {
			$this->project->where('id='.$id)->delete();
			$this->redirect('/index');
		}
	}
	
	public function createcategory() {
		if ($this->isPost()) {
			$name = $this->_post('name');
			if (!$name) $this->error('请输入分类名称');
			$projectName = $this->_post('projectname');
			if (!$projectName) $this->error('请输入项目名称');
			
			$projectId = $this->project->where("name='".$projectName."'")->getField('id');
			if (!$projectId) $this->error('项目不存在');
			
			$Category = M('category');
			$data = array();
			$data['name'] = $name;
			$data['project_id'] = $projectId;
			
			if ($Category->create($data)) {
				if ($Category->add($data)) {
					$this->redirect('/project/index?id='.$projectId);
				} else {
					$this->error($this->project->getError());
				}
			}
		}
		
		$id = (int)$this->_get('projectid');
		if ($id) {
			$data = $this->project->find($id);
			if (!$data) $this->error('数据不存在');			
			$this->assign('data', $data);
			$this->display();
		}
	}
}