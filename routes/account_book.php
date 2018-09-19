<?php
// 账薄功能的路由写在此文件中

Route::group(['middleware' => ['book']], function () {

    //测试页面
    Route::get('/test', 'InvoiceController@import');
    Route::get('/a', 'TestController@index');


    //进入首页 传入公司ID及公司唯一加密识别码
    Route::get('/home/{id}/{company_encode}/{fiscal_period?}', 'HomeController@index')->name('book.home');
    Route::get('/home/periodList', 'HomeController@periodList');//会计期间列表api

    //会计科目
    Route::post('/account_subject/freeze', 'AccountSubjectController@freeze')->name('account_subject.freeze');
    Route::resource('/account_subject', 'AccountSubjectController');
    //业务数据
    Route::post('/bussinessdata/del', 'BussinessdataController@del')->name('bussinessdata.del');
    Route::post('/bussinessdata/freeze', 'BussinessdataController@freeze')->name('bussinessdata.freeze');
    Route::resource('/bussinessdata', 'BussinessdataController');
    //银行账户
    Route::post('/bankaccount/del', 'BankAccountController@del')->name('bankaccount.del');
    Route::post('/bankaccount/freeze', 'BankAccountController@freeze')->name('bankaccount.freeze');
    Route::resource('/bankaccount', 'BankAccountController');

    //发票列表
    Route::get('/invoice/import', 'InvoiceController@import');
    Route::get('/invoice/export', 'InvoiceController@export');
    Route::get('/invoice/importExcel', 'InvoiceController@importExcel');
    Route::get('/invoice/exportExcel', 'InvoiceController@exportExcel');
    Route::get('/invoice/summary', 'InvoiceController@summary');

    //发票新增
    Route::get('/invoice/addImport', 'InvoiceController@addImport');
    Route::get('/invoice/addExport', 'InvoiceController@addExport');

    //发票编辑
    Route::get('/invoice/editImport/{id}', 'InvoiceController@editImport');
    Route::get('/invoice/editExport/{id}', 'InvoiceController@editExport');

    //发票 api
    Route::get('/invoice/detail', 'InvoiceController@detail');
    Route::post('/invoice/delete', 'InvoiceController@delete');
    Route::post('/invoice/create', 'InvoiceController@create');
    Route::post('/invoice/update', 'InvoiceController@update');
    Route::post('/invoice/deleteAll', 'InvoiceController@deleteAll');

    //费用
    Route::get('/cost/index', 'CostController@index'); //列表
    Route::post('/cost/importExcel', 'CostController@importExcel');//导入excel

    //费用api
    Route::post('/cost/delete', 'CostController@delete');
    Route::post('/cost/deleteItem', 'CostController@deleteItem');
    Route::post('/cost/deleteAll', 'CostController@deleteAll');
    Route::post('/cost/update', 'CostController@update');
    Route::post('/cost/add', 'CostController@add');

    //凭证
    Route::get('/voucher/add', 'VoucherController@add');

    //资金
    Route::post('/fund/del', 'FundController@del')->name('fund.del');
    Route::get('/fund/banklist', 'FundController@bankList')->name('fund.banklist'); //银行首页
    Route::get('/fund/convert', 'FundController@convert')->name('fund.convert'); //发票信息转化为资金信息
    Route::post('/fund/newBank', 'FundController@newBank')->name('fund.newbank');   //银行新增
    Route::get('/fund/bankFundCount', 'FundController@bankFundCount')->name('fund.bankFundCount');//银行收入支出统计
    Route::post('/fund/delBank', 'FundController@delBank')->name('fund.delbank');   //银行删除
    Route::post('/fund/delBankItem', 'FundController@delBankItem')->name('fund.delbankitem'); //银行删除(item)
    Route::get('/fund/ywlxList', 'FundController@ywlxList')->name('fund.ywlxList'); //业务类型
    Route::resource('/fund', 'FundController');

    //资产
    Route::get('/assetalter/assetAlterList', 'AssetAlterController@assetAlterList')->name('assetalert.assetAlterList'); //资产变动列表
    Route::get('/assetalter/getAssetBdlx', 'AssetAlterController@getAssetBdlx')->name('assetalter.getAssetBdlx'); //获取变动类型
    Route::get('/asset/type', 'AssetController@getAssetType')->name('asset.type');   //获取资产类型
    Route::get('/asset/getAssetZclb', 'AssetController@getAssetZclb')->name('asset.getAssetZclb');   //获取资产类别
    Route::get('/asset/getAssetList', 'AssetController@getAssetList')->name('asset.getAssetList');   //折旧摊销列表
    Route::post('/asset/storeAsset', 'AssetController@storeAsset')->name('asset.storeAsset'); //新增 更新 折旧摊销
    Route::post('/asset/delAsset', 'AssetController@delAsset')->name('asset.delAsset'); //折旧摊销删除

    //薪酬部门模块
    Route::post('/department/api_add', 'DepartmentController@api_add')->name('department.api_add');
    Route::post('/department/api_edit', 'DepartmentController@api_edit')->name('department.api_edit');
    Route::post('/department/api_del', 'DepartmentController@api_del')->name('department.api_del');
    Route::any('/department/search', 'DepartmentController@search')->name('department.search');
    Route::resource('/department', 'DepartmentController');

    //薪酬员工模块
    Route::post('/employee/list', 'EmployeeController@list')->name('employee.list');
    Route::post('/employee/api_del', 'EmployeeController@api_del')->name('employee.api_del');
    Route::post('/employee/api_del_ids', 'EmployeeController@api_del_ids')->name('employee.api_del_ids');
    Route::post('/employee/api_import', 'EmployeeController@api_import')->name('employee.api_import');
    Route::any('/employee/api_export', 'EmployeeController@api_export')->name('employee.api_export');
    Route::post('/employee/api_save_add', 'EmployeeController@api_save_add')->name('employee.api_save_add');
    Route::post('/employee/api_change_status', 'EmployeeController@api_change_status')->name('employee.api_change_status');
    Route::post('/employee/api_change_department', 'EmployeeController@api_change_department')->name('employee.api_change_department');
    Route::post('/employee/api_get_department', 'EmployeeController@api_get_department')->name('employee.api_get_department');

    Route::any('/employee/import', 'EmployeeController@import')->name('employee.import');
    Route::get('/employee/export', 'EmployeeController@export')->name('employee.export');
    Route::resource('/employee', 'EmployeeController');

    //薪酬表模块
    Route::get('/salary/list', 'SalaryController@list')->name('salary.list');
    //Route::any('/salary/add', 'SalaryController@add')->name('salary.add');
    Route::post('/salary/api_get_info', 'SalaryController@api_get_info')->name('salary.api_get_info');
    Route::post('/salary/api_add_salary', 'SalaryController@api_add_salary')->name('salary.api_add_salary');
    Route::post('/salary/api_get_salary', 'SalaryController@api_get_salary')->name('salary.api_get_salary');
    Route::post('/salary/api_del', 'SalaryController@api_del')->name('salary.api_del');
    Route::post('/salary/api_create_voucher', 'SalaryController@api_create_voucher')->name('salary.api_create_voucher');
    Route::post('/salary/api_copy_salary', 'SalaryController@api_copy_salary')->name('salary.api_copy_salary');
    Route::get('/salary/salary_km_config', 'SalaryController@salary_km_config')->name('salary.km_config');
    Route::post('/salary/api_account_list', 'SalaryController@api_account_list')->name('salary.api_account_list');
    Route::post('/salary/api_save_config', 'SalaryController@api_save_config')->name('salary.api_save_config');
    Route::post('/salary/api_del_cost_config', 'SalaryController@api_del_cost_config')->name('salary.api_del_cost_config');
    Route::post('/salary/api_copy_salary_bill', 'SalaryController@api_copy_salary_bill')->name('salary.api_copy_salary_bill');
    Route::post('/salary/api_auto_config', 'SalaryController@api_auto_config')->name('salary.api_auto_config');

    Route::post('/salary/api_get_link', 'SalaryController@api_get_link')->name('salary.api_get_link');
    Route::get('/salary/list_a', 'SalaryController@list_a')->name('salary.list_a');
    Route::get('/salary/list_b', 'SalaryController@list_b')->name('salary.list_b');
    Route::get('/salary/list_c', 'SalaryController@list_c')->name('salary.list_c');
    Route::get('/salary/list_d', 'SalaryController@list_d')->name('salary.list_d');
    Route::get('/salary/list_e', 'SalaryController@list_e')->name('salary.list_e');
    Route::get('/salary/list_f', 'SalaryController@list_f')->name('salary.list_f');
    Route::get('/salary/list_g', 'SalaryController@list_g')->name('salary.list_g');
    Route::get('/salary/list_h', 'SalaryController@list_h')->name('salary.list_h');

    Route::post('/salary/api_get_employee', 'SalaryController@api_get_employee')->name('salary.api_get_employee');
    Route::post('/salary/api_save_salary', 'SalaryController@api_save_salary')->name('salary.api_save_salary');
    Route::post('/salary/api_del_salary', 'SalaryController@api_del_salary')->name('salary.api_del_salary');

    Route::post('/salary/api_salary_a', 'SalaryController@api_get_salary_a')->name('salary.api_salary_a');
    Route::post('/salary/api_salary_b', 'SalaryController@api_get_salary_b')->name('salary.api_salary_b');
    Route::post('/salary/api_salary_c', 'SalaryController@api_get_salary_c')->name('salary.api_salary_c');
    Route::post('/salary/api_salary_d', 'SalaryController@api_get_salary_d')->name('salary.api_salary_d');
    Route::post('/salary/api_salary_e', 'SalaryController@api_get_salary_e')->name('salary.api_salary_e');

    Route::get('/salary/export_a', 'SalaryController@export_a')->name('salary.export_a');
    Route::post('/salary/make_voucher', 'SalaryController@make_voucher')->name('salary.make_voucher');
    //Route::resource('/salary', 'SalaryController');

    //凭证模块
    Route::get("/voucher/index", "VoucherController@index")->name('voucher.index');
    Route::post("/voucher/make", "VoucherController@make")->name('voucher.make');
    Route::post("/voucher/audit", "VoucherController@audit")->name('voucher.audit');
    Route::post("/voucher/del", "VoucherController@del")->name('voucher.del');
    Route::get("/voucher/add", "VoucherController@add")->name('voucher.add');
    Route::get("/voucher/addEditor", "VoucherController@addEditor")->name('voucher.addEditor');
    Route::get("/voucher/addKeep", "VoucherController@addKeep")->name('voucher.addKeep');
    Route::get("/voucher/invoiceSh", "VoucherController@invoiceSh")->name('voucher.invoiceSh');
    Route::post("/voucher/preview", "VoucherController@preview")->name('voucher.preview');
    Route::get("/voucher/edit", "VoucherController@edit")->name('voucher.edit');// 凭证编辑页面 主供薪酬使用
    Route::post('/voucher/api_get_voucher', 'VoucherController@api_get_voucher')->name('voucher.api_get_voucher');
    Route::post('/voucher/api_get_simple_voucher', 'VoucherController@api_get_simple_voucher')->name('voucher.api_get_simple_voucher');
    Route::get('/voucher/pdf', 'VoucherController@pdf')->name('voucher.pdf');


    //财务处理下的账簿
    Route::get("/accountBook/index", "AccountBookController@index")->name('accountBook.index');
    Route::get("/accountBook/ledger", "AccountBookController@ledger")->name('accountBook.ledger');
    Route::get("/accountBook/mxz", "AccountBookController@mxz")->name('accountBook.mxz');

    //账簿 之 明细账
    Route::get('/sub_ledger/list', 'SubsidiaryLedgerController@list')->name('sub_ledger.list');//明细账
    Route::post('/sub_ledger/api_list', 'SubsidiaryLedgerController@api_list')->name('sub_ledger.api_list');// 页面加载时请求、返回数据
    Route::post('/sub_ledger/api_get_list', 'SubsidiaryLedgerController@api_get_list')->name('sub_ledger.api_get_list');//明细账主体区域数据
    Route::post('/sub_ledger/api_get_id', 'SubsidiaryLedgerController@api_get_id')->name('sub_ledger.api_get_id');// 取选中树形ID
    Route::get('/sub_ledger/print', 'SubsidiaryLedgerController@print')->name('sub_ledger.print');
    Route::get('/sub_ledger/print_all', 'SubsidiaryLedgerController@print_all')->name('sub_ledger.print_all');


    //账薄 之 总账
    Route::get('/ledger/list', 'LedgerController@list')->name('ledger.list');// 总账
    Route::post('/ledger/api_list', 'LedgerController@api_list')->name('ledger.api_list');// 页面加载 返回数据
    Route::post('/ledger/api_change_list', 'LedgerController@api_change_list')->name('ledger.api_change_list');// 确定刷新数据
    Route::get('/ledger/export', 'LedgerController@export')->name('ledger.export');
    Route::get('/ledger/print', 'LedgerController@print')->name('ledger.print');


    //科目余额
    Route::get('/subjectBalance/subjectBalanceList', 'SubjectBalanceController@subjectBalanceList')->name('subjectBalance.subjectBalanceList');
    Route::post('/subjectBalance/subjectBalanceEdit', 'SubjectBalanceController@subjectBalanceEdit')->name('subjectBalance.subjectBalanceEdit');
    Route::get('/subjectBalance/subjectBalanceFirst', 'SubjectBalanceController@subjectBalanceFirst')->name('subjectBalance.subjectBalanceFirst');
    //初始科目余额表 条件判断
    Route::post('/subjectBalance/checkInit', 'SubjectBalanceController@checkInit')->name('subjectBalance.checkInit');
    //建账导入科目余额初始数据
    Route::post('/subjectBalance/import', 'SubjectBalanceController@import')->name('subjectBalance.import');

    Route::get('/begindata/finance', function () {
        return view('book.initData.account');
    });

    Route::get('/book/pzhz', function () {
        return view('book.bookAccount.pzhz');
    });
    Route::get('/book/rijizhang', function () {
        return view('book.bookAccount.rijizhang');
    });
    Route::get('/book/kjbb', function () {
        return view('book.kjbb.kjbb');
    });
    Route::get('/checkout', function () {
        return view('book.checkout.checkout');
    });

    //结账模块
    Route::get('/taxConfig/list', 'TaxConfigController@list');
    Route::post('/accountClose/check', 'AccountCloseController@check');
    Route::post('/accountClose/makeVoucherByQingdan', 'AccountCloseController@makeVoucherByQingdan');//批量生成清单凭证
    Route::post('/accountClose/makeVoucherByTax', 'AccountCloseController@makeVoucherByTax');//计提税金
    Route::post('/accountClose/makeVoucherBySunyi', 'AccountCloseController@makeVoucherBySunyi');//损益结转
    Route::post('/accountClose/deleteJitiVoucher', 'AccountCloseController@deleteJitiVoucher');//删除结账凭证
    Route::post('/accountClose/run', 'AccountCloseController@run');//删除结账凭证
    Route::post('/accountClose/checkClose', 'AccountCloseController@checkClose');//检查当期是否结账
    Route::post('/accountClose/reverse', 'AccountCloseController@reverse');//反结账
    Route::post('/taxConfig/save', 'TaxConfigController@save');

    //资产负债表
    Route::get('/balanceSheet/index', 'BalanceSheetController@index');
    Route::get('/profitSheet/index', 'ProfitSheetController@index');


});

