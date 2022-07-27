

// test for vertical screen
if (window.innerWidth > window.innerHeight) {
    // horizontal
    sidebar.classList.add("active");
} else {
    // vertical
    sidebar.classList.remove("active");
    content.classList.add("vert");
}
