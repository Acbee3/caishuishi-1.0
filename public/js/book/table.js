function computedHeight(){
    //table到浏览器的高度
    var tableTop = $('.tableScroll').offset().top;
    var windowHeight = $(window).height()-$('.tableScroll').offset().top - 15;
    /*table高度*/
    var tableHeight = $(".tableScroll").height();

    /*--------------待优化-----------*/
    if(tableHeight>windowHeight){
        $(".tableScroll").height(windowHeight);
        $('.fixTableHeader').addClass("tableHeader");
        $('.tableScroll').css('overflow-y','scroll');
        $('.accountFooter').addClass("tableHeader accounts");
    }
    $(window).resize(function () {
        var windowHeight = $(window).height()-$('.tableScroll').offset().top - 15;
        $(".tableScroll").height(windowHeight);
    })
}
function initHeight(){
    //table到浏览器的高度
    var tableTop = $('.tableScroll').offset().top;
    var windowHeight = $(window).height()-$('.tableScroll').offset().top - 40;
    /*table高度*/
    var tableHeight = $(".tableScroll").height();

    /*--------------待优化-----------*/
    if(tableHeight>windowHeight){
        $(".tableScroll").height(windowHeight);
        $('.fixTableHeader').addClass("tableHeader");
        $('.tableScroll').css('overflow-y','scroll');
        $('.accountFooter').addClass("tableHeader accounts");
    }
    $(window).resize(function () {
        var windowHeight = $(window).height()-$('.tableScroll').offset().top - 40;
        $(".tableScroll").height(windowHeight);
    })
}
function treeHeight(){
    /*-----treeHeight-----*/
    var treeHeight = $(".treeHeight").height();
    var treeTop = $('.treeHeight').offset().top;
    var windowTreeHeight = $(window).height()-$('.treeHeight').offset().top - 15;
    if(treeHeight>windowTreeHeight){
        $(".treeHeight").height(windowTreeHeight);
        $('.treeHeight').css('overflow-y','scroll');
    }
    $(window).resize(function () {
        var windowTreeHeight = $(window).height()-$('.treeHeight').offset().top - 15;
        $(".treeHeight").height(windowTreeHeight);
    })
}
/*
setTimeout(function(){
    computedHeight()
},2000)*/
