/*
 * File name: scripts.js
 * Last modified: 2021.11.07 at 11:59:29
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

$(document).ready(function () {
    let select2;
    let options;
    if ($('textarea').length > 0) {
        $('textarea').summernote({
            height: 200
        });
    }
    if ($('.datepicker').length > 0) {
        $(".datepicker").datetimepicker({
            format: 'yyyy-MM-DD HH:mm',
            icons: {
                time: "fas fa-clock",
                date: "fas fa-calendar-alt",
                up: "fas fa-arrow-up",
                down: "fas fa-arrow-down"
            }
        });
    }
    if ($('.timepicker').length > 0) {
        $(".timepicker").datetimepicker({
            format: 'HH:mm'
        });
    }
    if ($('select.select2').length > 0) {
        options = {
            theme: 'bootstrap4'
        };
        select2 = $('select.select2');

        if (select2.data('tags')) {
            options.tags = select2.data('tags');
        }
        select2.select2(options);
    }
    if ($('select.select2.not-required').length > 0) {
        select2 = $('select.select2.not-required');
        $.each(select2, function (i, element) {
            options = {
                theme: 'bootstrap4'
            };
            options.placeholder = {
                id: null, // the value of the option
                text: $(element).data('empty')
            }
            options.allowClear = true;
            $(element).select2(options);
        });
    }

    $('[data-toggle=tooltip]').tooltip({boundary: 'window'});
})

function render(props) {
    return function (tok, i) {
        return (i % 2) ? props[tok] : tok;
    };
}

function dzComplete(_this, file, mockFile = '', mediaMockFile = '') {
    if (mockFile !== '') {
        _this.removeFile(mockFile);
        mockFile = '';
    }
    if (mediaMockFile !== '' && _this.element.id === mediaMockFile.collection_name) {
        _this.removeFile(mediaMockFile);
        mediaMockFile = '';
    }
    if (file._removeLink) {
        file._removeLink.textContent = _this.options.dictRemoveFile;
    }
    if (file.previewElement) {
        return file.previewElement.classList.add("dz-complete");
    }
}

function dzCompleteMultiple(_this, file) {
    if (file._removeLink) {
        file._removeLink.textContent = _this.options.dictRemoveFile;
    }
    if (file.previewElement) {
        return file.previewElement.classList.add("dz-complete");
    }
}

function dzRemoveFile(file, mockFile = '', existRemoveUrl = '', collection, modelId, newRemoveUrl, csrf) {
    if (file.previewElement != null && file.previewElement.parentNode != null) {
        file.previewElement.parentNode.removeChild(file.previewElement);
    }
    //if(file.status === 'success'){
    if (mockFile !== '') {
        mockFile = '';
        $.post(existRemoveUrl,
            {
                _token: csrf,
                id: modelId,
                collection: collection,
            });
    } /*else {
        $.post(newRemoveUrl,
            {
                _token: csrf,
                uuid: file.upload.uuid
            });
    }*/
    //}
}

function dzRemoveFileMultiple(file, mockFile = [], existRemoveUrl = '', collection, modelId, newRemoveUrl, csrf) {
    if (file.previewElement != null && file.previewElement.parentNode != null) {
        file.previewElement.parentNode.removeChild(file.previewElement);
    }

    if (mockFile.length !== 0) {
        mockFile = [];
        $.post(existRemoveUrl,
            {
                _token: csrf,
                id: modelId,
                collection: collection,
                uuid: file.uuid,
            });
    }
    if (file.upload != null) {
        $('input#' + file.upload.uuid).remove();
    }
}

function dzSending(_this, file, formData, csrf) {
    _this.element.children[0].value = file.upload.uuid;
    formData.append('_token', csrf);
    formData.append('field', _this.element.dataset.field);
    formData.append('uuid', file.upload.uuid);
}

function dzSendingMultiple(_this, file, formData, csrf) {
    $(_this.element).prepend('<input type="hidden" name="image[]">');
    _this.element.children[0].value = file.upload.uuid;
    _this.element.children[0].id = file.upload.uuid;
    formData.append('_token', csrf);
    formData.append('field', _this.element.dataset.field);
    formData.append('uuid', file.upload.uuid);
}

function dzMaxfile(_this, file) {
    _this.removeAllFiles();
    _this.addFile(file);
}

function dzInit(_this, mockFile, thumb) {
    _this.options.addedfile.call(_this, mockFile);
    _this.options.thumbnail.call(_this, mockFile, thumb);
    mockFile.previewElement.classList.add('dz-success');
    mockFile.previewElement.classList.add('dz-complete');
}

function dzAccept(file, done, dzElement = '.dropzone', iconBaseUrl) {
    var ext = file.name.split('.').pop().toLowerCase();
    if (['jpg', 'png', 'gif', 'jpeg', 'bmp'].indexOf(ext) === -1) {
        var thumbnail = $(dzElement).find('.dz-preview.dz-file-preview .dz-image:last');
        var icon = iconBaseUrl + "/" + ext + ".png";
        thumbnail.css('background-image', 'url(' + icon + ')');
        thumbnail.css('background-size', 'contain');
    }
    done();
}

function initializeGoogleMaps() {

    $('form').on('keyup keypress', function (e) {
        let keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        const isEdit = document.getElementById("latitude").value != '' && document.getElementById("longitude").value != '';

        const latitude = parseFloat(document.getElementById("latitude").value) || -33.8688;
        const longitude = parseFloat(document.getElementById("longitude").value) || 151.2195;

        const map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: latitude, lng: longitude},
            zoom: 14
        });
        const marker = new google.maps.Marker({
            map: map,
            position: {lat: latitude, lng: longitude},
            draggable: true
        });

        // adds a listener to the marker
        // gets the coords when drag event ends
        // then updates the input with the new coords
        google.maps.event.addListener(marker, 'dragend', function (evt) {
            $("#latitude").val(evt.latLng.lat().toFixed(6));
            $("#longitude").val(evt.latLng.lng().toFixed(6));
            map.panTo(evt.latLng);
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = 'address';
        autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        // adds a listener to the marker
        // gets the coords when drag event ends
        // then updates the input with the new coords
        google.maps.event.addListener(marker, 'dragend', function (evt) {
            $("#latitude").val(evt.latLng.lat().toFixed(6));
            $("#longitude").val(evt.latLng.lng().toFixed(6));
            map.panTo(evt.latLng);
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    setLocationCoordinates(autocomplete.key, lat, lng);
                }
            });

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

        });
    }
}

function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById("latitude");
    const longitudeField = document.getElementById("longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}
