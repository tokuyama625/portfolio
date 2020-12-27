//================================
//      スライド
//================================
$(document).ready(function () {
    $('.slider').bxSlider();
});
$(function () {
    $('.bxslider').bxSlider({
        mode: 'fade',
        captions: true,
        slideWidth: 600,
        auto: true,
        speed: 500,
        pause: 3000,
        infiniteLoop: true,
        responsive: true,
        touchEnabled: true,
        oneToOneTouch: true
    });
});
//================================
//      回文書
//================================
//配列
var data = [
    { str: "このセリフが最後です。", jpn: "もう一度押すと最初に戻ります。" },
    { str: "スタートボタンを", jpn: "押す度にセリフが変わります。" },
    { str: "これは、配列とタイマーメソッドを", jpn: "組み合わせて作っています。" },
    { str: "スタートボタンを連打しても", jpn: "複数タイマーが起動しないように工夫しています。" },
    { str: "下の時計は", jpn: "CANVASで作った時計です。" },
];
var timerId, index = 0, pos = 0;

function start() {
    //posクリア
    pos = 0;
    //jpnテキストクリア
    document.getElementById("jpn").textContent = "";
    //項目選択　1%6=1 、2%6=2 ・・・6%6=0
    index = (index + 1) % data.length;
    //タイマー止める　問題ここでタイマーを止める理由はなんでしょう？ヒント：授業で習ったタイマーメソッドの改善ができます。
    clearInterval(timerId);
    //タイマーを0.2秒に1回、ticks関数を呼び出し
    timerId = setInterval(ticks, 200);
}

function ticks() {
    //?番目の項目のstrを選択
    var str = data[index].str;
    //　　　　　　　　　　　　　　　　　　表示する文字の開始位置↓　　↓表示する文字の長さ
    document.getElementById("str").textContent = str.substr(0, pos);
    //posに1を足した後に、　pos　>　strの文字数の判定をする
    if (++pos > str.length) {
        //もしstrの文字を全て表示したらタイマーを止めてjpnを表示する。
        clearInterval(timerId);
        document.getElementById("jpn").textContent = data[index].jpn;
    }
}
//================================
//      ここから時計の描画
//================================
var ctx, h, m, s;

function gobj(id) { return document.getElementById(id); }

function init() {
    //描画宣言
    ctx = gobj("clock").getContext("2d");
    //1秒に1回tick関数呼び出し
    setInterval(tick, 1000);
}
//現在時刻を取得
function tick() {
    //(1)Image(HTMLImageElement)オブジェクトを生成します.
    const image = new Image();
    //(2)tickイベントにハンドラ関数を設定する.この中でimageの内容をcanvasに転写する
    image.onload = function (e) {
        //(3)canvas要素を生成する
        const canvas = document.createElement("canvas");
        //(4)canvasグラフィックのサイズを設定する. ここでは画像のサイズに合わせている.
        canvas.width = image.naturalWidth;
        canvas.height = image.naturalHeight;
        //(6)canvas要素にimageオブジェクトの内容を転写する.
        ctx.drawImage(image, 0, 0);
    };
    //(7)画像読み込み完了時の処理が定義されたので, 画像の読み込みを開始する.
    image.src = "img/unnamed.gif";
    //日時取得宣言メソッド
    var now = new Date();
    //時を取得
    h = now.getHours() % 12;
    //分を取得
    m = now.getMinutes();
    //秒を取得
    s = now.getSeconds();
    //pタグにtoTimeStringで現在時刻を取得して、文字として出力
    gobj("time").textContent = now.toTimeString();
    //時計の文字盤を描画関数に移動
    paint();
}
//時刻針を描画
function drawHand(rotation, length, width, color) {
    //座標系を保存
    ctx.save();
    ctx.lineWidth = width;
    ctx.strokeStyle = color;
    //座標系の原点を移動
    ctx.translate(256, 256);
    //座標系を回転
    ctx.rotate(rotation);
    //長針、短針、秒針を描画
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.lineTo(0, -length);
    ctx.stroke();
    //描画状態を保存した時点のものに戻す
    ctx.restore();
}

//時計の文字盤を描画
function paint() {
    //canvsをクリア
    ctx.clearRect(0, 0, 512, 511);
    //座標系を保存
    ctx.save();
    //座標系の原点をX150、Y150に移動
    ctx.translate(256, 256);
    //線の色を黒にする
    ctx.strokeStyle = "black";
    //π*2/60の角度
    var pitch = Math.PI * 2 / 60;
    for (var i = 0; i < 60; i++) {
        ctx.beginPath();
        //5本に1本、線を太くする
        ctx.lineWidth = (i % 5) == 0 ? 3 : 1;
        ctx.moveTo(0, -120);
        ctx.lineTo(0, -140);
        ctx.stroke();
        ctx.rotate(pitch);
    }
    ctx.restore();
    //長針、短針、秒針の角度を求める
    var radH = (Math.PI * 2) / 12 * h + (Math.PI * 2) / 12 * (m / 60);
    var radM = (Math.PI * 2) / 60 * m;
    var radS = (Math.PI * 2) / 60 * s;
    //現在時刻、長さ、太さ、色を設定
    drawHand(radH, 80, 6, "blue");
    drawHand(radM, 120, 4, "blue");
    drawHand(radS, 140, 2, "red");
}
//================================
//      モーダル
//================================
document.getElementById("modalOpen01").addEventListener("click", function () {
    document.getElementById("modal01").classList.add("active");
});
document.getElementById("modalClose01").addEventListener("click", function () {
    document.getElementById("modal01").classList.remove("active");
});
document.getElementById("modalOpen02").addEventListener("click", function () {
    document.getElementById("modal02").classList.add("active");
});
document.getElementById("modalClose02").addEventListener("click", function () {
    document.getElementById("modal02").classList.remove("active");
});
