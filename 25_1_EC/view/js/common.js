$(function (){
    // 共通タグの埋め込み
    $("<input>").attr({
        type : "hidden",
        name : "action_kind"
    }).appendTo("form");
});

/**
 * ActionKindの設定をする
 * @param {object} formObj formタグオブジェクト
 * @param {string} value 設定値
 */
function setActionKind(formObj, value) {
    formObj.find("[name='action_kind']").val(value);
}