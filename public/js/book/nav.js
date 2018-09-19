/*---------aside----------slide-------*/
$(function(){
    // slider收缩展开
    $('.nav-item>dt').on('click',function(){
            var display = $(this).next('dd').css('display');
            if (display == "none") {
                //展开未展开
                $('.nav-item').children('dd').slideUp(300);
                $(this).next('dd').slideDown(300);
                $(this).parent('dl').addClass('nav-show').siblings('dl').removeClass('nav-show');
                $('.nav-item li').removeClass('current');
            }else{
                //收缩已展开
                $(this).next('dd').slideUp(300);
                $('.nav-item.nav-show').removeClass('nav-show');
                $('.nav-item li').removeClass('current');
            }
    });
    /*--没3级菜单，选中二级菜单的状态--*/
    $('.sliderItem.nav-show').removeClass('nav-show');
    $('.sliderItem>dt').on('click',function(){
        $('.sliderItem.nav-show').removeClass('nav-show');
        $('.nav-item').children('dd').slideUp(300);
        $('.nav-item.nav-show').removeClass('nav-show');
        $(this).parent('dl').addClass('nav-show').siblings('dl').removeClass('nav-show');
    })
});

