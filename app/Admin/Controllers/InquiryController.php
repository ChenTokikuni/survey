<?php

namespace App\Admin\Controllers;

use App\Models\inquiry;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use App\Helpers\GetData;

class InquiryController extends Controller
{
	
   use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('查询帐号')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('查询帐号')
            ->description('检视')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('查询帐号')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('查询帐号')
            ->description('新建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new inquiry);

		// 關閉選擇器
		$grid->disableRowSelector();
		$grid->disableExport();
		//$grid->disableActions();
		//$grid->disableCreateButton();
		$grid->disableColumnSelector();
		//自訂
		$grid->filter(function($filter){
			$filter->disableIdFilter();	
				// 在这里添加字段过滤器
			$filter->equal('account', '查询帐号')->select(GetData::inquiryOptions());
		});
		// 關閉搜尋
		//$grid->disableFilter(); 
		$grid->actions(function ($actions) {
			
			//$actions->disableEdit();
			
			$actions->disableView();
			
			//$actions->disableDelete();
			
		});
		$grid->tools(function ($tools) {
			$tools->append(new \App\Admin\Actions\ImportInquiryPost);
			$tools->append(new \App\Admin\Actions\TruncateTable('inquiry'));
		});

		$grid->column('account', '查询帐号');
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(inquiry::findOrFail($id));

        $show->setting_id('Setting id');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new inquiry);

		$form->tools(function (Form\Tools $tools) {
			$tools->disableView();
			$tools->disableDelete();
			/*
			$tools->disableList();
			$tools->disableBackButton();
			$tools->disableListButton();
			*/
		});
		
		$form->footer(function ($footer) {

			// 去掉`重置`按钮
			//$footer->disableReset();

			// 去掉`提交`按钮
			//$footer->disableSubmit();

			// 去掉`查看`checkbox
			$footer->disableViewCheck();

			// 去掉`继续编辑`checkbox
			$footer->disableEditingCheck();

			// 去掉`继续创建`checkbox
			$footer->disableCreatingCheck();

		});
		
		$form->text('account', '查询帐号')->rules(function ($form) {
			//all account
			$all = [];
			$rows = \App\Models\inquiry::all();
			foreach ($rows as $row) {
				$all[$row->id] = $row->account;	
			}
			if ($id = $form->model()->id) {
				return "unique:inquiry,account,$id,id";
			}
			if (!$id = $form->model()->id) {
				return 'unique:inquiry,account';
			}
		},['警告:已有相同查询帐号，请重新设定']);
        return $form;
    }
}

