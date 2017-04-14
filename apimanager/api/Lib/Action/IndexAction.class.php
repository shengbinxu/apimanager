<?php

class IndexAction extends Action {
	
	public function index(){
		$Porject = M('project');
		$Interface = M('interface');
		$list = $Porject->order('id DESC')->select();
		
		foreach ($list as $k => $v) {
			$list[$k]['count'] = $Interface->where('project_id='.$v['id'])->count();
		}
		
		$this->assign('projectStatus', C('STATUS_PROJECT'));
		$this->assign('list', $list);	
		$this->display();
	}
	
}