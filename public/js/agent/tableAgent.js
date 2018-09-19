function computedHeight(){
    var windowHeight = $(window).height()-294;
    /*--body高度----*/
    var bodyHeight = $("#tableScroll").height();
    if((bodyHeight-windowHeight)>0){
        $("#tableScroll").height(windowHeight);
        $('.fixTableHeader').addClass("tableHeader")
    }
    $(window).resize(function () {
        var windowHeight = $(window).height()-300;
        $("#tableScroll").height(windowHeight)
    })
}
function stopList(){
    var windowHeight = $(window).height()-294;
    /*--body高度----*/
    var bodyHeight = $("#stopList").height();
    if((bodyHeight-windowHeight)>0){
        $("#stopList").height(windowHeight);
        $('.fixTableHeaderStop').addClass("tableHeader")
    }
    $(window).resize(function () {
        var windowHeight = $(window).height()-300;
        $("#stopList").height(windowHeight)
    })
}
$(".stopList").click(function(){
    setTimeout(function(){
        stopList()
    },20)
})
setTimeout(function(){
    computedHeight()
},20)
