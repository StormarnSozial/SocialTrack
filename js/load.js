// navigation function
let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".navigation");
let search = document.querySelector(".bx-search");
let content = document.querySelector(".content");

btn.onclick = function () {
    sidebar.classList.toggle("active")
}
search.onclick = function () {
    sidebar.classList.toggle("active")
}

// test for vertical screen
if (window.innerWidth > window.innerHeight) {
    // horizontal
    sidebar.classList.add("active");
} else {
    // vertical
    sidebar.classList.remove("active");
    content.classList.add("vert");
}
