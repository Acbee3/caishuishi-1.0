<?php
//代理记账后台功能路由写在此文件中

/**
 * 无访问权限
 */
Route::get('/forbidden', 'ForbiddenController@index')->name('forbidden');

//Route::get('login/login', 'LoginController@loginForm')->name('login');旧版登录
Route::get('login/login', 'LoginController@loginForm')->name('login');

Route::get('login/logout', 'LoginController@logout')->name('logout');

Route::post('login/login', 'LoginController@login')->name('login');


// CFS业务中心
Route::get('/home', 'HomeController@index')->name('home');


/**
 * CFS管理中心
 */
/*Route::group(['prefix' => 'cfs', 'middleware' => ['auth','checkpriv'], 'namespace' => 'System'], function () {

});*/

Route::any("login/test", "LoginController@test");

Route::group(['middleware' => ['agent', 'checkpriv']], function () {

    //Route::get('/login/logout', 'LoginController@Logout')->name('agent.logout');

    //测试路由

    Route::get('/', 'DashboardController@dashboard')->name('agent');
    Route::get('/system', 'DashboardController@dashboard')->name('agent');
    Route::get('/api/agent_header', 'DashboardController@dashboard_agent_header')->name('agent_header');

    /*
     * 系统设置　用户管理
     */
    //Route::resource('/users', 'UsersController');
    Route::get('/users', 'UsersController@index')->name('agent.users');
    Route::any('/users/create', 'UsersController@create')->name('agent.users.create');
    Route::any('/users/edit', 'UsersController@edit')->name('agent.users.edit');

    /*
     * 系统设置　角色管理
     */
    Route::get('/roles', 'RolesController@index')->name('agent.roles');
    Route::any('/roles/create', 'RolesController@create')->name('agent.roles.create');
    Route::any('/roles/edit', 'RolesController@edit')->name('agent.roles.edit');

    /*
     * 系统设置　权限管理
     */
    Route::get('/rolelists', 'RolelistsController@index')->name('agent.rolelists');
    Route::any('/rolelists/create', 'RolelistsController@create')->name('agent.rolelists.create');
    Route::any('/rolelists/edit', 'RolelistsController@edit')->name('agent.rolelists.edit');
    Route::any('/rolelists/addchild', 'RolelistsController@addchild')->name('agent.rolelists.addchild');
    Route::any('/rolelists/createsession', 'RolelistsController@createsession')->name('agent.rolelists.createsession');
    Route::any('/rolelists/updatesession', 'RolelistsController@updatesession')->name('agent.rolelists.updatesession');

    /*
     * 系统设置　菜单管理
     */
    Route::get('/menus', 'MenusController@index')->name('agent.menus');
    Route::any('/menus/create', 'MenusController@create')->name('agent.menus.create');
    Route::any('/menus/edit', 'MenusController@edit')->name('agent.menus.edit');

    /*
     * 系统设置　菜单路由管理
     */
    Route::get('/menuactions', 'MenuactionsController@index')->name('agent.menuactions');
    Route::any('/menuactions/create', 'MenuactionsController@create')->name('agent.menuactions.create');
    Route::any('/menuactions/edit', 'MenuactionsController@edit')->name('agent.menuactions.edit');

    /*
     * 基础设置　角色权限管理
     */
    Route::get('/rolerelations', 'RolerelationsController@index')->name('agent.rolerelations');
    Route::any('/rolerelations/operation', 'RolerelationsController@operation')->name('agent.rolerelations.operation');
    //Route::any('/rolerelations/addrole', 'RolerelationsController@addrole')->name('agent.rolerelations.addrole');
    Route::any('/rolerelations/edit', 'RolerelationsController@edit')->name('agent.rolerelations.edit');
    Route::any('/rolerelations/del', 'RolerelationsController@del')->name('agent.rolerelations.del');
    Route::any('/rolerelations/changepermission', 'RolerelationsController@changepermission')->name('agent.rolerelations.changepermission');
    Route::any('/rolerelations/addrolenew', 'RolerelationsController@addrolenew')->name('agent.rolerelations.addrolenew');

    /*
     * 基础设置　客户授权管理
     */
    Route::get('/authorizations', 'AuthorizationsController@index')->name('agent.authorizations');
    Route::get('/authorizations/lists', 'AuthorizationsController@lists')->name('agent.authorizations.lists');
    Route::any('/authorizations/authusers', 'AuthorizationsController@authusers')->name('agent.authorizations.authusers');
    Route::any('/authorizations/getagentusers', 'AuthorizationsController@getagentusers')->name('agent.authorizations.getagentusers');


    /*
     * 客户信息管理
     */
    Route::get('/companies', 'CompanyController@index')->name('agent.companies');
    Route::get('/companies/freezlist', 'CompanyController@index_freez')->name('agent.companies.freezlist');
    Route::any('/companies/create', 'CompanyController@create')->name('agent.companies.create');
    Route::any('/companies/edit', 'CompanyController@edit')->name('agent.companies.edit');
    //Route::any('/companies/edit_new', 'CompanyController@edit_new')->name('agent.companies.edit_new');
    Route::post('/companies/api_edit', 'CompanyController@api_edit')->name('agent.companies.api_edit');
    Route::any('/companies/editaccount', 'CompanyController@editaccount')->name('agent.companies.editaccount');
    Route::post('/companies/api_edit_account', 'CompanyController@api_edit_account')->name('agent.companies.api_edit_account');
    Route::get('/companies/view', 'CompanyController@view')->name('agent.companies.view');
    Route::any('/companies/setstatus', 'CompanyController@setstatus')->name('agent.companies.setstatus');
    Route::any('/companies/freez', 'CompanyController@freez')->name('agent.companies.freez');
    Route::any('/companies/unfreez', 'CompanyController@unfreez')->name('agent.companies.unfreez');
    Route::post('/companies/api_del', 'CompanyController@api_del')->name('agent.companies.api_del');


    //部门管理
    Route::any("department/index", "AgentDepartmentController@index");
    Route::any("department/create", "AgentDepartmentController@create");
    Route::any("department/create-user", "AgentDepartmentController@createUser");
    //Route::any("department/edit","AgentDepartmentController@edit");
    Route::any("department/del", "AgentDepartmentController@del");

    //人员管理
    Route::any("user/agent-index", "UserController@agentIndex");
    Route::any("user/agent-create", "UserController@agentCreate");
    Route::any("user/agent-edit", "UserController@agentEdit");
    Route::any("user/agent-del", "UserController@agentDel");
    Route::any("user/change-status", "UserController@changeStatus");

});

