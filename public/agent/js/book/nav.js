/*---------aside----------slide-------*/
$(function(){
    // nav收缩展开
    $('.nav-item>dt').on('click',function(){
        if (!$('body').hasClass('sidebar-collapse')) {
            var display = $(this).next('dd').css('display');
            if (display == "none") {
                //展开未展开
                $('.nav-item').children('dd').slideUp(300);
                $(this).next('dd').slideDown(300);
                $(this).parent('dl').addClass('nav-show').siblings('dl').removeClass('nav-show');
            }else{
                //收缩已展开
                $(this).next('dd').slideUp(300);
                $('.nav-item.nav-show').removeClass('nav-show');
            }
        }
    });
    //sidebar-collapse切换
   /* $('#miniBtn').on('click',function(){
        if (!$('body').hasClass('sidebar-collapse')) {
            $('.nav-item.nav-show').removeClass('nav-show');
            $('.nav-item').children('dd').removeAttr('style');
            $('body').addClass('sidebar-collapse');
        }else{
            $('body').removeClass('sidebar-collapse');
        }
    });*/
});

