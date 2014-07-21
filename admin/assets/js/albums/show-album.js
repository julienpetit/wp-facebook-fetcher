(function($) {
    "use strict";


    /* Container of albums div */
    var divContentAlbums = $('#album-images');

    /* Button for importing selected images */
    var buttonImportSelection = $('button#album-button-import-selection');

    /* Button for all images selection */
    var buttonSelectAll = $('button#album-button-select-all');

    /* Selected images counter */
    var selectedCounter = $('.selected-count');

    $.fn.albums = {};

    /**
     * Return true is all in stage of @param state
     * @param  {[type]}  state [description]
     * @return {Boolean}       [description]
     */
    $.fn.albums.isAllState = function(state)  {
        var selected = true;

        $.each(divContentAlbums.find(".album-image"), function(i, value) {
            if (!$(value).hasClass(state) && !$(value).hasClass("uploaded"))
                selected = false;
        });

        return selected;
    }

    /**
     * Select all the images which have no been uploaded yet.
     * @return {[type]} [description]
     */
    $.fn.albums.toggleSelectAllImages = function() {
        if ($.fn.albums.isAllState('selected')) {
            $.each(divContentAlbums.find(".album-image"), function(i, value) {
                $.fn.albums.image.setSelected($(value), false);
            });
        } else {
            $.each(divContentAlbums.find(".album-image"), function(i, value) {
                if (!$(value).hasClass("uploaded"))
                    $.fn.albums.image.setSelected($(value), true);
            });
        }
    }

    $.fn.albums.message = {
        set: function(image, message) {
            image.find('.message').html(message);
        },

        display: function(image, bool) {
            var message = image.find('.message');
            if (bool) {
                message.show();
            } else {
                message.hide();
            }
        }
    };


    $.fn.albums.image = {

        /**
         * @param {[type]} image .album-image div
         * @param {[type]} bool  [description]
         */
        setSelected: function(image, bool) {
            if (bool) {
                if (!image.hasClass("selected")) {
                    image.addClass("selected");
                    $.fn.albums.selectedCounter.increase(1);
                }
            } else {
                if (image.hasClass("selected")) {
                    image.removeClass("selected");
                    $.fn.albums.selectedCounter.decrease(1);
                }
            }
        }

    };


    /**
     * Upload an image to the server and call callback when it's done.
     * @param  {[type]} imgSrc   [description]
     * @param  {[type]} fbid     [description]
     * @param  {[type]} callBack [description]
     * @return {[type]}          [description]
     */
    $.fn.albums.upload = function(imgSrc, fbid, callBack) {

        var data = {
            action: 'facebook_fetcher_albums_upload_image',
            imgSrc: imgSrc,
            fbid: fbid
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post("admin-ajax.php", data, function(response) {
            callBack("uploaded");
        });
    }

    /**
     * Upload all selected images
     * @return {[type]} [description]
     */
    $.fn.albums.uploadSelected = function() {
        $.each(divContentAlbums.find(".album-image.selected"), function(i, value) {

            var img = $(value).find('img');

            console.log(img.attr('src'));

            $.fn.albums.message.set($(value), "Enregistrement en cours...");
            $.fn.albums.message.display($(value), true);

            $.fn.albums.upload(
                img.attr('src'),
                img.data('fbid'),
                function(state) {
                    $.fn.albums.message.set($(value), "Enregistré.");

                    $.fn.albums.image.setSelected($(value), false);
                    $(value).addClass(state);
                });
        });
    }

    $.fn.albums.selectedCounter = {

        set: function(value) {
            selectedCounter.html(value);
        },

        get: function() {
            return parseInt(selectedCounter.html());
        },

        reset: function() {
            this.setCounter(0);
        },

        increase: function(value) {
            this.set(this.get() + value);
        },

        decrease: function(value) {
            this.set(this.get() - value);
        }
    }



    $(function() {

        divContentAlbums.delegate(".album-image", "click", function(event) {
            var album = $(event.currentTarget);

            if (!album.hasClass("uploaded")) {
                if (album.hasClass("selected")) {
                    $.fn.albums.image.setSelected(album, false);
                } else {
                    $.fn.albums.image.setSelected(album, true);
                }

            }
        });

        buttonSelectAll.on('click', function(event) {
            $.fn.albums.toggleSelectAllImages();

        })

        buttonImportSelection.on('click', function(event) {
            $.fn.albums.uploadSelected();
        })



        // isAllSelected();

        // Place your administration-specific JavaScript here

    });

}(jQuery));