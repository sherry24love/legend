<?php
namespace Sherry\Shop\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

use Illuminate\Routing\Controller;

use Sherry\Shop\Models\Category ;

use Encore\Admin\Controllers\ModelForm ;

class CategoryController extends Controller
{
	use ModelForm;
	
	public function index() {
		
		return Admin::content(function(Content $content ){
			$content->header(trans('shop::lang.category'));
			$content->description(trans('admin::lang.list'));
			
			$content->row(function (Row $row) {
				$row->column(5, $this->treeView()->render());
				
				$row->column(7, function (Column $column) {
					$form = new \Encore\Admin\Widgets\Form();
					$form->action(admin_url('shop/category'));
				
					$form->select('parent_id', trans('admin::lang.parent_id'))->options(Category::selectOptions());
					$form->text('name', trans('shop::lang.category'))->rules('required');
					$form->text('keyword', trans('shop::lang.keyword'));
					$form->text('description', trans('shop::lang.description'));
					$form->image('cover', trans('shop::lang.cover'))->help('请上传图片');
					
				
					$column->append((new Box(trans('admin::lang.new'), $form))->style('success'));
				});
			});
			
		});
	}
	
	/**
	 * Edit interface.
	 *
	 * @param string $id
	 *
	 * @return Content
	 */
	public function edit($id) {
		return Admin::content(function (Content $content) use ($id) {
			$content->header(trans('shop::lang.category'));
			$content->description(trans('admin::lang.edit'));
	
			$content->row($this->form()->edit($id));
		});
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	public function form() {
		return Category::form(function (Form $form) {
			$form->display('id', 'ID');
	
			$form->select('parent_id', trans('admin::lang.parent_id'))->options(Category::selectOptions());
			$form->text('name', trans('shop::lang.category'))->rules('required');
			$form->text('keyword', trans('shop::lang.keyword'));
			$form->text('description', trans('shop::lang.description'));
			$form->image('cover', trans('shop::lang.cover'))->rules('required')->help('请上传图片');
			
			$form->display('created_at', trans('admin::lang.created_at'));
			$form->display('updated_at', trans('admin::lang.updated_at'));
		});
	}
	
	/**
	 * @return \Encore\Admin\Tree
	 */
	protected function treeView()
	{
		return Category::tree(function (Tree $tree) {
			$tree->disableCreate();
	
			$tree->branch(function ($branch) {
				$payload = "<strong>{$branch['name']}</strong>";
				/**
				if (!isset($branch['children'])) {
					$uri = admin_url($branch['uri']);
	
					$payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
				}
				**/
	
				return $payload;
			});
		});
	}
	
	/**
	 * Redirect to edit page.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function show($id)
	{
		return redirect()->action(
				'\Sherry\Shop\Controllers\CategoryController@edit', ['id' => $id]
				);
	}
}