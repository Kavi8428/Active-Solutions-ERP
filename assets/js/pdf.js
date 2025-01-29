
function generatePDF(){

    const element = document.getElementById('pdfCard');
    htmltopdf()
    .from(element)
    .save();

};