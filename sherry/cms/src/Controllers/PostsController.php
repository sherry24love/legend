<?php
namespace Sherry\Cms\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;

use Illuminate\Routing\Controller;

use Sherry\Cms\Models\Category ;
use Sherry\Cms\Models\Posts ;

use Encore\Admin\Controllers\ModelForm ;

class PostsController extends Controller {
	use ModelForm;
	
	public function index() {
		
		return Admin::content(function (Content $content) {
			$content->header(trans('cms::lang.posts'));
			$content->description(trans('admin::lang.list'));
			$content->body($this->grid()->render());
		});
	}
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		return Admin::grid( Posts::class, function (Grid $grid) {
			$grid->id('ID')->sortable();
			$grid->title(trans('cms::lang.title'));
			$grid->column('category.name' , trans('cms::lang.category'));
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$grid->is_hot( trans('cms::lang.is_hot' ) )->switch($states);
			$grid->is_recom( trans('cms::lang.is_recom' ) )->switch($states);
			$grid->is_top( trans('cms::lang.is_top' ) )->switch($states);
			$grid->is_pic( trans('cms::lang.is_pic' ) )->switch($states);
			$grid->created_at(trans('admin::lang.created_at'));
			$grid->updated_at(trans('admin::lang.updated_at'));
			$grid->filter(function ($filter) {
				$filter->like('title', trans('cms::lang.title'));
				//$filter->equal('category_id' , trans('cms::lang.category') );
			});
			$grid->disableExport();
		});
	}
	
	/**
	 * 新增
	 */
	public function create() {
		return Admin::content(function (Content $content) {
			$content->header(trans('cms::lang.posts'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form());
		});
	}
	
	/**
	 * 修改单页面的内容
	 */
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
			$content->header(trans('cms::lang.posts'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::form( Posts::class, function ( Form $form) {
			$form->display('id', 'ID');
		
			$form->text('title', trans('cms::lang.title'))->rules('required');
			$form->select('category_id', trans('cms::lang.category'))->options(function(){
				$cate = new Category();
				return $cate->selectTree();
			})->help( trans('cms::lang.category_help'));
			$form->text('author', trans('cms::lang.author'));
			$form->text('keyword', trans('cms::lang.keyword'));
			$form->text('description', trans('cms::lang.description'));
			$form->image('cover', trans('cms::lang.cover'));
			$form->ueditor('content', trans('cms::lang.content'))->rules('required');
			$form->text('link' , '链接' );
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$form->switch( 'is_hot' , trans('cms::lang.is_hot'))->states( $states );
			$form->switch( 'is_recom' , trans('cms::lang.is_recom'))->states( $states );
			$form->switch( 'is_top' , trans('cms::lang.is_top'))->states( $states );
			$form->switch( 'is_pic' , trans('cms::lang.is_pic'))->states( $states );
				
			$form->display('created_at', trans('admin::lang.created_at'));
			$form->display('updated_at', trans('admin::lang.updated_at'));
			
			$form->saving( function( $form ){
				$form->model()->link = request()->input('link' , '' );
			});
		});
	}
}