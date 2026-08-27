document.addEventListener("DOMContentLoaded", function() {
    // HTML側で定義されたURLを取得
    var url = document.getElementById("output").getAttribute("data-url");

    // AJAXリクエストを作成
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // レスポンスを受け取った後、HTMLエレメントに出力
            document.getElementById("output").innerHTML = xhr.responseText;
        }
    };
    
    xhr.open("GET", url, true);
    xhr.send();
});
