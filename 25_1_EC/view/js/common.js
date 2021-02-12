$(function (){
    // 共通タグの埋め込み
    $("<input>").attr({
        type : "hidden",
        name : "action_kind"
    }).appendTo("form");
});