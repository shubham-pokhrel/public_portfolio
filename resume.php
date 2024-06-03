<?php
// Server-side PHP code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume</title>
    <script src="libs/pdfjs/pdf.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        #pdf-container {
            width: 80%;
            height: 90%;
            overflow: auto;
        }
        canvas {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div id="pdf-container"></div>
    <script>
        const url = 'files/Shubham_Pokhrel_CV.pdf'; // Path to your PDF file
        
        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'libs/pdfjs/pdf.worker.js';

        const container = document.getElementById('pdf-container');

        pdfjsLib.getDocument(url).promise.then((pdf) => {
            for (let i = 1; i <= pdf.numPages; i++) {
                pdf.getPage(i).then((page) => {
                    const viewport = page.getViewport({scale: 1.5});
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise.then(() => {
                        container.appendChild(canvas);

                        const textLayerDiv = document.createElement('div');
                        textLayerDiv.className = 'textLayer';
                        textLayerDiv.style.width = canvas.width + 'px';
                        textLayerDiv.style.height = canvas.height + 'px';
                        container.appendChild(textLayerDiv);

                        page.getTextContent().then((textContent) => {
                            pdfjsLib.renderTextLayer({
                                textContent: textContent,
                                container: textLayerDiv,
                                viewport: viewport,
                                textDivs: []
                            }).promise.then(() => {
                                const links = textLayerDiv.querySelectorAll('a');
                                links.forEach((link) => {
                                    link.target = '_blank';
                                });
                            });
                        });
                    });
                });
            }
        });
    </script>
</body>
</html>
