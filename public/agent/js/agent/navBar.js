$(".tabList>li").mouseenter(function(){
    $(this).find(".showList").show();
}).mouseleave(function(){
    $(this).find(".showList").hide();
});
$(".tabList>li").click(function(){
    $(this).addClass('activeBg').siblings().removeClass('activeBg')
})