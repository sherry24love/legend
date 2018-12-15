<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class OrderState extends AbstractTool
{
	public function script()
	{
		$url = Request::fullUrlWithQuery(['tabcate' => '_gender_']);

		return <<<EOT

$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_gender_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
	}

	public function render()
	{
		Admin::script($this->script());

		$options = [
				'all'   => '全部',
				'waitdeal' => '待追踪' ,
				'ontrace' => '追踪中' ,
				'tracedone' => '追踪完成' ,
				'trash'     => '作废',
			//	'10'     => '草稿',
		];

		return view('admin.tools.order', compact('options'));
	}
}