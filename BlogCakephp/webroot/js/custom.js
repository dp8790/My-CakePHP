var images = [{
        src: 'http://localhost/BlogCakephp/img/bgslider/1.jpg'}, {
        src: 'http://localhost/BlogCakephp/img/bgslider/2.jpg'}, {
        src: 'http://localhost/BlogCakephp/img/bgslider/3.jpg'}, {
        src: 'http://localhost/BlogCakephp/img/bgslider/4.jpg'}];

function setBackground(images)
{
    var n = Math.floor(Math.random() * images.length);
    $("body").css({'background': 'url(' + images[n].src + ')  repeat fixed left top / cover'});
}

setBackground(images);
window.setInterval(function () {
    setBackground(images)
}, 3000);