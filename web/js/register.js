var tongyi = document.getElementsByClassName("tongyi")[0].getElementsByTagName("img")[0]
console.log(tongyi)
var c = 0;
tongyi.addEventListener('click', function() {
    if (c == 0) {
        this.src = "./img/2.png";
        c = 1;
    } else {
        this.src = "./img/1.png";
        c = 0;
    }

    console.log(11)
})