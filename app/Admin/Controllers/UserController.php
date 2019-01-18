<?php

namespace App\Admin\Controllers;

use App\Model\UserModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
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
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserModel);

        $grid->user_id('User id');
        $grid->user_name('User name');
        $grid->user_pwd('User pwd');
        $grid->user_tel('User tel');
        $grid->user_optins('User optins');
        $grid->register_time('Register time');
        $grid->login_time('Login time');
        $grid->login_ip('Login ip');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(UserModel::findOrFail($id));

        $show->user_id('User id');
        $show->user_name('User name');
        $show->user_pwd('User pwd');
        $show->user_tel('User tel');
        $show->user_optins('User optins');
        $show->register_time('Register time');
        $show->login_time('Login time');
        $show->login_ip('Login ip');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserModel);

        $form->number('user_id', 'User id');
        $form->text('user_name', 'User name');
        $form->text('user_pwd', 'User pwd');
        $form->text('user_tel', 'User tel');
        $form->text('user_optins', 'User optins');
        $form->number('register_time', 'Register time');
        $form->number('login_time', 'Login time');
        $form->number('login_ip', 'Login ip');

        return $form;
    }
}
