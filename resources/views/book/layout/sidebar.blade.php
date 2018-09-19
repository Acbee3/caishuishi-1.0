<aside class="Hui-aside">
    <div>
        <div class="menu_dropdown bk_2">
            <div class="sliderTop">
                <svg class="icon iconfont" aria-hidden="true">
                    <use xlink:href="#icon-qingdan"></use>
                </svg>
                <span>清单处理</span>
            </div>
            <dl id="menu-article" class="nav-item">
                <dt><a href="javascript:;" class="options">发票<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href="{{ url('/book/invoice/import')  }}" data-title="进项" href="javascript:void(0)">进项</a></li>
                        <li><a data-href="{{ url('/book/invoice/export')  }}" data-title="销项" href="javascript:void(0)">销项</a></li>
                        <li><a data-href="{{ url('/book/cost/index')  }}" data-title="费用" href="javascript:void(0)">费用</a></li>
                    </ul>
                </dd>
            </dl>
            <dl id="capital" class="nav-item">
                <dt><a href="javascript:;" class="options">资金<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href={{ route('fund.index', ['channel_type' => 1]) }} data-title="银行" href="javascript:void(0)">银行</a></li>
                        <li><a data-href={{ route('fund.index', ['channel_type' => 2]) }} data-title="现金" href="javascript:void(0)">现金</a></li>
                        <li><a data-href={{ route('fund.index', ['channel_type' => 3]) }} data-title="票据" href="javascript:void(0)">票据</a></li>
                    </ul>
                </dd>
            </dl>
            <dl id="paid" class="nav-item">
                <dt><a href="javascript:;" class="options">薪酬<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href="{{ route('department.index') }}" data-title="员工" href="javascript:void(0)">员工</a></li>
                        <li><a data-href="{{ route('salary.list') }}" data-title="薪酬表" href="javascript:void(0)">薪酬表</a></li>
                    </ul>
                </dd>
            </dl>
            <dl id="asset" class="nav-item">
                <dt><a href="javascript:;" class="options">资产<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href="{{ route('asset.getAssetList') }}" data-title="折旧摊销" href="javascript:void(0)">折旧摊销</a></li>
                        <li><a data-href="{{ route('assetalert.assetAlterList') }}" data-title="资产变动" href="javascript:void(0)">资产变动</a></li>
                    </ul>
                </dd>
            </dl>
        </div>
        <div class="menu_dropdown bk_2">
            <div class="sliderTop">
                {{--<i class="icon icon1 iconfont icon-caiwuicon"></i>--}}
                <svg class="icon iconfont" aria-hidden="true">
                    <use xlink:href="#icon-caiwuicon"></use>
                </svg>
                <span>财务处理</span>
            </div>
            <dl id="certificates" class="sliderItem">
                <dt><a data-href="{{ route('voucher.index') }}" data-title="凭证" href="javascript:void(0)">凭证</a></dt>
            </dl>
            <dl id="accountBook" class="nav-item">
                <dt><a href="javascript:;" class="options">账簿<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href="{{ route('subjectBalance.subjectBalanceList') }}" data-title="科目余额" href="javascript:void(0)">科目余额</a></li>
                        <li><a data-href="{{ route('ledger.list') }}" data-title="总账" href="javascript:void(0)">总账</a></li>
                        <li><a data-href="{{ route('sub_ledger.list') }}" data-title="明细账" href="javascript:void(0)">明细账</a></li>
                        {{--<li><a data-href="charts-2.html" data-title="凭证汇总" href="javascript:void(0)">凭证汇总</a></li>--}}
                        {{--<li><a data-href="charts-2.html" data-title="日记账" href="javascript:void(0)">日记账</a></li>--}}
                        {{--<li><a data-href="{{ url('/book/book/pzhz') }}" data-title="凭证汇总" href="javascript:void(0)">凭证汇总</a></li>--}}
                        {{--<li><a data-href="{{ url('/book/book/rijizhang') }}" data-title="日记账" href="javascript:void(0)">日记账</a></li>--}}
                    </ul>
                </dd>
            </dl>
            <dl id="voucher" class="sliderItem">
                <dt><a data-href="{{ url('/book/checkout') }}" data-title="结账" href="javascript:void(0)">结账</a></dt>
            </dl>
        </div>
        <div class="menu_dropdown bk_2">
            <div class="sliderTop">
                {{--<i class="icon icon1 iconfont icon-tongjiicon"></i>--}}
                <svg class="icon iconfont" aria-hidden="true">
                    <use xlink:href="#icon-tongjiicon"></use>
                </svg>
                <span>统计报表</span>
            </div>
            <dl id="accountingReports" class="sliderItem">
                <dt><a data-href="{{ url('/book/book/kjbb') }}" data-title="会计报表" href="javascript:void(0)">会计报表</a></dt>
            </dl>
        </div>
        <div class="menu_dropdown bk_2">
            <div class="sliderTop">
                {{--<i class="icon icon1 iconfont icon-shezhi1"></i>--}}
                <svg class="icon iconfont" aria-hidden="true">
                    <use xlink:href="#icon-shezhi"></use>
                </svg>
                <span>基础设置</span>
            </div>
            <dl id="companyInfo" class="sliderItem">
                <dt><a data-href={{ route('agent.companies.edit',['f'=>'book','id'=>\App\Entity\Company::sessionCompany()->id]) }} data-title="企业信息" href="javascript:void(0)">企业信息</a></dt>
            </dl>
            <dl id="accountTitle" class="sliderItem">
                <dt><a data-href={{ route('account_subject.index') }} data-title="会计科目" href="javascript:void(0)">会计科目</a></dt>
            </dl>
            <dl id="transactionData" class="nav-item">
                <dt><a href="javascript:;" class="options">业务数据<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href={{ route('bussinessdata.index',['type'=>1]) }} data-title="客户" href="javascript:void(0)">客户</a></li>
                        <li><a data-href={{ route('bussinessdata.index',['type'=>2]) }} data-title="供应商" href="javascript:void(0)">供应商</a></li>
                        <li><a data-href={{ route('bussinessdata.index',['type'=>3]) }} data-title="其他往来" href="javascript:void(0)">其他往来</a></li>
                        <li><a data-href={{ route('bussinessdata.index',['type'=>4]) }} data-title="投资方" href="javascript:void(0)">投资方</a></li>
                        <li><a data-href={{ route('bankaccount.index') }} data-title="银行账户" href="javascript:void(0)">银行账户</a></li>
                    </ul>
                </dd>
            </dl>
            <dl id="initialData" class="nav-item">
                <dt><a href="javascript:;" class="options">初始数据<i class="iconfont menu_dropdown-arrow">&#xe623;</i></a></dt>
                <dd>
                    <ul>
                        <li><a data-href="{{ route('subjectBalance.subjectBalanceFirst') }}" data-title="财务" href="javascript:void(0)">财务</a></li>
                    </ul>
                </dd>
            </dl>
        </div>
    </div>
</aside>