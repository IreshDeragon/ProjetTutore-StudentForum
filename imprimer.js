function print(imprimerPlanning) {
    var maPage = window.open("", "PRINT", "height=400,width=600");
    maPage.document.write("<html><head><title>Forum de Stage</title>");
    maPage.document.write("<link rel=preconnect href=https://fonts.gstatic.com><link href=https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap rel=stylesheet><link rel=stylesheet href=squelette.css>");
    maPage.document.write("</head><body>");
    maPage.document.write("<h1>Planning</h1>");
    maPage.document.write("<div class=planningEntreprise>");

    maPage.document.write(document.getElementById(imprimerPlanning).innerHTML);
    maPage.document.write("</div>");
    maPage.document.write("</body></html>");

    maPage.focus();

    // this is needed for CSS to load before printing..
    setTimeout(function () {
        maPage.print();
        mywindow.close();
    }, 600);

    return true;
}