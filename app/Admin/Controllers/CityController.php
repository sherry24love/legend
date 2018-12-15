<?php

namespace App\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\ModelForm ;
use App\Models\Port ;
use Encore\Admin\Grid\Filter;

class CityController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header( '城市港口' );
            $content->description(trans('admin::lang.list'));
			$content->body( $this->grid() );
        });
    }
    
    protected function grid() {
    	return Admin::grid( Port::class , function (Grid $grid ){
    		$grid->model()->where('parent_id' , 0 );
    		$grid->model()->orderBy('id' , 'desc');
    		$grid->id('ID')->sortable();
    		$grid->name('城市名称');
    		$grid->name_py( '城市拼音');
    		$grid->short_py( '城市拼音首字母');
    		$grid->sort( '排序')->sortable()->editable();
    		$states = [
    				'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
    				'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
    		];
    		$grid->is_recommend( '是否推荐' )->switch($states);
			$grid->disableBatchDeletion ();
			$grid->disableExport ();
			$grid->filter( function( Filter $filter ){
				$filter->disableIdFilter();
				$filter->like('name' , '城市名称');
				$filter->like('name_py' , '城市拼音');
				$filter->like('short_py' , '首字母') ;
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
            '\App\Admin\Controllers\CityController@edit', ['id' => $id]
        );
    }

    
    public function create()
    {
    	return Admin::content(function (Content $content) {
    		$content->header( '城市' );
    		$content->description('新增');
    
    		$content->row($this->form() );
    	});
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header( '城市' );
            $content->description('编辑');

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Port::class ,function (Form $form) {
			$form->text('name', '城市名称')->rules('required');
            $form->text('name_py', '英文名')->rules('required');
            $form->text('short_py', '英文简写')->rules('required');
            $form->number('sort' , '排序')->default( 50 );
            $states = [
            		'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
            		'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
            ];
            $form->switch('is_recommend' , '推荐' )->states( $states );
            $form->saving( function( $form){
            	$form->model()->parent_id = 0 ;
            });
        });
    }

}
