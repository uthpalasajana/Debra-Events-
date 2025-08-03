$(document).ready(function () {
    $('#download').click(function () {
        domtoimage.toJpeg(document.getElementById('htmlContent'), {
            quality: 0.95
        }).then(function (dataUrl) {
            let link = document.createElement('a');
            link.download = 'ticket.jpeg';
            link.href = dataUrl;
            link.click();
        });
    });
});