$(document).ready(function() {

    $.ajax({
        url : '/tabard',
        method : 'GET',
        success : function(resp) {
            var tabard = new GuildTabard(JSON.parse(resp));
        },
        error : function(err) {
            console.log(err);
        }
    });

    function GuildTabard(guildMeta) {

        var canvas = document.getElementById('guild-tabard');
        var context = canvas.getContext('2d');

        var self = this,
            _width = canvas.width,
            _height = canvas.height,
            _src = [
                '/images/ring.png',
                '/images/shadow.png',
                '/images/background.png',
                '/images/overlay.png',
                '/images/border.png',
                '/images/emblem.png',
                '/images/hooks.png'
            ],

            // Colors that need to be applied to each layer
            _color = [
                null,
                null,
                guildMeta['background_color_data'],
                null,
                guildMeta['border_color_data'],
                guildMeta['icon_color_data'],
                null
            ],

            // Positions to overlay each image at
            _position = [
                [ 0, 0, (_width*216/240), (_width*216/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*216/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*210/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*210/240) ],
                [ (_width*31/240), (_width*40/240), (_width*147/240), (_width*159/240) ],
                [ (_width*33/240), (_width*57/240), (_width*125/240), (_width*125/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*32/240) ]
            ],
            _img = [ new Image(), new Image(), new Image(), new Image(), new Image(), new Image(), new Image() ];
            $(canvas).css('opacity', 0);
        ;

        self.drawImage = function() {

            // Draw onto canvas
            _img[0].src = _src[0];
            context.drawImage(_img[0], _position[0][0], _position[0][1], _position[0][2], _position[0][3]);

            $(canvas).animate({opacity: 1}, 400);
        };

        function _render(index) {
            var _oldCanvas = new Image(),
                _newCanvas = new Image();

            // Load in contents behind new layer
            _img[index].src = _src[index];

            _img[index].onload = function() {
                _oldCanvas.src = canvas.toDataURL('image/png');
            };

            _oldCanvas.onload = function() {
                canvas.width = 1;
                canvas.width = _width;
                context.drawImage(_img[index], _position[index][0], _position[index][1], _position[index][2], _position[index][3]);

                if (typeof _color[index] !== 'undefined' && _color[index] !== null) {
                    _colorize(_color[index][0], _color[index][1], _color[index][2]);
                }

                _newCanvas.src = canvas.toDataURL('image/png');
                context.drawImage(_oldCanvas, 0, 0, _width, _height);
            };

            _newCanvas.onload = function() {
                context.drawImage(_newCanvas, 0, 0, _width, _height);
                index++;

                if (index < _src.length) {
                    _render(index);
                } else {
                    $(canvas).animate({opacity: 1}, 400);
                }
            };
        };

        function _colorize(r, g, b) {
            var imageData = context.getImageData(0, 0, _width, _height),
                pixelData = imageData.data,
                i = pixelData.length,
                intensityScale = 19,
                blend = 1 / 3,
                added_r = r / intensityScale + r * blend,
                added_g = g / intensityScale + g * blend,
                added_b = b / intensityScale + b * blend,
                scale_r = r / 255 + blend,
                scale_g = g / 255 + blend,
                scale_b = b / 255 + blend;

            do {
                if (pixelData[i + 3] !== 0) {
                    pixelData[i] = pixelData[i] * scale_r + added_r;
                    pixelData[i + 1] = pixelData[i + 1] * scale_g + added_g;
                    pixelData[i + 2] = pixelData[i + 2] * scale_b + added_b;
                }
            } while (i -= 4);
            context.putImageData(imageData, 0, 0);
        };

        _render(0);
    };
});
