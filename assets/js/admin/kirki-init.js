if (wp.customize && wp.customize.controlConstructor && wp.customize.controlConstructor['kirki-background']) {
    wp.customize.controlConstructor['kirki-background'] = wp.customize.controlConstructor['kirki-background'].extend({
        initKirkiControl: function () {
            var control = this,
                value = control.setting._value,
                picker = control.container.find('.kirki-color-control');

            // Background-Control Init
            if (_.isUndefined(value['background-image'])) {
                control.setting._value = {
                    'background-attachment': '',
                    'background-color': '',
                    'background-image': '',
                    'background-position': '',
                    'background-repeat': '',
                    'background-size': '',
                };
            }

            // Hide unnecessary controls if the value doesn't have an image.
            if (_.isUndefined(value['background-image']) || '' === value['background-image']) {
                control.container.find('.background-wrapper > .background-repeat').hide();
                control.container.find('.background-wrapper > .background-position').hide();
                control.container.find('.background-wrapper > .background-size').hide();
                control.container.find('.background-wrapper > .background-attachment').hide();
            }

            // If we have defined any extra choices, make sure they are passed-on to Iris.
            if (!_.isUndefined(control.params.choices)) {
                picker.wpColorPicker(control.params.choices);
            }

            // Tweaks to make the "clear" buttons work.
            setTimeout(function () {
                clear = control.container.find('.wp-picker-clear');
                clear.click(function () {
                    control.saveValue('background-color', '');
                });
            }, 200);

            // Color.
            picker.wpColorPicker({
                change: function () {
                    setTimeout(function () {
                        control.saveValue('background-color', picker.val());
                    }, 100);
                }
            });

            // Background-Repeat.
            control.container.on('change', '.background-repeat select', function () {
                control.saveValue('background-repeat', jQuery(this).val());
            });

            // Background-Size.
            control.container.on('change click', '.background-size input', function () {
                control.saveValue('background-size', jQuery(this).val());
            });

            // Background-Position.
            control.container.on('change', '.background-position select', function () {
                control.saveValue('background-position', jQuery(this).val());
            });

            // Background-Attachment.
            control.container.on('change click', '.background-attachment input', function () {
                control.saveValue('background-attachment', jQuery(this).val());
            });

            // Background-Image.
            control.container.on('click', '.background-image-upload-button', function (e) {
                var image = wp.media({ multiple: false }).open().on('select', function () {

                    // This will return the selected image from the Media Uploader, the result is an object.
                    var uploadedImage = image.state().get('selection').first(),
                        previewImage = uploadedImage.toJSON().sizes.full.url,
                        imageUrl,
                        imageID,
                        imageWidth,
                        imageHeight,
                        preview,
                        removeButton;

                    if (!_.isUndefined(uploadedImage.toJSON().sizes.medium)) {
                        previewImage = uploadedImage.toJSON().sizes.medium.url;
                    } else if (!_.isUndefined(uploadedImage.toJSON().sizes.thumbnail)) {
                        previewImage = uploadedImage.toJSON().sizes.thumbnail.url;
                    }

                    imageUrl = uploadedImage.toJSON().sizes.full.url;
                    imageID = uploadedImage.toJSON().id;
                    imageWidth = uploadedImage.toJSON().width;
                    imageHeight = uploadedImage.toJSON().height;

                    // Show extra controls if the value has an image.
                    if ('' !== imageUrl) {
                        control.container.find('.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment').show();
                    }

                    control.saveValue('background-image', imageUrl);
                    preview = control.container.find('.placeholder, .thumbnail');
                    removeButton = control.container.find('.background-image-upload-remove-button');

                    if (preview.length) {
                        preview.removeClass().addClass('thumbnail thumbnail-image').html('<img src="' + previewImage + '" alt="" />');
                    }
                    if (removeButton.length) {
                        removeButton.show();
                    }
                });

                e.preventDefault();
            });

            control.container.on('click', '.background-image-upload-remove-button', function (e) {

                var preview,
                    removeButton;

                e.preventDefault();

                control.saveValue('background-image', '');

                preview = control.container.find('.placeholder, .thumbnail');
                removeButton = control.container.find('.background-image-upload-remove-button');

                // Hide unnecessary controls.
                control.container.find('.background-wrapper > .background-repeat').hide();
                control.container.find('.background-wrapper > .background-position').hide();
                control.container.find('.background-wrapper > .background-size').hide();
                control.container.find('.background-wrapper > .background-attachment').hide();

                if (preview.length) {
                    preview.removeClass().addClass('placeholder').html('No file selected');
                } if (removeButton.length) {
                    removeButton.hide();
                }
            });
        }
    });
}